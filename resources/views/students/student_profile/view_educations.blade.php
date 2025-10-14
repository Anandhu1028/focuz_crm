<div class="row">
    <div class="col">
        {{-- View Educational Qualification --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Educational Qualification</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped">
                    <tbody>
                        @foreach ($educationDataAr as $educationData)
                            <!-- Row 1: Labels -->
                            <tr>
                                <th>SSLC Board</th>
                                <th>SSLC Passout Year</th>
                            </tr>
                            <!-- Row 2: Data -->
                            <tr>
                                <td>{{ $educationData->sslc_board }}</td>
                                 <td>{{ $educationData->sslc_passout }}</td>
                            </tr>
                
                            <!-- Row 3: Labels -->
                            <tr>
                                 <th>Intermediate Board</th>
                                  <th>Intermediate Passout Year </th>
                            </tr>
                            <!-- Row 4: Data -->
                            <tr>
                                <td>{{ $educationData->intermediate_board }}</td>
                                <td>{{ $educationData->intermediate_passout }}</td>
                            </tr>
                
                            <!-- Row 5: Labels -->
                            <tr>
                               <th>Last Completed Course</th>
                                 <th>University/Board/Institute </th>
                            </tr>
                            <!-- Row 6: Data -->
                            <tr>
                                 <td>{{ $educationData->other_degree_name }}</td>
                                 <td>{{ $educationData->other_college_name }}</td>
                            </tr>
                
                            <!-- Row 7: Labels -->
                            <tr>
                               <th>Passout Year</th>
                                <th>GPA</th>
                            </tr>
                            <!-- Row 8: Data -->
                            <tr>
                                <td>{{ $educationData->graduation_year }}</td>
                                <td>{{ $educationData->gpa }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- View University Details --}}
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">View University Details</h3>
            </div>
            <div class="card-body table-responsive">
                <table style="font-size:10pt;" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ABC ID</th>
                            <th>DEB ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($educationDataAr as $educationData)
                            <tr>
                                <td>{{ $educationData->abc_id }}</td>
                                <td>{{ $educationData->deb_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>