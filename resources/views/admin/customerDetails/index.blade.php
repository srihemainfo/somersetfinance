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
                Add Customer
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
        Customer List
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
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Phone
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
                        <h5 class="modal-title">Customer Detail</h5>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="name" class="required">Name</label>
                                <input type="hidden" name="name_id" id="name_id" value="">
                                <input type="text" class="form-control" id="name" name="name" value="">
                                <span id="name_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="email" class="required">Email</label>
                                <input type="hidden" name="email_id" id="email_id" value="">
                                <input type="email" class="form-control" id="email" name="email" value="">
                                <span id="email_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="phone" class="required">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="" oninput="validateInput(this)">
                                <span id="phone_span" class="text-danger text-center"
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
                                <input type="hidden" name="email_id" id="email_id" value="">
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
                                        url: "{{ route('admin.customerusers.massDestroy') }}",
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
                    ajax: "{{ route('admin.customerusers.index') }}",
                    columns: [{
                            data: 'placeholder',
                            name: 'placeholder'
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
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

                $("#name").val('')
                $("#email").val('')
                $("#phone").val('')
                $("#email_id").val('')
                $("#loading_div").hide();
                $("#save_btn").html(`Save`);
                $("#save_div").show();
                $("#regulationModal").modal('show');
            }

            function saveRegulation() {
                $("#loading_div").hide();
                if ($("#name").val() == '') {
                    $("#name_span").html(`Name is Required.`);
                    $("#name_span").show();

                }else  if ($("#email").val() == '') {
                    $("#email_span").html(`Email is Required.`);
                    $("#email_span").show();

                }else  if ($("#phone").val() == '') {
                    $("#phone_span").html(`Phone is Required.`);
                    $("#phone_span").show();

                }
                else {

                    $("#name_span").html('').hide();
                    $("#email_span").html('').hide();
                    $("#phone_span").html('').hide();
                    
                    $("#save_div").hide();
                    // $("#brand_span").hide();
                    $("#loading_div").show();
                    let id = $("#email_id").val();
                    let name = $("#name").val();
                    let phone = $("#phone").val();
                    let email = $("#email").val();
                    $.ajax({
                        url: "{{ route('admin.customerusers.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'name': name,
                            'email': email,
                            'phone': phone,
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
                        url: "{{ route('admin.customerusers.view') }}",
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
                                $("#email_id").val(data.id);
                                $("#name").val(data.name);
                                $("#email").val(data.email);
                                $("#phone").val(data.phone);
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
                        url: "{{ route('admin.customerusers.edit') }}",
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
                                $("#email_id").val(data.id);
                                $("#name").val(data.name);
                                $("#email").val(data.email);
                                $("#phone").val(data.phone);
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
                                url: "{{ route('admin.customerusers.delete') }}",
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

            function validateInput(input) {
            input.value = input.value
                .replace(/[^0-9.]/g, '')  // Remove any non-numeric and non-period characters
                .replace(/(\..*)\./g, '$1');  // Allow only one period
        }


        </script>
    @endsection
