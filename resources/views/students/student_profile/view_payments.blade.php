<div class="row">
    <div class="col">
        <div class="alert alert-info">
            Feature can be added: Invoices can be shared digitally, reducing your workflow.
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Payments</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <td>Course</td>
                            <td>Payment Method</td>
                            <td>Bank</td>
                            <td>Card Type</td>
                            <td>Trans Date</td>
                            <td>Trans Ref</td>
                            <td>Course Fee</td>
                            <td>Amount</td>
                            <td>Discount Amount</td>
                            <td>Total Amount</td>
                            <td>Total Payed Amount</td>
                            <td>Balance Amount</td>
                            <td>Status</td>
                            <td>Invoice</td>
                            <td>Offer Letter</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coursePaymentsAr as $coursePayment)
                        dd( $coursePayment);
                        @php
                        $course = $coursePayment->courses;
                        $university = $course?->universities;
                        $stream = $course?->stream;
                        $schedule = $course?->course_schedules;
                        $course_name = trim(($stream->code ?? '') . ' ' . ($course->specialization ?? ''));
                        $course_fee = $schedule->course_fee ?? 0;

                        $paymentsDataAr = $coursePayment->payments ?? collect();
                        $total_paid_amount = $paymentsDataAr->where('status', 'active')->sum(fn($p) => ($p->amount ?? 0) + ($p->discount_amount ?? 0));
                        $balance_amount = max(0, $course_fee - $total_paid_amount);
                        @endphp

                        @foreach ($paymentsDataAr as $payment)
                        @php
                        $amount = $payment->amount ?? 0;
                        $discount_amount = $payment->discount_amount ?? 0;
                        $payed_amount = $amount + $discount_amount;

                        $table_class = match($payment->status) {
                        'active' => 'table-primary',
                        'pending' => 'table-warning',
                        'reversed' => 'table-danger',
                        default => '',
                        };
                        @endphp

                        <tr class="{{ $table_class }}">
                            <td>{{ $university->university_code ?? '' }} {{ $course_name }}</td>
                            <td>{{ $payment->payment_methods->method_name ?? '-' }}</td>
                            <td>{{ $payment->banks->bank_name ?? '-' }}</td>
                            <td>{{ $payment->card_types->type_name ?? '-' }}</td>
                            <td>{{ $payment->payment_date ? date('d-m-Y', strtotime($payment->payment_date)) : '-' }}</td>
                            <td>{{ $payment->transaction_ref ?? '-' }}</td>
                            <td>{{ number_format($course_fee, 2) }}</td>
                            <td>{{ number_format($amount, 2) }}</td>
                            <td>{{ number_format($discount_amount, 2) }}</td>
                            <td>{{ number_format($payed_amount, 2) }}</td>
                            <td>{{ number_format($total_paid_amount, 2) }}</td>
                            <td>
                                @if ($balance_amount > 0)
                                <a href="{{ route('add_students', [4, $student_id, $coursePayment->course_id, $coursePayment->course_schedule_id]) }}" target="_blank">
                                    {{ number_format($balance_amount, 2) }}
                                </a>
                                @else
                                {{ number_format($balance_amount, 2) }}
                                @endif
                            </td>
                            <td>{{ ucwords($payment->status) }}</td>

                            <td>
                                @if (in_array($payment->status, ['pending', 'reversed']))
                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                @elseif ($payment->verified_by)
                                <i class="fa fa-file-pdf text-danger download_invoice" data-id="{{ $payment->id }}" style="font-size:12pt;cursor:pointer"></i>
                                @else
                                CONTACT IT
                                @endif
                            </td>
                            <td>
                                @if (in_array($payment->status, ['pending', 'reversed']))
                                <a href="{{ route('payments_approve', ['reversed']) }}" target="_blank">Process Payment</a>
                                @elseif ($payment->verified_by)
                                <i class="fa fa-envelope text-success download_offer_letter" data-id="{{ $payment->student_id }}" style="font-size:12pt;cursor:pointer"></i>
                                @else
                                CONTACT IT
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<style>
    td:hover {
        max-width: 800px !important;
        white-space: normal !important;
    }
</style>

<script>
    $(document).ready(function() {

        $('.download_invoice').click(function(e) {
            e.preventDefault();

            var checkedId = $(this).data('id');

            // Create a form dynamically
            var form = $('<form>', {
                method: 'POST',
                action: "{{ route('invoice_print') }}"
            });

            // CSRF token
            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: "{{ csrf_token() }}"
            }));

            // Add the selected ID
            form.append($('<input>', {
                type: 'hidden',
                name: 'checkedIdsJson[]',
                value: checkedId
            }));

            // Append form to body, submit, then remove it
            $('body').append(form);
            form.submit();
            form.remove();
        });



        $(document).on("click", ".download_offer_letter", function(e) {
            e.preventDefault();
            let paymentId = $(this).data("id");
            window.location.href = "/offer_letter/download/" + paymentId;
        });
    });
</script>   