@extends('layouts.admin')
@section('content')
  
    <style>
        .select2 {
            width: 100% !important;
        }
        .input-group {
            justify-content: end;
        }
    </style>
   

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
        $(function() {
            callAjax();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    
        });


    
        function callAjax() {
            // let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            // dtButtons.splice(2, 2);
            // dtButtons.splice(3, 3);
                // let deleteButton = {
                //     text: 'Delete Selected',
                //     className: 'btn-outline-danger',
                //     action: function(e, dt, node, config) {
                //         var ids = $.map(dt.rows({
                //             selected: true
                //         }).data(), function(entry) {
                //             return entry.id
                //         });
    
                //         if (ids.length === 0) {
                //             Swal.fire('', 'No Rows Selected', 'warning');
                //             return
                //         }

                {{-- 
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
                                    // headers: {
                                    //     'x-csrf-token': _token
                                    // },
                                    method: 'POST',
                                    url: "{{ route('admin.document_type.massDestroy') }}",
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
                    
                    --}}
                // }
            // }
            // dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
                $('.datatable-caseList').DataTable().destroy();
            }
            let dtOverrideGlobals = {
               
                // buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.enquiry_list.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'loan_category_type',
                        name: 'loan_category_type'
                    },
                    {
                        data: 'client_loan_amount',
                        name: 'client_loan_amount'
                    },

                    {
                        data: 'client_first_name',
                        name: 'client_first_name'
                    },{
                        data: 'client_email',
                        name: 'client_email'
                    },
                    {
                        data: 'client_phone',
                        name: 'client_phone'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 10,
            };
            let table = $('.datatable-caseList').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };


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
