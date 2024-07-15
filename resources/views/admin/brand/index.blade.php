@extends('layouts.admin')

@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-outline-success" onclick="openModal()">
                Add Document
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
        Document List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ToolssyllabusYear text-center">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Document
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
                        <h5 class="modal-title">Brand</h5>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="brand" class="required">brand</label>
                                <input type="hidden" name="brand_id" id="brand_id" value="">
                                <input type="text" class="form-control" id="brand" name="brand" value="">
                                <span id="brand_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveRegulation()">Save</button>
                        </div>
                        <div id="loading_div">
                            <span class="theLoader"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="regulationModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" style="outline: none;" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="brand" class="required">brand</label>
                                <input type="hidden" name="brand_id" id="brand_id" value="">
                                <input type="text" class="form-control" style="text-transform:uppercase" id="brand"
                                    name="brand" value="">
                                <span id="brand_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveRegulation()">Save</button>
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
                                        url: "{{ route('admin.brand.massDestroy') }}",
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

                if ($.fn.DataTable.isDataTable('.datatable-ToolssyllabusYear')) {
                    $('.datatable-ToolssyllabusYear').DataTable().destroy();
                }
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: "{{ route('admin.brand.index') }}",
                    columns: [{
                            data: 'placeholder',
                            name: 'placeholder'
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'brand',
                            name: 'brand'
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
                let table = $('.datatable-ToolssyllabusYear').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });

            };

            function openModal() {

                $("#brand").val('')
                $("#loading_div").hide();
                $("#save_btn").html(`Save`);
                $("#save_div").show();
                $("#regulationModal").modal('show');
            }

            function saveRegulation() {
                $("#loading_div").hide();
                if ($("#brand").val() == '') {
                    $("#brand_span").html(`Brand Is Required.`);
                    $("#brand_span").show();

                } else {
                    $("#save_div").hide();
                    $("#brand_span").hide();
                    $("#loading_div").show();
                    let id = $("#brand_id").val();
                    let brand = $("#brand").val();
                    $.ajax({
                        url: "{{ route('admin.brand.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'brand': brand,
                            'id': id
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
            }

            function viewRegulation(id) {
                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {
                    $('.secondLoader').show()
                    $.ajax({
                        url: "{{ route('admin.brand.view') }}",
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
                                $("#brand_id").val(brand.id);
                                $("#brand").val(data.brand);
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

            function editRegulation(id) {

                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {
                    $('.secondLoader').show()
                    $.ajax({
                        url: "{{ route('admin.brand.edit') }}",
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

                                $("#brand_id").val(data.id);
                                $("#brand").val(data.brand);
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

            function deleteRegulation(id) {
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
                                url: "{{ route('admin.brand.delete') }}",
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
