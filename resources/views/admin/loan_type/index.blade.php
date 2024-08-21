@extends('layouts.admin')

@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    @can('loan_type_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <button class="btn btn-outline-success" onclick="openModal()">
                    Add Loan type
                </button>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            Loan type List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-LoanType text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Document
                        </th>
                        <th>
                            Form Upload
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

    <div class="modal" tabindex="-1" id="regulationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Type</h5>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="title" class="required">Loan Type</label>
                            <input type="hidden" name="title_id" id="title_id" value="">
                            <input type="text" class="form-control" id="title" name="title" value="">
                            <span id="title_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="document_id" class="required">Documents</label>

                            <select name="document_id[]" id="document_id" class='form-control select2' multiple>
                                @foreach ($document_types as $key => $value)
                                  
                                <option value="{{ $key }}">{{ $value }}</option>
                              
              
                                  
                                @endforeach

                            </select>
                            <span id="document_id_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="form_upload_id" class="required">Form Upload</label>

                            <select name="form_upload_id[]" id="form_upload_id" class='form-control select2' multiple>
                                @foreach ($formuploades as $key => $value)
                                
                                  <option value="{{ $key  }}">{{ $value }}</option>
                                @endforeach

                            </select>
                            <span id="form_upload_id_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div id="save_div">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveLoanType()">Save</button>
                    </div>
                    <div id="loading_div">
                        <span class="theLoader"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                    url: "{{ route('admin.loanType.massDestroy') }}",
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

            if ($.fn.DataTable.isDataTable('.datatable-LoanType')) {
                $('.datatable-LoanType').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.loanType.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'documents',
                        render: function(data) {
                            var documentHtml = '';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(function(document) {
                                    documentHtml += '<span class="badge badge-info">' + document +
                                        '</span> ';
                                });
                            }
                            return documentHtml.trim(); // Remove trailing space
                        }
                    },
                    {
                        data: 'formuploads',
                        render: function(data) {
                            var formuploadsHtml = '';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(function(formuploads) {
                                    formuploadsHtml += '<span class="badge badge-info">' + formuploads +
                                        '</span> ';
                                });
                            }
                            return formuploadsHtml.trim(); // Remove trailing space
                        }
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
            let table = $('.datatable-LoanType').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#title").val('')
            $("#title_id").val('')
            $("#document_id").val('').prop('disabled', false).select2()
            $("#form_upload_id").val('').prop('disabled', false).select2()
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#regulationModal").modal('show');
        }

        function saveLoanType() {
            $("#loading_div").hide();

            let fields = [{
                    id: "#title",
                    errorId: "#title_span",
                    errorMessage: "Loan Type Is Required."
                },
                {
                    id: "#document_id",
                    errorId: "#document_id_span",
                    errorMessage: "Document is Required."
                },
                {
                    id: "#form_upload_id",
                    errorId: "#form_upload_id_span",
                    errorMessage: "Form Upload is Required."
                }
            ];

            let isValid = true;

            fields.forEach(field => {
                if ($(field.id).val() == '') {
                    $(field.errorId).html(field.errorMessage).show();
                    isValid = false;
                } else {
                    $(field.errorId).hide();
                }
            });

            if (!isValid) return;

            $("#save_div").hide();
            $("#loading_div").show();

            $.ajax({
                url: "{{ route('admin.loanType.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'title': $("#title").val(),
                    'id': $("#title_id").val(),
                    document_id: $("#document_id").val(),
                    form_upload_id: $("#form_upload_id").val()
                },
                success: function(response) {
                    let alertType = response.status ? 'success' : 'error';
                    Swal.fire('', response.data, alertType);
                    $("#regulationModal").modal('hide');
                    callAjax();
                },
                error: function(jqXHR, textStatus) {
                    let errorMsg = jqXHR.status ?
                        (jqXHR.status == 500 ? 'Request Timeout / Internal Server Error' : jqXHR.status) :
                        (textStatus ? textStatus : 'Request Failed With Status: ' + jqXHR.statusText);
                    Swal.fire('', errorMsg, 'error');
                }
            });
        }

        {{--
            function saveLoanType() {
                $("#loading_div").hide();
                if ($("#title").val() == '') {
                    $("#title_span").html(`Loan Type Is Required.`);
                    $("#title_span").show();

                }else
                if ($("#document_id").val() == '') {
                    $("#document_id_span").html(`Document is Required.`);
                    $("#document_id_span").show();

                }
                else
                if ($("#form_upload_id").val() == '') {
                    $("#form_upload_id_span").html(`Form Upload is Required.`);
                    $("#form_upload_id_span").show();

                }
                 else {
                    $("#save_div").hide();
                    $("#title_span").hide();
                    $("#loading_div").show();
                    let id = $("#title_id").val();
                    let title = $("#title").val();
                    let document_id = $("#document_id").val();
                    let form_upload_id = $("#form_upload_id").val();
                    $.ajax({
                        url: "{{ route('admin.loanType.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'title': title,
                            'id': id,
                            document_id: document_id,
                            form_upload_id:form_upload_id

                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            $("#regulationModal").modal('hide');
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
            } --}}

        function viewLoanType(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.loanType.view') }}",
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
                            var data = response.data.data;
                            var documents_data = response.data.documents;
                            var formuploads = response.data.formuploads;
                            $("#title_id").val(data.id);
                            $("#title").val(data.title);
                            $("#document_id").val(documents_data).trigger('change');
                            $("#form_upload_id").val(formuploads).trigger('change');
                            $("#loading_div").hide();
                            $("#regulationModal").modal('show');
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

        function editLoanType(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.loanType.edit') }}",
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
                            var data = response.data.data;
                            var documents_data = response.data.documents;
                            var formuploads = response.data.formuploads;
                            $("#title_id").val(data.id);
                            $("#title").val(data.title);
                            $("#document_id").val(documents_data).trigger('change');
                            $("#form_upload_id").val(formuploads).trigger('change');
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#loading_div").hide();
                            $("#regulationModal").modal('show');
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

        function deleteLoanType(id) {
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
                            url: "{{ route('admin.loanType.delete') }}",
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
