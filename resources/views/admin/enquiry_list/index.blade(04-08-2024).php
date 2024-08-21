@extends('layouts.admin')
@section('content')

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
   
@endphp

 
    <style>
        .select2 {
            width: 100% !important;
        }
        .input-group {
            justify-content: end;
        }
    </style>
    
    <div class="col-sm-12 main-card mb-3 card">

    <div class="card-header">
        <h4 class="card-title">Enquiry Filter</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 row mb-2">
              
                <div class="col-sm-3">
                    <label for="loan_type_filter" class="col-form-label"> Loan Type</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="loan_type_filter" name="loan_type_filter"  >
                        @foreach ($loan_types as $id => $value)
                            <option value="{{ $id ?? ''}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="customer_name_filter">Cutomer Name</label>
                    <input type="text" class="form-control" id="customer_name_filter" placeholder="Search by customer name" name="customer_name_filter" value="">
                </div>
                
                
                @if($roleTitle ==1)
                <div class="col-sm-3">
                    <label for="loan_partner" class="col-form-label">Partners</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="loan_partner" name="loan_partner"  >
                        <option value=''> Select Partner</option>
                        @foreach ($partners as $partner)
                            <option value="{{$partner->id ?? ''}}">{{ $partner->name }} ( {{ $partner->email  }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-sm-3">
                    <label for="enquiry_date" class="col-form-label">Start Date - End Date</label>
                    <input type="text" class="form-control" id="enquiry_date" placeholder="Choose the Start Date" name="enquiry_date" >
               
                </div>
                
                
                
                <div class="col-sm-3">
                    <label for="status_filter" class="col-form-label"> Case Status</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="status_filter" name="status_filter"  >
                       <option value="">-- Select Status --</option>
                        <option value="Enquiry">Enquiry</option>
                        <option value="Underwritting">Underwritting</option>
                     
                    </select>
                </div>
                

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 row mb-3">
                <div class="col-sm-3">
                    <button type="button" class="btn btn-primary" id="search"><i class="fa fa-filter"></i>&nbsp; Filter</button>
                    <button type="button" class="btn btn-danger" id="reset"><i class="fa fa-undo"></i>&nbsp; Reset</button>
                </div>
            </div>
     </div>
    </div>

</div>
    
    
   

    <div class="card">
        <div class="card-header">
            Enquiry List
        </div>

        <div class="card-body">
            <table
                class=" table table-striped table-hover ajaxTable datatable datatable-caseList text-center">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Loan Type
                        </th>
                         <th>
                            Loan Amount
                        </th>
                        <th>
                            Customer Name
                        </th>

                        <th>
                            Customer Email
                        </th>
                        <th>
                           Customer phone
                        </th>
                        <th>
                            Stage
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="secondLoader"></div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        let $loading = $('.loading-overlay')
        
        $(document).ready(function() {
            
            // Function to initialize the DataTable
            function callAjax() {
                if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
                    $('.datatable-caseList').DataTable().destroy();
                }
                
                let dtOverrideGlobals = {
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.enquiry_list.index') }}",
                        data: function(d) {
                            d.loan_type_filter = $('#loan_type_filter').val();
                            d.customer_name_filter = $('#customer_name_filter').val();
                            d.status_filter = $('#status_filter').val();
                            d.loan_partner = $('#loan_partner').val();
                            d.enquiry_date = $('#enquiry_date').val();
                        },
                    },
                    columns: [
                        { data: 'placeholder', name: 'placeholder' },
                        { data: 'id', name: 'id' },
                        { data: 'loan_category_type', name: 'loan_category_type' },
                        { data: 'client_loan_amount', name: 'client_loan_amount' },
                        { data: 'client_first_name', name: 'client_first_name' },
                        { data: 'client_email', name: 'client_email' },
                        { data: 'client_phone', name: 'client_phone' },
                        { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions' }
                    ],
                    orderCellsTop: true,
                    order: [[1, 'desc']],
                    pageLength: 10,
                };
                
                let table = $('.datatable-caseList').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                });
            }
    
            // Initial call to load the DataTable
            callAjax();
    
            // Event listener for the search button click
            $('#search').on('click', function(e) {
                callAjax();
            });
            
            $('#reset').on('click', function(e) {
                $('#loan_type_filter,#customer_name_filter, #status_filter,#loan_partner,#status_filter,#enquiry_date').val('')
                callAjax();
            })
            
            
           
            
        $('#enquiry_date').daterangepicker({
            locale: {
                firstDay: 1
            },
            startDate: moment().startOf('day'),
            endDate: moment().endOf('day'),
            autoUpdateInput: false // This prevents the input from being updated automatically
        })
        .on('apply.daterangepicker', function(ev, picker) {
            // Update the input field with the selected date range
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        })
        .on('cancel.daterangepicker', function(ev, picker) {
            // Clear the input field if the user cancels the selection
            $(this).val('');
        });
            
              //Date range picker for pickup dates
        function pickup_callback(start, end) {
            $('#enquiry_date').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        }
            
            
 
            
            
        });




        function viewCase(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.application-stage.view') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {


                            // ClientModal_ResetErrors()
                            // $('#customer_id').val('');
                            // $('.submit_button').hide();
                            // $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
                            $('#customerForm').trigger("reset");
                            $('#form-modal').modal('show');


                            // var data = response.data;
                            // $("#document_type_id").val(data.id);
                            // // $("#loan_type_id").val(data.loan_type_id).prop('disabled', true).select2();
                            // $("#title").val(data.title);
                            // $("#loading_div").hide();
                            // $("#documenttypeModal").modal('show');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })

            }


            // if (id == undefined) {
            //     Swal.fire('', 'ID Not Found', 'warning');
            // } else {
            //     $('.secondLoader').show()
            //     $.ajax({
            //         url: "{{ route('admin.document_type.view') }}",
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             'id': id
            //         },
            //         success: function(response) {
            //             $('.secondLoader').hide()
            //             let status = response.status;
            //             if (status == true) {
            //                 var data = response.data;
            //                 $("#document_type_id").val(data.id);
            //                 // $("#loan_type_id").val(data.loan_type_id).prop('disabled', true).select2();
            //                 $("#title").val(data.title);
            //                 $("#loading_div").hide();
            //                 $("#documenttypeModal").modal('show');
            //             } else {
            //                 Swal.fire('', response.data, 'error');
            //             }
            //         },
            //         error: function(jqXHR, textStatus, errorThrown) {
            //             if (jqXHR.status) {
            //                 if (jqXHR.status == 500) {
            //                     Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
            //                 } else {
            //                     Swal.fire('', jqXHR.status, 'error');
            //                 }
            //             } else if (textStatus) {
            //                 Swal.fire('', textStatus, 'error');
            //             } else {
            //                 Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
            //                     "error");
            //             }
            //         }
            //     })
            // }
        }

        function editCase(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.document_type.edit') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {
                            var data = response.data;

                            $("#document_type_id").val(data.id);
                            $("#title").val(data.title);
                            // $("#loan_type_id").val(data.loan_type_id).prop('disabled', false).select2();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#loading_div").hide();
                            $("#documenttypeModal").modal('show');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }
        
          //Search Datatable
        
        //Reset Filter
        
        

        // function deleteCase(id) {
        //     if (id == undefined) {
        //         Swal.fire('', 'ID Not Found', 'warning');
        //     } else {
        //         Swal.fire({
        //             title: "Are You Sure?",
        //             text: "Do You Really Want To Delete !",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Yes",
        //             cancelButtonText: "No",
        //             reverseButtons: true
        //         }).then(function(frameby) {
        //             if (frameby.value) {
        //                 $('.secondLoader').show()
        //                 $.ajax({
        //                     url: "{{ route('admin.document_type.delete') }}",
        //                     method: 'POST',
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     data: {
        //                         'id': id
        //                     },
        //                     success: function(response) {
        //                         $('.secondLoader').hide()
        //                         Swal.fire('', response.data, response.status);
        //                         callAjax();
        //                     },
        //                     error: function(jqXHR, textStatus, errorThrown) {
        //                         if (jqXHR.status) {
        //                             if (jqXHR.status == 500) {
        //                                 Swal.fire('', 'Request Timeout / Internal Server Error',
        //                                     'error');
        //                             } else {
        //                                 Swal.fire('', jqXHR.status, 'error');
        //                             }
        //                         } else if (textStatus) {
        //                             Swal.fire('', textStatus, 'error');
        //                         } else {
        //                             Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                                 "error");
        //                         }
        //                     }
        //                 })
        //             }
        //         })
        //     }
        // }
    </script>
@endsection
