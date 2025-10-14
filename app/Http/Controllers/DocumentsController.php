<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{

    public function uploadStudentDocs(Request $request)
    {
        if (count($request->file()) == 0) {
            return response()->json(['message' => 'Upload at least one file!'], 422);
        }

        $documentCategories = $request->input('document_category');

        $rules = [
            'student_id' => 'required|exists:students,id',
            'document_category.*' => 'nullable|string',
        ];

        // Set validation rules per document category
        foreach ($documentCategories as $documentCategory) {
            if ($documentCategory == '18') {
                $rules['file_' . $documentCategory] = 'nullable|file|mimes:mp4,mp3,m4a,opus,ogg,3gp|max:10192';
            } else {
                $rules['file_' . $documentCategory] = 'nullable|file|mimes:jpg,png,jpeg,pdf|max:2048';
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePaths = [];
        $studentId = $request->input('student_id');

        // Folder inside public/
        $destinationPath = public_path('student_docs');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        foreach ($documentCategories as $documentCategory) {
            $file = $request->file('file_' . $documentCategory);

            if ($file) {
                $existingDocument = Documents::where('student_id', $studentId)
                    ->where('doc_category_id', $documentCategory)
                    ->first();

                if ($existingDocument) {
                    $existingFilePath = public_path($existingDocument->document_path);
                    if (file_exists($existingFilePath)) {
                        unlink($existingFilePath);
                    }
                }

                $fileName = "{$documentCategory}_{$studentId}_" . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $relativePath = 'student_docs/' . $fileName;
                $filePaths[$documentCategory] = asset($relativePath);

                Documents::updateOrCreate(
                    ['student_id' => $studentId, 'doc_category_id' => $documentCategory],
                    ['document_path' => $relativePath, 'uploaded_by' => Auth::id(), 'status' => 'approved']
                );
            }
        }

        $this->update_profile_completion($studentId, 2, 3);
        $prolfile_completed = $this->getProfileCompletedState($studentId);

        return response()->json([
            'message' => '<i class="fa fa-check-circle text-success">&nbsp;</i>Files uploaded successfully!',
            'file_paths' => $filePaths,
            'prolfile_completed' => $prolfile_completed
        ], 200);
    }



    public function viewDocument($id)
    {
        $document = Documents::findOrFail($id);

        // Full path to the uploaded file
        $filePath = storage_path('app/' . $document->document_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath); // Opens file in browser
    }




    // Show verification page (list)
    public function verify(Request $request)
    {
        $search = $request->input('search');

        $documentsQuery = Documents::with(['student', 'doc_category', 'verifiedBy']);

        if (!empty($search)) {
            $documentsQuery->whereHas('student', function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $documents = $documentsQuery->paginate(25)->withQueryString();

        return view('documents.verify', compact('documents', 'search'));
    }



    // Upload verification screenshot
    public function uploadScreenshot(Request $request)
    {
        try {
            $request->validate([
                'document_id' => 'required|exists:documents,id',
                'screenshot'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $doc = Documents::findOrFail($request->document_id);
            $file = $request->file('screenshot');

            // Ensure storage folder exists
            $storagePath = storage_path('app/public/verification_screenshots');
            if (!file_exists($storagePath)) {
                if (!mkdir($storagePath, 0755, true) && !is_dir($storagePath)) {
                    throw new \RuntimeException("Cannot create directory: {$storagePath}");
                }
            }

            // Delete old screenshot if exists
            if ($doc->verification_screenshot && file_exists(storage_path('app/public/' . $doc->verification_screenshot))) {
                unlink(storage_path('app/public/' . $doc->verification_screenshot));
            }

            // Generate unique file name
            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            // Store file
            $file->storeAs('public/verification_screenshots', $fileName);

            // Save path in DB
            $doc->verification_screenshot = 'verification_screenshots/' . $fileName;
            $doc->save();

            return response()->json([
                'success' => true,
                'message' => 'Screenshot uploaded successfully!',
                'screenshot_url' => asset('storage/' . $doc->verification_screenshot)
            ]);
        } catch (\Throwable $e) {
            // Log detailed error
            Log::error("Screenshot upload failed: " . $e->getMessage(), [
                'document_id' => $request->document_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    // Serve image from DB
    // Serve screenshot from storage
    public function viewScreenshot($id)
    {
        try {
            $doc = Documents::findOrFail($id);

            if (!$doc->verification_screenshot) {
                abort(404, 'Screenshot not found.');
            }

            $filePath = storage_path('app/public/' . $doc->verification_screenshot);

            if (!file_exists($filePath)) {
                abort(404, 'File does not exist on server.');
            }

            return response()->file($filePath);
        } catch (\Throwable $e) {
            \Log::error("Screenshot view failed: " . $e->getMessage(), ['id' => $id]);
            abort(500, 'Unable to display screenshot.');
        }
    }

    public function updateStatus(Request $request)
{
    try {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'status' => 'required|in:approved,rejected'
        ]);

        $doc = Documents::findOrFail($request->document_id);

        $doc->update([
            'status'      => $request->status,
            'verified_by' => auth()->id() ?? null,
            'verified_at' => now(),
            'doc_verified'=> $request->status === 'approved' ? 1 : 0
        ]);

        return response()->json([
            'success'      => true,
            'status'       => $doc->status,
            'doc_verified' => $doc->doc_verified,
            'message'      => 'Document has been ' . $doc->status
        ]);

    } catch (\Throwable $e) {
        \Log::error('Document update failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
    