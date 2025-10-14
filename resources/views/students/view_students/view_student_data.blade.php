<table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Actions</th>
            <th>Name</th>
            <th>Track Id</th>
            <th>Email</th>
            <th>Phone</th>
            <th>University</th>
            <th>Course</th>
            <th>Course Fee</th>
            <th>Pending Amount</th>
            <th>Payed Amount</th>
            <th>Payment Status</th>
            <th>Document Verification</th>
            <th>Profile Status</th>
            <th>Next Payment Date</th>
        </tr>
    </thead>
    <tbody id="tbody_view_students">


        @foreach ($students_data as $student)
        @php
        $pending_amount = '';
        $payed_amount = floatval($student->amount) + floatval($student->discount);
        $payment_status = '';
        $next_payment_date = '';
        $class_payment_date = '';
        $class = '';

        $course_data = App\Models\CourseSchedules::with([
        'course:id,specialization,stream_id,university_id', // include course_fee and other_fees directly
        'course.streams:id,code',
        'course.university:id,university_code',
        ])
        ->where('id', $student->course_schedule_id)

        ->first();
        if ($course_data && $course_data->course) {
        $course = $course_data->course; // To avoid repeated access
        $university = $course->university->university_code ?? '';
        $specialization = $course->specialization ?? '';
        $stream_code = $course->streams->code ?? '';
        $course_fee = floatval($course_data->course_fee) ?? 0;
        $other_fees = floatval($course_data->other_fees) ?? 0;
        $course_fee += $other_fees;

        $pending_amount = $course_fee - $payed_amount;
        } else {
        // Default values if no course data is found
        $university = '';
        $specialization = '';
        $stream_code = '';
        $course_fee = '';
        $other_fees = '';
        }

        if ($student->next_payment_date == null) {
        $next_payment_date = '';
        $class_payment_date = 'text-danger';
        } else {
        $next_payment_date = date('d-m-Y', strtotime($student->next_payment_date));
        if ($student->next_payment_date <= date('Y-m-d')) {
            $class_payment_date='text-danger' ;
            } else {
            $class_payment_date='text-success' ;
            }
            }
            if ($student->payment_status == 'pending') {
            $class = 'text-danger';
            } else {
            $class = 'text-success';
            }
            @endphp

            <tr>
                <td>
                    <button type="button" class="btn btn-sm btn-dark" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu" style="cursor:pointer">
                        <li class="dropdown-item">
                            <a target="_blank" href="{{ route('view_profile', [1, $student->id]) }}">View
                                Profile</a>

                        </li>
                        <li class="dropdown-item">
                            <a target="_blank" target="blank" href="{{ route('add_students', [1, $student->id]) }}">Edit
                                Profile</a>
                        </li>

                    </ul>
                </td>

                <td class="{{ $class }}">
                    <a target="_blank" data-toggle="popover" title="View Profile"
                        href="{{ route('view_profile', [1, $student->id]) }}">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </a>
                </td>
                <td>
                    <a target="_blank" target="blank" data-toggle="popover" title="Edit Profile"
                        href="{{ route('add_students', [1, $student->id]) }}">{{ $student->student_track_id }}</a>
                </td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone_number }}</td>

                <td>{{ $university }}</td>
                <td>{{ $stream_code }} {{ $specialization }}</td>
                <td>{{ number_format(floatval($course_fee), 2) }}</td>

                <td>{{ number_format(floatval($pending_amount), 2) }}</td>
                <td>{{ number_format(floatval($payed_amount), 2) }}</td>

                <td class="{{ $class }}"> {{ ucwords($student->payment_status) }}</td>
                <td class="{{ $student->document_status === 'approved' ? 'text-success' : ($student->document_status === 'rejected' ? 'text-danger' : 'text-warning') }}">
                    {{ $student->document_status ? ucfirst($student->document_status) : ' Pending' }}
                </td>
                <td> {{ $student->profile_completion * 25 }}%</td>
                <td class="{{ $class_payment_date }}"> {{ $next_payment_date }}</td>



            </tr>
            @endforeach
    </tbody>
</table>



<script>
    $(document).ready(function() {

    let table = $('#view_students').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("view_students") }}',
            type: 'POST',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
                d.name = $('#filter_name').val() || '';
                d.email = $('#filter_email').val() || '';
                d.phone_number = $('#filter_phone').val() || '';
                d.gender = $('#filter_gender').val() || '';
                d.pending_payments = $('#filter_pending_payments').val() || '';
                d.course = $('#filter_course').val() || '';
            }
        },
        columns: [
            { data: 'actions', orderable: false, searchable: false },
            { data: 'full_name', name: 'first_name' },
            { data: 'student_track_id', name: 'student_track_id' },
            { data: 'email', name: 'email' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'university', name: 'university' },
            { data: 'course', name: 'course' },
            { data: 'course_fee', name: 'course_fee' },
            { data: 'pending_amount', name: 'pending_amount' },
            { data: 'payed_amount', name: 'payed_amount' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'document_status', name: 'document_status' },
            { data: 'profile_completion', name: 'profile_completion' },
            { data: 'next_payment_date', name: 'next_payment_date' },
        ],
        lengthMenu: [10, 25, 50, 100, 500, 1000],
        pageLength: 25,
        order: [[1, 'asc']],
    });

    // Redraw on filter change
    $('#filter_form input, #filter_form select').on('change keyup', function() {
        table.draw();
    });

    // Export to Excel with current filters
    $('#export_excel').click(function(e) {
        e.preventDefault();
        let params = table.ajax.params();
        params.excel = 'true';
        let form = $('<form method="POST" action="{{ route("view_students") }}"></form>');
        $.each(params, function(k,v){
            form.append($('<input>').attr('name', k).val(v));
        });
        form.append($('<input>').attr('name', '_token').val('{{ csrf_token() }}'));
        $('body').append(form);
        form.submit();
    });
});

</script>