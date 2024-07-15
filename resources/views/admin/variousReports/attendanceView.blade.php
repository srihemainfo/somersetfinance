@php
    if (auth()->user()->roles[0]->id == 5) {
        $key = 'layouts.studentHome';
    } elseif (auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 12 || auth()->user()->roles[0]->id == 2 || auth()->user()->roles[0]->id == 4 || auth()->user()->roles[0]->id == 3) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.teachingStaffHome';
    }
@endphp
@extends($key)
@section('content')
    <div class="card" id="report">
        <div class="card-header">
            <p class="text-center">Student Leave / OD History</p>
            <div class="row">
                <div class="col-md-6 col-12">
                    <p class="text-dark"><b class="text-primary" style="color: #006cdf">Class : </b>
                        @if ($get_enroll != '')
                            {{ $get_enroll->enroll_master_number }}
                        @else
                        @endif
                    </p>
                </div>
                <div class="col-md-6 col-12">
                    <p class="text-dark"><b class="text-primary" style="color: #006cdf">Student : </b>
                        @if ($student != '')
                            {{ $student->name }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>From Date</th>
                        <th> To Date</th>
                        <th> Leave Type</th>
                    </tr>
                </thead>
                <tbody id="tbody">

                    @if (count($theArray) > 0)
                        @foreach ($theArray as $id => $req)
                            <tr>
                                <td>{{ $id + 1 }}</td>
                                <td>{{ $req['from_date'] }}</td>
                                <td>{{ $req['to_date'] }}</td>
                                <td>{{ $req['leave_type'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Data Available..</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
