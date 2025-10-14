@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
<script src="{{ asset('/js/datatables.min.js') }}"></script>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Document Verification</h3>
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
                @if($documents->count())
                <table id="documents_table" class="table table-bordered table-striped dataTable dtr-inline" style="font-size:10pt;">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Student</th>
                            <th>Category</th>
                            <!--<th>Document</th>-->
                            <th>Status</th>
                            <th>Upload Verification Screenshot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $key => $doc)
                        <tr id="row_{{ $doc->id }}">
                            {{-- SL No with pagination support --}}
                            <td>{{ ($documents->currentPage() - 1) * $documents->perPage() + ($key + 1) }}</td>

                            <td>
                                <a href="{{ route('view_profile', [1, $doc->student_id]) }}" target="_blank">
                                    {{ $doc->student->first_name }} {{ $doc->student->last_name }}
                                </a>
                            </td>
                            <td>{{ $doc->doc_category ? $doc->doc_category->category_name : 'N/A' }}</td>


                            <!--<td>-->
                            <!--        <a href="{{ asset('storage/'.$doc->document_path) }}" target="_blank">View File</a>-->
                            <!--</td>-->
                            <td class="{{ $doc->status === 'approved' 
                                    ? 'text-success' 
                                    : ($doc->status === 'rejected' 
                                        ? 'text-danger' 
                                        : 'text-warning') }}">
                                {{ $doc->status ? ucfirst($doc->status) : 'Verification Pending...' }}
                            </td>
                                    <td>
                                        @if($doc->document_path)
                                            @php
                                                $fileFullPath = public_path($doc->document_path);
                                                $fileExt = pathinfo($doc->document_path, PATHINFO_EXTENSION);
                                            @endphp
                                    
                                            @if(file_exists($fileFullPath))
                                                <div class="d-flex align-items-center">
                                                    @if(in_array(strtolower($fileExt), ['jpg','jpeg','png','gif']))
                                                        <div class="border rounded p-1 me-2">
                                                            <img src="{{ asset($doc->document_path) }}" 
                                                                 alt="Document" 
                                                                 class="img-thumbnail" 
                                                                 style="width:80px; height:auto;">
                                                        </div>
                                                    @else
                                                        <span class="text-muted me-2">{{ strtoupper($fileExt) }} File</span>
                                                    @endif
                                    
                                                    <div class="m-3">
                                                        <a href="{{ asset($doc->document_path) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="View File"
                                                           style="height: 30px; display: flex; align-items: center; justify-content: center;">
                                                            <span class="iconify" data-icon="mdi:eye" data-inline="false"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-danger">No file ❌</span>
                                            @endif
                                        @else
                                            <span class="text-danger">No file ❌</span>
                                        @endif
                                    </td>


                            <td>
                                <button type="button" class="btn btn-sm btn-info update-status"
                                    data-id="{{ $doc->id }}" data-status="approved">
                                    Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger update-status"
                                    data-id="{{ $doc->id }}" data-status="rejected">
                                    Reject
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $documents->links() }}
                @else
                <p>No documents found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    // Save screenshot via AJAX
    // Save screenshot via AJAX
    $(document).on('click', '.save-screenshot', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var fileInput = $('tr#row_' + id + ' .screenshot-input')[0];

        if (!fileInput.files.length) {
            alert("Please select a file first.");
            return;
        }

        var formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('document_id', id);
        formData.append('screenshot', fileInput.files[0]);

        $.ajax({
            url: "{{ route('documents.upload-screenshot') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message);

                    // Automatically refresh the page
                    window.location.reload();

                    // If you still want to keep the dynamic thumbnail update, you can keep the code below
                    // ... your previous thumbnail + input + buttons code
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr) {
                alert("Error: " + (xhr.responseJSON?.message || "Something went wrong"));
            }
        });
    });






    $(document).ready(function() {
        // Approve/Reject document status with optional screenshot upload
        $(document).on('click', '.update-status', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');
            var fileInput = $('tr#row_' + id + ' .screenshot-input')[0];

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('document_id', id);
            formData.append('status', status);

            // Append screenshot if selected
            if (fileInput && fileInput.files.length > 0) {
                formData.append('screenshot', fileInput.files[0]);
            }

            if (confirm("Are you sure to mark this document as " + status + "?")) {
                $.ajax({
                    url: "{{ route('documents.update-status') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // Reload to show updated screenshot link if uploaded
                            window.location.reload();
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert("Error: " + (xhr.responseJSON?.message || "Something went wrong"));
                    }
                });
            }
        });

        $('#documents_table').DataTable({
            lengthMenu: [10, 25, 100, 500],
            pageLength: 25,
            searching: false
        });
    });
</script>
@endsection