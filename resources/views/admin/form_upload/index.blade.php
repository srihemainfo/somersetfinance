@extends('layouts.admin')



@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
    @can('form_upload_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Form Upload
            </button>

        </div>
    </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Document Form Upload List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-formUpload text-center">
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
                            Image
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



        <div class="modal" tabindex="-1" id="formUploadModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Document Type</h5>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">

                            <form id="uploadForm" enctype="multipart/form-data">

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
                                <label for="title" class="required">Form Upload</label>
                                <input type="hidden" name="form_upload_id" id="form_upload_id" value="">
                                <input type="text" class="form-control" id="title" name="title" value="">
                                <span id="title_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="file_name" class="required">Form image</label>
                                <input type="file" class="form-control" id="file_name" name="file_name" value="" accept="image/*,application/pdf">
                                <span id="file_name_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                        </form>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveFormUpload()">Save</button>
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
                                    url: "{{ route('admin.form_upload.massDestroy') }}",
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

            if ($.fn.DataTable.isDataTable('.datatable-formUpload')) {
                $('.datatable-formUpload').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.form_upload.index') }}",
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
                        data: 'file_name',
                        name: 'file_name'
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
            let table = $('.datatable-formUpload').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#title").val('')
            $("#form_upload_id").val('')
            $("#file_name").val('')
            // $("#loan_type_id").val('').prop('disabled', false).select2()
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#formUploadModal").modal('show');
        }

        function saveFormUpload() {
            // $("#loan_type_id_span").html('');
            $("#title_span").html('');
            $("#fiel_name_span").html('');


            // let image = $('#file_name')[0].files[0];
            // if (!image) {
            //     Swal.fire('', 'Please select an image to upload.', 'error');
            //     return;
            // }

            $("#loading_div").hide();
            // if ($("#loan_type_id").val() == '') {
            //     $("#loan_type_id_span").html(`Loan Type is Required.`);
            //     $("#loan_type_id_span").show();

            // } else
            if ($("#title").val() == '') {
                $("#title_span").html(`Document is Required.`);
                $("#title_span").show();
            }

            else
            if (!$('#file_name')[0].files[0] && $('#form_upload_id').val() == '' ) {

                $("#fiel_name_span").html(`Form image is Required.`);
                $("#fiel_name_span").show();
            }

            else {
                $("#save_div").hide();
                $("#loan_type_id_span").hide();
                $("#title_span").hide();
                $("#loading_div").show();
                // let id = $("#form_upload_id").val();
                // let title = $("#title").val();
                // let loan_type_id = $("#loan_type_id").val();


                let title = $('#title').val();
                // let loan_type_id = $('#loan_type_id').val();
                let id = $('#form_upload_id').val();
                let image = $('#file_name')[0].files[0];

                let formData = new FormData();
                formData.append('title', title);
                // formData.append('loan_type_id', loan_type_id);
                formData.append('id', id);
                formData.append('file_name', image);


                $.ajax({
                    url: "{{ route('admin.form_upload.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#formUploadModal").modal('hide');
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

        function viewformUpload(id) {
            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.form_upload.view') }}",
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
                            $("#form_upload_id").val(data.id);
                            // $("#loan_type_id").val(data.loan_type_id).prop('disabled', true).select2();
                            $("#title").val(data.title);
                            $("#loading_div").hide();
                            $("#formUploadModal").modal('show');
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

        function editFormUpload(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.form_upload.edit') }}",
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

                            $("#form_upload_id").val(data.id);
                            $("#title").val(data.title);
                            // $("#loan_type_id").val(data.loan_type_id).prop('disabled', false).select2();
                            $("#save_btn").html(`Update`);
                            $("#save_div").show();
                            $("#loading_div").hide();
                            $("#formUploadModal").modal('show');
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

        function deleteFormUpload(id) {
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
                            url: "{{ route('admin.form_upload.delete') }}",
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
