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
    {{-- @can('document_type_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Document Type
            </button>

        </div>
    </div>
    @endcan --}}

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
        $(function() {
            callAjax();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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



            //  $('body').on('click', '.assignClient', function() {
            //     AssignModal_ResetErrors()
            //     $('#clientAssignForm').trigger("reset");
            //     $("#driver_name").val('').trigger('change');
            //     $('#driver_name').empty()


            //     //This AJAX request get drivers based on their cars order - Only show greater or equal order value than selected car for ride.
            //     $.ajax({
            //         data: {
            //             car_type: car_type
            //         },
            //         url: "{{ route('admin.application.clientGets') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         success: function(response) {
            //             let driver_options = '<option value="">-- select driver --</option>'

            //             response.forEach(function(item) {
            //                 driver_options +=
            //                     `<option value="${item.id}">${item.name}</option>`
            //             })

            //             $('#driver_name').html(driver_options)
            //         },
            //         error: function(data) {
            //             console.log('Error:', data);
            //         }
            //     })

            //     $('#booking_id').val($(this).data("id"));
            //     $('#job_no').val($(this).data("job"));
            //     $('#total').val($(this).data("amount"));
            //     $('#charges').val($(this).data("charges"));

            //     $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Assign");
            //     $('#form-modal').modal('show');
            // });





            function otherFunction(caseId, clientId) {
                // Example function to process the caseId and clientId values
                alert('Clicked case ID: ' + caseId + ', Client ID: ' + clientId);
                // Perform further actions with the caseId and clientId values
            }


            // $('#addCustomer').click(function() {
            //     // ClientModal_ResetErrors()
            //     $('#customer_id').val('');
            //     $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
            //     $('#customerForm').trigger("reset");
            //     $('#form-modal').modal('show');
            // });


        });


        $('#search_clients').select2({
            ajax: {
                url: "{{ route('admin.GetClients') }}",
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



        function callAjax() {
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
                }
            }
            dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
                $('.datatable-caseList').DataTable().destroy();
            }
            let dtOverrideGlobals = {
               
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.application-stage.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ref_no',
                        name: 'ref_no'
                    },
                    {
                        data: 'customer_id',
                        name: 'customer_id'
                    },

                    {
                        data: 'loan_type_id',
                        name: 'loan_type_id'
                    },{
                        data: 'assigned_client_id',
                        name: 'assigned_client_id'
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

        // function viewCase1(){

        //     $('#form-modal').modal('show') ;

        // }

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




                            // ClientModal_ResetErrors()
                            // $('#customer_id').val('');
                            // $('.submit_button').hide();
                            // $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
                            // $('#customerForm').trigger("reset");
                            // $('#form-modal').modal('show');


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
    </script>
@endsection
