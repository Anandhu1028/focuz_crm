@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
<script src="{{ asset('/js/datatables.min.js') }}"></script>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Student Document Verification</h3>
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('documents.verify') }}" method="GET" class="d-flex" style="max-width: 300px;">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-sm"
                            placeholder="Search..."
                            value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body table-responsive">
                @if($students->count())
                <table id="students_table" class="table table-bordered table-striped" style="font-size:10pt;">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Email</th>
                            <th>Phone</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $statuses = collect($student['documents'])->pluck('status')->toArray();
                            if (in_array('pending', $statuses)) $overall = 'pending';
                            elseif (in_array('rejected', $statuses)) $overall = 'rejected';
                            elseif (in_array('approved', $statuses)) $overall = 'approved';
                            else $overall = 'pending';

                            $first = $student['first_name'] ?? '';
                            $last = isset($student['last_name']) && strtolower(trim($student['last_name'])) !== 'n/a' ? $student['last_name'] : '';
                            $fullName = trim($first . ' ' . $last);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="#" class="text-primary student-link" data-student="{{ $student['id'] }}">
                                    {{ $fullName }}
                                </a>
                            </td>
                            <td></td>
                            <td class="{{ $overall == 'approved' ? 'text-success' : ($overall == 'rejected' ? 'text-danger' : 'text-warning') }}">
                                {{ ucfirst($overall) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>No students found.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Student Modal -->
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Documents Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="studentDocuments">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
const allStudents = @json($students);

$('#students_table').DataTable({
    lengthMenu: [5, 10, 25, 50],
    pageLength: 25,
    searching: false,
    paging: true,
    info: true
});

// Open modal on student name click
$(document).on('click', '.student-link', function(e) {
    e.preventDefault();
    const studentId = $(this).data('student');
    const student = allStudents.find(s => s.id === studentId);
    if (!student) return;

    const lastName = student.last_name && student.last_name.trim().toLowerCase() !== 'n/a' ? student.last_name : '';
    const fullName = ((student.first_name ?? '') + (lastName ? ' ' + lastName : '')).trim();

    let html = `<h5>Student: ${fullName}</h5>
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Upload Documents</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

    student.documents.forEach(doc => {
        let thumbHtml = '<span class="text-danger">No file ❌</span>';

        if (doc.document_path) {
            // Ensure correct base URL
            const baseUrl = "{{ asset('') }}".replace(/\/+$/, '') + '/';
            const relativePath = doc.document_path.replace(/^\/+/, '');
            const fileUrl = baseUrl + relativePath;

            const fileExt = doc.document_path.split('.').pop().toLowerCase();

            if (imageExtensions.includes(fileExt)) {
                thumbHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="border rounded p-1 me-2">
                                <img src="${fileUrl}" alt="Document" class="img-thumbnail" style="width:80px; height:auto;">
                            </div>
                            <span class="text-muted small"></span>
                        </div>
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-warning">
                            <i class="bi bi-eye"></i> Vew
                        </a>
                    </div>`;
            } else {
                thumbHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"> </span>
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-warning">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>`;
            }
        }

        html += `<tr>
                    <td>${doc.doc_category?.category_name ?? 'N/A'}</td>
                    <td>${thumbHtml}</td>
                    <td class="${
                        doc.status === 'approved' ? 'text-success' :
                        doc.status === 'rejected' ? 'text-danger' : 'text-warning'
                    }">${doc.status ?? 'Pending'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success update-status" data-id="${doc.id}" data-status="approved">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                        <button class="btn btn-sm btn-danger update-status" data-id="${doc.id}" data-status="rejected">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </td>
                 </tr>`;
    });

    html += `</tbody></table>`;
    $('#studentDocuments').html(html);

    const modalEl = document.getElementById('studentModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
});



// Approve / Reject via AJAX — live update
$(document).on('click', '.update-status', function() {
    const id = $(this).data('id');
    const status = $(this).data('status');
    if (!confirm(`Confirm ${status}?`)) return;

    $.post('{{ route("documents.update-status") }}', {
        _token: '{{ csrf_token() }}',
        document_id: id,
        status: status
    }, res => {
        if (res.success) {
            // Update modal table status
            const statusCell = $(`#studentDocuments button[data-id="${id}"]`).closest('tr').find('td').eq(2);
            statusCell
                .removeClass('text-success text-danger text-warning')
                .addClass(status === 'approved' ? 'text-success' : 'text-danger')
                .html(status === 'approved' ? 'Approved' : 'Rejected');

            // Update main Blade tick / cross if present
            const mainStatusCell = $(`td.doc-verified[data-doc-id="${id}"]`);
            if (mainStatusCell.length) {
                mainStatusCell.html(status === 'approved'
                    ? '<i class="fas fa-check text-success"></i>'
                    : '<i class="fas fa-times text-danger"></i>');
            }

            alert('Status updated!');
        } else {
            alert('Error updating status.');
        }
    });
});
</script>
@endsection
