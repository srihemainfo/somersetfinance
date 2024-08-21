@extends('layouts.admin')



@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    @can('document_type_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Document Type
            </button>

        </div>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            Document Type List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DocumentType text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        {{-- <th>
                            Loan Type
                        </th> --}}
                         <th>
                            Title
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

        {{-- <div class="modal fade" id="documenttypeModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" style="outline: none;" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="title" class="required">title</label>
                                <input type="hidden" name="document_type_id" id="document_type_id" value="">
                                <input type="text" class="form-control" style="text-transform:uppercase" id="title"
                                    name="title" value="">
                                <span id="loan_type_id_span" class="text-danger text-center"
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
        </div> --}}


@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            callAjax();
        });

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
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
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
                                })
                        }
                    })
                }
            }
            dtButtons.push(deleteButton)

            if ($.fn.DataTable.isDataTable('.datatable-DocumentType')) {
                $('.datatable-DocumentType').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.document_type.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    // {
                    //     data: 'loan_type_id',
                    //     name: 'loan_type_id'
                    // },
                    {
                        data: 'title',
                        name: 'title'
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
            let table = $('.datatable-DocumentType').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

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

        function viewDocumentType(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.document_type.view') }}",
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
                            // $("#loan_type_id").val(data.loan_type_id).prop('disabled', true).select2();
                            $("#title").val(data.title);
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

        function editDocumentType(id) {

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

        function deleteDocumentType(id) {
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
