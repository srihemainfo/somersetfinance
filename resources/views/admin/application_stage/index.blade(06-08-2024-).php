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
        // echo $roleTitle ;
@endphp
  
    <style>
        .select2 {
            width: 100% !important;
        }
        .input-group {
            justify-content: end;
        }
    </style>
    {{-- @can('document_type_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Document Type
            </button>

        </div>
    </div>
    @endcan --}}
    
      <div class="col-sm-12 main-card mb-3 card">

    <div class="card-header">
        <h4 class="card-title">Case Filter</h4>
    </div>
      <div class="card-body">
        <div class="row">
            <div class="col-sm-12 row mb-2">
              
                <div class="col-sm-3">
                    <label for="Loan_type_filter" class="col-form-label"> Loan Type</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="Loan_type_filter" name="Loan_type_filter"  >
                        @foreach ($loan_types as $id => $value)
                            <option value="{{ $id ?? ''}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                
                 <div class="col-sm-3">
                    <label for="customer_filter" class="col-form-label"> Case Customer</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="customer_filter" name="customer_filter"  >
                       <option value="">-- Select Customer --</option>
                        @foreach ($customer_details as $customer_detail)
                            <option value="{{$customer_detail-> id ?? ''}}"> {{$customer_detail-> name ?? ''}} ( {{ $customer_detail-> email }}) </option>
                        @endforeach
                     
                    </select> 
                </div>
                
                {{--
                <div class="col-sm-3">
                    <label for="customer_filter">Cutomer Name</label>
                    <input type="text" class="form-control" id="customer_filter" placeholder="Search by customer name" name="customer_filter" value="">
                </div> --}}
                
                <div class="col-sm-3">
                    <label for="partner_filter" class="col-form-label"> Case Status</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="partner_filter" name="partner_filter"  >
                        <option value="">-- Select Partner --</option>
                        @foreach ($partners as $partner)
                            <option value="{{ $partner-> id ?? ''}}"> {{$partner -> name ?? ''}} ( {{ $partner-> email }}) </option>
                        @endforeach
                     
                    </select>
                </div>
                
                <div class="col-sm-3">
                    <label for="case_date" class="col-form-label">Start Date - End Date</label>
                    <input type="text" class="form-control" id="case_date" placeholder="Choose the Start Date" name="case_date" >
               
                </div>
                
                <div class="col-sm-3">
                    <label for="status_filter" class="col-form-label"> Case Status</label>
                    <select class="form-control" class="form-control select2" style="width: 100%;"  id="status_filter" name="status_filter"  >
                       <option value="">-- Select Status --</option>
                        <option value="Enquiry">Enquiry</option>
                        <option value="Underwritting">Underwritting</option>
                        <option value="Processing">Processing</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Completed">Completed</option>
                     
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
            Case List
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
                            App No.
                        </th>
                        <th>
                            Email
                        </th>

                        <th>
                            Type
                        </th>
                        <th>
                            Assigned 
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



    <div class="modal" tabindex="-1" id="documenttypeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Type</h5>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">

                        {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="loan_type_id" class="required">Loan Type</label>

                                <select name="loan_type_id" id="loan_type_id" class='form-control select2'>
                                    @foreach ($loan_types as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach

                                </select>
                                <span id="loan_type_id_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div> --}}


                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="title" class="required">Document Type</label>
                            <input type="hidden" name="document_type_id" id="document_type_id" value="">
                            <input type="text" class="form-control" id="title" name="title" value="">
                            <span id="title_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveDocumentType()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <button type="button" class="btn btn-success" title="Add Client" id="addCustomer"><i
            class="fas fa-plus"></i></button> --}}

    @include('admin.application.partials.clientassignModel')
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
        
                @if($roleTitle == 1)
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                dtButtons.splice(2, 2);
                dtButtons.splice(3, 3);
                let deleteButton = {
                    text: 'Delete Selected',
                    className: 'btn-outline-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });
        
                        if (ids.length === 0) {
                            Swal.fire('', 'No Rows Selected', 'warning');
                            return
                        }
        
                        Swal.fire({
                            title: "Are You Sure?",
                            text: "Do You Really Want To Delete !",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            reverseButtons: true
                        }).then(function(frameby) {
                            if (frameby.value) {
                                $('.secondLoader').show()
                                $loading.show()
                                $.ajax({
                                        method: 'POST',
                                        url: "{{ route('admin.document_type.massDestroy') }}",
                                         headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function(response) {
                                        $('.secondLoader').hide()
                                        Swal.fire('', response.data, response.status);
                                        callAjax()
                                        $loading.hide()
                                    })
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
                @else
                let dtButtons = '';
                @endif
        
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.application-stage.index') }}",
                        data: function(d) {
                            d.Loan_type_filter = $('#Loan_type_filter').val();
                            d.customer_filter = $('#customer_filter').val();
                            d.partner_filter = $('#partner_filter').val();
                            d.status_filter = $('#status_filter').val();
                            d.case_date = $('#case_date').val();
                        },
                    },
                    columns: [
                        { data: 'placeholder', name: 'placeholder' },
                        { data: 'id', name: 'id' },
                        { data: 'ref_no', name: 'ref_no' },
                        { data: 'customer_id', name: 'customer_id' },
                        { data: 'loan_type_id', name: 'loan_type_id' },
                        { data: 'assigned_client_id', name: 'assigned_client_id' },
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
        
            $('#search').on('click', function(e) {
                callAjax();
            });
        
            $('#reset').on('click', function(e) {
                $('input[name=Loan_type_filter]').val('');
                $('input[name=customer_filter]').val('');
                $('input[name=status_filter]').val('');
                callAjax();
            });

        callAjax();

           

            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            $(document).on('click', '.removeClient', function() {
                var caseId = $(this).data('case-id');
                var clientId = $(this).data('client-id');

                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You  Want remove Assigned Client ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(frameby) {
                    if (frameby.value) {
                        $loading.show()
                        $.ajax({
                            method: 'POST',
                            url: "{{ route('admin.application.removeClientGet') }}",
                             headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                            data: {
                                caseId: caseId,
                                clientId: clientId,
                            },
                            success: function(response) {

                                if (response.status && response.message) {

                                    Swal.fire('', response.message, 'success');

                                } else {
                                    Swal.fire('', response.message, 'error');
                                }

                                $loading.hide()

                                callAjax();

                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $loading.hide()
                            }
                        })

                    }
                })

            });

            $(document).on('click', '.assignClient', function() {
                $('#clientAssignForm').trigger("reset");
                var caseId = $(this).data('case-id');
                var caseNo = $(this).data('ref-id');
                $('#job_id').val(caseId);
                $('#case_No').val(caseNo);
                $('#client_id').html('')

                $loading.show()

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.clientGets') }}",
                     headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    data: {
                        caseId: caseId
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.data) {


                            let driver_options = '<option value="">-- select Client --</option>'

                            response.data.forEach(function(item) {
                                driver_options +=
                                    `<option value="${item.id}">${item.name}</option>`
                            })

                            $('#client_id').html(driver_options)

                            $('#form-modal').modal('show');
                        } else {

                            Swal.fire('', 'No Client', 'error');

                        }
                     
                        $loading.hide()
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $loading.hide()
                    }
                })


            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();

                var client = $('#client_id').val();
                if (!client) {
                    Swal.fire('', ' Choose the Client', 'error');
                    return
                }
                $loading.show()
                $.ajax({
                    data: $('#clientAssignForm').serialize(),
                    url: "{{ route('admin.application.assignClient') }}",
                     headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {

                        if (response.status) {

                            if (response.message) {
                                $('#form-modal').modal('hide');
                                callAjax()
                                Swal.fire("", response.message, "success");

                            } else {

                                Swal.fire('', 'No Client', 'error');
                                callAjax()
                            }


                        } else {
                            Swal.fire('', response.message, 'error');
                        }

                        $loading.hide()


                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $loading.hide()
                    }
                });
            });
            
            
            
              $('#case_date').daterangepicker({
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


        
        //     function callAjax() {
                
                
        //     @if($roleTitle == 1)
        //     let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        //     dtButtons.splice(2, 2);
        //     dtButtons.splice(3, 3);
        //     let deleteButton = {
        //         text: 'Delete Selected',
        //         className: 'btn-outline-danger',
        //         action: function(e, dt, node, config) {
        //             var ids = $.map(dt.rows({
        //                 selected: true
        //             }).data(), function(entry) {
        //                 return entry.id
        //             });

        //             if (ids.length === 0) {
        //                 Swal.fire('', 'No Rows Selected', 'warning');
        //                 return
        //             }

        //             Swal.fire({
        //                 title: "Are You Sure?",
        //                 text: "Do You Really Want To Delete !",
        //                 icon: "warning",
        //                 showCancelButton: true,
        //                 confirmButtonText: "Yes",
        //                 cancelButtonText: "No",
        //                 reverseButtons: true
        //             }).then(function(frameby) {
        //                 if (frameby.value) {
        //                     $('.secondLoader').show()
        //                     $loading.show()
        //                     $.ajax({
        //                             // headers: {
        //                             //     'x-csrf-token': _token
        //                             // },
        //                             method: 'POST',
        //                             url: "{{ route('admin.document_type.massDestroy') }}",
        //                             data: {
        //                                 ids: ids,
        //                                 _method: 'DELETE'
        //                             }
        //                         })
        //                         .done(function(response) {
        //                             $('.secondLoader').hide()
        //                             Swal.fire('', response.data, response.status);
        //                             callAjax()
        //                             $loading.hide()
        //                         })
        //                 }
        //             })
        //         }
        //     }
        //     dtButtons.push(deleteButton)
            
        //     @else
        //     let dtButtons = '';
        //     @endif
            

        //     if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
        //         $('.datatable-caseList').DataTable().destroy();
        //     }
        //     let dtOverrideGlobals = {
               
        //         buttons: dtButtons,
        //         retrieve: true,
        //         aaSorting: [],
        //         ajax: {
        //                 url: "{{ route('admin.application-stage.index') }}",
        //                 data: function(d) {
        //                     d.Loan_type_filter = $('#Loan_type_filter').val();
        //                     d.customer_filter = $('#customer_filter').val();
        //                     d.status_filter = $('#status_filter').val();
        //                 },
        //             },
        //         columns: [{
        //                 data: 'placeholder',
        //                 name: 'placeholder'
        //             },
        //             {
        //                 data: 'id',
        //                 name: 'id'
        //             },
        //             {
        //                 data: 'ref_no',
        //                 name: 'ref_no'
        //             },
        //             {
        //                 data: 'customer_id',
        //                 name: 'customer_id'
        //             },

        //             {
        //                 data: 'loan_type_id',
        //                 name: 'loan_type_id'
        //             },{
        //                 data: 'assigned_client_id',
        //                 name: 'assigned_client_id'
        //             },
        //             {
        //                 data: 'status',
        //                 name: 'status'
        //             },
        //             {
        //                 data: 'actions',
        //                 name: 'actions'
        //             }
        //         ],
        //         orderCellsTop: true,
        //         order: [
        //             [1, 'desc']
        //         ],
        //         pageLength: 10,
        //     };
        //     let table = $('.datatable-caseList').DataTable(dtOverrideGlobals);
        //     $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
        //         $($.fn.dataTable.tables(true)).DataTable()
        //             .columns.adjust();
        //     });

        // };
        
            // Event listener for the search button click
                // $('#search').on('click', function(e) {
                //     callAjax();
                // });
                
                // $('#reset').on('click', function(e) {
                //     $('input[name=Loan_type_filter]').val('')
                //     $('input[name=customer_filter]').val('');
                //     $('input[name=status_filter]').val('');
                //     callAjax();
                // })
                
                //  callAjax();

      


        $('#search_clients').select2({
            ajax: {
                url: "{{ route('admin.GetClients') }}",
                 headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                type: "post",
                dataType: 'json',
                delay: 400,
                data: function(params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        })


        function openModal() {

            $("#title").val('')
            $("#document_type_id").val('')
            // $("#loan_type_id").val('').prop('disabled', false).select2()
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#documenttypeModal").modal('show');
        }

        function saveDocumentType() {
            // $("#loan_type_id_span").html('');
            $("#title_span").html('');

            $("#loading_div").hide();
            // if ($("#loan_type_id").val() == '') {
            //     $("#loan_type_id_span").html(`Loan Type is Required.`);
            //     $("#loan_type_id_span").show();

            // } else
            if ($("#title").val() == '') {
                $("#title_span").html(`Document is Required.`);
                $("#title_span").show();
            } else {
                $("#save_div").hide();
                // $("#loan_type_id_span").hide();
                $("#title_span").hide();
                $("#loading_div").show();
                let id = $("#document_type_id").val();
                let title = $("#title").val();
                // let loan_type_id = $("#loan_type_id").val();
                $.ajax({
                    url: "{{ route('admin.document_type.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'title': title,
                        // 'loan_type_id': loan_type_id,
                        'id': id
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#documenttypeModal").modal('hide');
                        callAjax();
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

                            $('#customerForm').trigger("reset");
                            $('#form-modal').modal('show');

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

        function deleteCase(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You Really Want To Delete !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(frameby) {
                    if (frameby.value) {
                        $('.secondLoader').show()
                        $.ajax({
                            url: "{{ route('admin.document_type.delete') }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('.secondLoader').hide()
                                Swal.fire('', response.data, response.status);
                                callAjax();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                if (jqXHR.status) {
                                    if (jqXHR.status == 500) {
                                        Swal.fire('', 'Request Timeout / Internal Server Error',
                                            'error');
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
                })
            }
        }
        
      });
      
       function uploaddocument(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.application-stage.uploadcheck') }}",
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

                            $url = response.url;

                            if ($url != '') {
                                window.location.href = $url;

                            } else {

                                Swal.fire('', 'No any upload option', 'error');
                            }

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
    </script>
@endsection
