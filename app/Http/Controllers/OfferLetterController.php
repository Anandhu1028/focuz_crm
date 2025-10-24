<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Students;
use App\Models\Payments;
use App\Models\CoursePayments;
use App\Models\Branches;
use App\Models\User;

class OfferLetterController extends Controller
{
    public function download($student_id)
    {
        // Get latest payment for student
        $payment = Payments::with([
            'student.employee',
            'courses.university',
            'courses.streams'
        ])
            ->where('student_id', $student_id)
            ->latest()
            ->firstOrFail();

        $student = $payment->student;
        $course = $payment->courses;

        // Get the related course payment (for branch, CRE, etc.)
        $course_payment = CoursePayments::with(['branch', 'customer_relation_executive_user', 'customer_relation_executive_employee'])
            ->where('student_id', $student->id)
            ->where('course_id', $course->id ?? null)
            ->latest()
            ->first();

        $branch = $course_payment->branch ?? null;

        // Admission executive
        $admission_executive = $course_payment && $course_payment->created_by
            ? optional(User::find($course_payment->created_by))->name ?? 'N/A'
            : 'N/A';

        // Customer Relation Executive (CRE)
        $customer_relation_executive = 'N/A';
        if ($course_payment) {
            if ($course_payment->customer_relation_executive_employee) {
                $employee = $course_payment->customer_relation_executive_employee;
                $customer_relation_executive = trim("{$employee->first_name} {$employee->last_name}");
            } elseif ($course_payment->customer_relation_executive_user) {
                $customer_relation_executive = $course_payment->customer_relation_executive_user->name ?? 'N/A';
            }
        }


        // Prepare data for PDF
        $data = [
            'student' => $student,
            'course_name' => $course ? (optional($course->streams)->code . ' ' . $course->specialization) : 'N/A',
            'university_name' => $course->university->name ?? 'N/A',
            'branch_name' => $branch->name ?? 'N/A',
            'track_id' => $student->id,
            'total_fee' => $payment->amount ?? 0,
            'paid_amount' => $payment->amount ?? 0,
            'initial_receipt' => $payment->id,
            'admission_executive' => $admission_executive,
            'customer_relation_executive' => $customer_relation_executive,
        ];



        // Generate PDF
        $pdf = Pdf::setOption(['isRemoteEnabled' => true])
            ->loadView('payments.pdfs.offer_letter', $data);

        return $pdf->download('offerletter_' . $student_id . '.pdf');
    }
}
