@extends('layouts.admin')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    @php
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        if ($user) {
            $assignedRole = $user->roles->first();

            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            } else {
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp
    
         @if (session('status'))
            <div class="content">
                <div class="col-lg-6 col-6">

                    <div class="card">
                        {{-- <div class="card-header">
                    Chart
                </div> --}}

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                             <div>
                        <canvas id="myChart"></canvas>
                    </div> 
                        </div>
                    </div>

                </div>
            </div>
        @endif

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Dashboard
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="row justify-content-center">
                                 
                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $enquiry_count }}</h3>
                                            <p>Enquiry</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                      {{--  <a href="{{ route('admin.sellvehicle.index') }}" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a> --}}
                                    </div>
                                </div>
                                 @if ($roleTitle == 1)
                                    <div class="col-lg-2 col-4">

                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Underwriting }}<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Underwriting</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                      
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Processing }}</h3>
                                            <p>Processing</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                       
                                    </div>
                                </div>
                                 @if ($roleTitle == 1)
                                    <div class="col-lg-2 col-4">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Completions  }}</h3>
                                            <p>Submitted</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                     
                                    </div>
                                </div>
                                 @endif
                                 
                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            @php
                                                if ($roleTitle != 1){
                                                
                                                    $completed = ($statusCounts->Completed ?? 0 ) + ($statusCounts->Completions ?? 0 )  ;
                                                
                                                }else{
                                                  $completed = ($statusCounts->Completed ?? 0 ) ;
                                                
                                                }
                                             @endphp
                                            <h3 class="counter-value">{{$completed ?? 0}}</h3>
                                            <p>Completed</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                     
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        @include('partials.homecontent')
        
 @include('admin.application.partials.clientassignModel')
@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script>
    let $loading = $('.loading-overlay')
    //  $(document).ready(function() {
    //   // Function to initialize the DataTable
    //         function callAjax() {
    //             if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
    //                 $('.datatable-caseList').DataTable().destroy();
    //             }
    
    //             let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    //             dtButtons.splice(0, 10);
    //             let dtOverrideGlobals = {
    //                 buttons: dtButtons,
    //                 retrieve: true,
    //                 aaSorting: [],
    //                 ajax: {
    //                     url: "{{ route('admin.home.index') }}",
    //                 },
    //                 columns: [
    //                     { data: 'placeholder', name: 'placeholder' },
    //                     { data: 'id', name: 'id' },
    //                     { data: 'ref_no', name: 'ref_no' },
    //                     { data: 'customer_id', name: 'customer_id' },
    //                     // { data: 'loan_type_id', name: 'loan_type_id' },
    //                     { data: 'assigned_client_id', name: 'assigned_client_id' },
                       
    //                     { data: 'actions', name: 'actions' }
    //                 ],
    //                 orderCellsTop: true,
    //                 order: [[1, 'desc']],
    //                 pageLength: 10,
                   
    //             };
        
    //             let table = $('.datatable-caseList').DataTable(dtOverrideGlobals);
    //             $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
    //                 $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    //             });
    //         }
            
    //          function callAjax2() {
    //             if ($.fn.DataTable.isDataTable('.datatable-caseList2')) {
    //                 $('.datatable-caseList2').DataTable().destroy();
    //             }
    
    //             let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    //             dtButtons.splice(0, 10);
    //             let dtOverrideGlobals = {
    //                 buttons: dtButtons,
    //                 retrieve: true,
    //                 aaSorting: [],
    //                 ajax: {
    //                     url: "{{ route('admin.home.CompletedJob') }}",
    //                 },
    //                 columns: [
    //                     { data: 'placeholder', name: 'placeholder' },
    //                     { data: 'id', name: 'id' },
    //                     { data: 'ref_no', name: 'ref_no' },
    //                     { data: 'customer_id', name: 'customer_id' },
    //                     // { data: 'loan_type_id', name: 'loan_type_id' },
    //                     { data: 'assigned_client_id', name: 'assigned_client_id' },
                       
    //                     { data: 'actions', name: 'actions' }
    //                 ],
    //                 orderCellsTop: true,
    //                 order: [[1, 'desc']],
    //                 pageLength: 10,
                   
    //             };
        
    //             let table = $('.datatable-caseList2').DataTable(dtOverrideGlobals);
    //             $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
    //                 $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    //             });
    //         }
            
            
            
    //          callAjax().then(() => {
    //             callAjax2();
    //         });
    //  });
    
  
    function callAjax() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
            $('.datatable-caseList').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.index') }}",
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        // Call callAjax2() directly after callAjax() completes
        callAjax2();
    }
    
    function callAjax2() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList2')) {
            $('.datatable-caseList2').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.CompletedJob') }}",
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList2').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        
         callAjax3();
    }
    
     function callAjax3() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList3')) {
            $('.datatable-caseList3').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.CanceledJob') }}",
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList3').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    }
    
    // Call callAjax() on document ready
        callAjax();
    
       function callAjax4() {
                if ($.fn.DataTable.isDataTable('.datatable-caseList4')) {
                    $('.datatable-caseList4').DataTable().destroy();
                }
                
                let dtOverrideGlobals = {
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.home.EnquiryJob') }}",
                    },
                    columns: [
                        { data: 'placeholder', name: 'placeholder' },
                        { data: 'id', name: 'id' },
                        // { data: 'loan_category_type', name: 'loan_category_type' },
                        // { data: 'client_loan_amount', name: 'client_loan_amount' },
                        { data: 'client_first_name', name: 'client_first_name' },
                        // { data: 'client_email', name: 'client_email' },
                        // { data: 'client_phone', name: 'client_phone' },
                         @if($roleTitle == 1)
                            { data: 'created_by', name: 'created_by' },
                         @endif
                        // { data: 'created_date', name: 'created_date' },
                        // { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions' }
                    ],
                    orderCellsTop: true,
                    order: [[1, 'desc']],
                    pageLength: 10,
                };
                
                let table = $('.datatable-caseList4').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                });
            }
            
            callAjax4();
  

    </script>
    @include('admin.application_stage.partials.caseList')
 
@endsection
