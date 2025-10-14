<div class="card card-default ">
    {{-- collapsed-card --}}
    <div class="card-header">
        <h3 class="card-title"> Document Upload</h3>
        <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="alert alert-sm alert-info d-none    " id="alert_messge_doc">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col table-responsive">
                <table class="table table-sm table-striped table-bordered w-100">
                    <tr>
                        <th>SL No</th>
                        <th>Category</th>
                        <th>Path</th>
                        <th>Doc Verified</th>
                    </tr>
                    @foreach ($documentsDataAr as $key => $documentsData)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $documentsData->doc_category->category_name }}</td>
                            <td>
                                <a href="{{ asset($documentsData->document_path) }}" target="_blank">View file</a>
                            </td>
                            <td class="text-center doc-verified">
                                @if($documentsData->status === 'approved')
                                <i class="fas fa-check text-success"></i> <!-- Green tick -->
                                @else
                                <i class="fas fa-times text-danger"></i> <!-- Red X -->
                                @endif
                            </td>

                    @endforeach
                </table>
            </div>

        </div>

    </div>


</div>
