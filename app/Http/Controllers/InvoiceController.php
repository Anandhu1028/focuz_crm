<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCPDF;

class InvoiceController extends Controller
{
    //

public function generateInvoice($pdfDirectory, $invoiceData, $name_append = '')
{
    $userId = Auth::id();
    $bg_color = "#015a85";

    require_once base_path('vendor/tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company');
    $pdf->SetTitle('Payment Voucher');
    $pdf->SetSubject('Invoice');
    $pdf->SetKeywords('TCPDF, PDF, invoice, voucher');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetFont('dejavusans', '', 12);

    $pdf->AddPage('P', 'A4');
    $imagePath = public_path('images/logo-gray.jpg');
    if(file_exists($imagePath)){
        $pdf->SetAlpha(0.1);
        $pdf->Image($imagePath, 55, 80, 100, 90, '', '', '', false, 300);
        $pdf->SetAlpha(1);
    }

    $html_data = view('payments.pdfs.invoice_data', [
        'bg_color' => $bg_color,
        'invoiceData' => $invoiceData
    ])->render();

    $pdf->writeHTML($html_data, true, false, false, false, '');

    // Ensure storage folder exists
    if (!file_exists($pdfDirectory)) {
        mkdir($pdfDirectory, 0777, true);
    }

    // Safe file name
    $track_id = $invoiceData['student_track_id'] ?? $invoiceData['invoice_number'] ?? 'unknown';
    $track_id = strtolower(str_replace("/", "_", str_replace(" ", "", $track_id)));
    $fileNamePrefix = "invoice_{$userId}";
    $file_name = "{$fileNamePrefix}_{$track_id}.pdf";
    $pdf_path = $pdfDirectory . '/' . $file_name;

    // Delete old files with same prefix
    $files = File::glob($pdfDirectory . "/{$fileNamePrefix}_*.pdf");
    foreach ($files as $file) {
        File::delete($file);
    }

    // Output PDF
    $pdf->Output($pdf_path, 'F');

    if (!file_exists($pdf_path)) {
        throw new \Exception("PDF generation failed at path: " . $pdf_path);
    }

    return $pdf_path; // return server path
}

   
}
