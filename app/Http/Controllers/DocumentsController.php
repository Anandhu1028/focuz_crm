<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Students;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{
    /**
     * Upload student documents.
     * Newly uploaded documents default to "rejected" status.
     */
    public function uploadStudentDocs(Request $request)
    {
        if (count($request->file()) === 0) {
            return response()->json(['message' => 'Upload at least one file!'], 422);
        }

        $documentCategories = $request->input('document_category', []);

        $rules = [
            'student_id' => 'required|exists:students,id',
            'document_category.*' => 'nullable|string',
        ];

        // Validation rules per category
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

        $studentId = $request->input('student_id');
        $filePaths = [];

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

        if ($existingDocument && file_exists(public_path($existingDocument->document_path))) {
            unlink(public_path($existingDocument->document_path));
        }

        $fileName = "{$documentCategory}_{$studentId}_" . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);

        $relativePath = 'student_docs/' . $fileName;
        $filePaths[$documentCategory] = [
            'path' => asset($relativePath),
            'status' => 'rejected' // Always show rejected for new upload
        ];

        Documents::updateOrCreate(
            ['student_id' => $studentId, 'doc_category_id' => $documentCategory],
            ['document_path' => $relativePath, 'uploaded_by' => Auth::id(), 'status' => 'rejected']
        );
    }


        }

        $this->update_profile_completion($studentId, 2, 3);
        $profileCompleted = $this->getProfileCompletedState($studentId);

        return response()->json([
            'message' => '<i class="fa fa-check-circle text-success">&nbsp;</i>Files uploaded successfully!',
            'file_paths' => $filePaths,
            'profile_completed' => $profileCompleted
        ], 200);
    }

    /**
     * View document in browser.
     */
    public function viewDocument($id)
    {
        $document = Documents::findOrFail($id);
        $filePath = public_path($document->document_path);

        if (!file_exists($filePath)) {
            abort(404, 'Document not found.');
        }

        return response()->file($filePath);
    }

    /**
     * Show verify page - only documents that are not approved (rejected or pending)
     */
    public function verify(Request $request)
    {
        $search = $request->input('search');

        $studentsQuery = Students::query();

        if (!empty($search)) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Include students with at least one document not approved OR no documents (new student)
        $studentsQuery->where(function ($q) {
            $q->doesntHave('documents')
              ->orWhereHas('documents', function ($q2) {
                  $q2->whereIn('status', ['pending', 'rejected']);
              });
        });

        $students = $studentsQuery->with('documents.doc_category')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name ?? 'N/A',
                'last_name' => $student->last_name ?? 'N/A',
                'email' => $student->email ?? 'N/A',
                'phone_number' => $student->phone_number ?? 'N/A',
                'documents' => $student->documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'doc_category' => $doc->doc_category ? ['category_name' => $doc->doc_category->category_name] : null,
                        'document_path' => $doc->document_path,
                        'status' => $doc->status ?? 'rejected',
                    ];
                })->values()
            ];
        });

        return view('documents.verify', compact('students', 'search'));
    }

    /**
     * Update document status (approve or reject)
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'status' => 'required|in:approved,rejected'
        ]);

        try {
            $doc = Documents::findOrFail($request->document_id);

            $doc->update([
                'status' => $request->status,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'doc_verified' => $request->status === 'approved' ? 1 : 0
            ]);

            return response()->json([
                'success' => true,
                'status' => $doc->status,
                'doc_verified' => $doc->doc_verified,
                'message' => 'Document has been ' . $doc->status
            ]);
        } catch (\Throwable $e) {
            Log::error('Document update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload verification screenshot.
     */
    public function uploadScreenshot(Request $request)
    {
        try {
            $request->validate([
                'document_id' => 'required|exists:documents,id',
                'screenshot' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $doc = Documents::findOrFail($request->document_id);
            $file = $request->file('screenshot');

            $storagePath = storage_path('app/public/verification_screenshots');
            if (!file_exists($storagePath)) mkdir($storagePath, 0755, true);

            // Delete old screenshot
            if ($doc->verification_screenshot && file_exists(storage_path('app/public/' . $doc->verification_screenshot))) {
                unlink(storage_path('app/public/' . $doc->verification_screenshot));
            }

            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('public/verification_screenshots', $fileName);

            $doc->verification_screenshot = 'verification_screenshots/' . $fileName;
            $doc->save();

            return response()->json([
                'success' => true,
                'message' => 'Screenshot uploaded successfully!',
                'screenshot_url' => asset('storage/' . $doc->verification_screenshot)
            ]);
        } catch (\Throwable $e) {
            Log::error("Screenshot upload failed: " . $e->getMessage(), ['document_id' => $request->document_id ?? null]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View verification screenshot.
     */
    public function viewScreenshot($id)
    {
        $doc = Documents::findOrFail($id);

        if (!$doc->verification_screenshot) abort(404, 'Screenshot not found.');

        $filePath = storage_path('app/public/' . $doc->verification_screenshot);
        if (!file_exists($filePath)) abort(404, 'File does not exist.');

        return response()->file($filePath);
    }
}
