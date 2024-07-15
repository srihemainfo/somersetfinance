@extends('layouts.admin')

@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>

    <div class="card">
        <div class="card-header">
           Buyer Enquire List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BuyerEnquire text-center">
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
                            Message
                        </th>
                        <th>
                            Status
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


        <div class="modal" tabindex="-1" id="BuyerEnquireModel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">name</h5>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="name" class="required">name</label>
                                <input type="hidden" name="buyer_enquire_id" id="buyer_enquire_id" value="">
                                <input type="text" class="form-control" id="name" name="name" value="" disable>
                                <span id="name_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="email" class="required">email</label>
                                <input type="email" class="form-control" id="email" name="email" value="" disable>
                                <span id="email_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="phone" class="required">phone</label>
                                <input type="phone" class="form-control" id="phone" name="phone" value="" disable>
                                <span id="phone_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="message" class="required">message</label>
                                <textarea type="message" class="form-control" id="message" name="message" value="" disable>
                                <span id="message_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                                <label for="model" class="required">status</label>
                                <select name="status" id="status" class='form-control select2'>
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Canceled">Canceled</option>


                                </select>
                                <span id="status_span" class="text-danger text-center"
                                    style="display:none;font-size:0.9rem;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="save_div">
                            <button type="button" id="save_btn" class="btn btn-outline-success"
                                onclick="saveBuyerEnquire()">Save</button>
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
                                        url: "{{ route('admin.buyer_enquire.massDestroy') }}",
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

                if ($.fn.DataTable.isDataTable('.datatable-BuyerEnquire')) {
                    $('.datatable-BuyerEnquire').DataTable().destroy();
                }
                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    retrieve: true,
                    aaSorting: [],
                    ajax: "{{ route('admin.buyer_enquire.index') }}",
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
                            data: 'message',
                            name: 'message'
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
                let table = $('.datatable-BuyerEnquire').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });

            };

            function openModal() {

                $("#name").val('')
                $("#buyer_enquire_id").val('')
                $("#email").val('')
                $("#phone").val('')
                $("#message").val('')
                $("#status").val('').prop('disabled', false).select2()
                $("#loading_div").hide();
                $("#save_btn").html(`Save`);
                $("#save_div").show();
                $("#BuyerEnquireModel").modal('show');
            }

            function saveBuyerEnquire() {
                $("#loading_div").hide();
                if ($("#status").val() == '') {
                    $("#status_span").html(`status Is Required.`);
                    $("#status_span").show();

                } else {
                    $("#save_div").hide();
                    $("#status_span").hide();
                    $("#loading_div").show();
                    let id = $("#buyer_enquire_id").val();
                    let status = $("#status").val();
                    $.ajax({
                        url: "{{ route('admin.buyer_enquire.store') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'status': status,
                            'id': id
                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == true) {
                                Swal.fire('', response.data, 'success');
                            } else {
                                Swal.fire('', response.data, 'error');
                            }
                            $("#BuyerEnquireModel").modal('hide');
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

            function viewBuyerEnquire(id) {
                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {

                    $("#name").val('')
                    $("#email").val('')
                    $("#phone").val('')
                    $("#message").val('')
                    $("#buyer_enquire_id").val('')
                    $("#status").val('').prop('disabled', false).select2()
                    $('.secondLoader').show()
                    $.ajax({
                        url: "{{ route('admin.buyer_enquire.view') }}",
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

                                $("#name").val(data.name)
                                $("#email").val(data.email)
                                $("#phone").val(data.phone)
                                $("#message").val(data.message)
                                $("#buyer_enquire_id").val(data.id)
                                $("#status").val(data.status).prop('disabled', false).select2()
                                $("#loading_div").hide();
                                $("#BuyerEnquireModel").modal('show');
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

            function editBuyerEnquire(id) {

                if (id == undefined) {
                    Swal.fire('', 'ID Not Found', 'warning');
                } else {

                    $("#name").val('')
                    $("#email").val('')
                    $("#phone").val('')
                    $("#message").val('')
                    $("#buyer_enquire_id").val('')
                    $("#status").val('').prop('disabled', false).select2()
                    $('.secondLoader').show()
                    $.ajax({
                        url: "{{ route('admin.buyer_enquire.edit') }}",
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

                                $("#name").val(data.name)
                                $("#email").val(data.email)
                                $("#phone").val(data.phone)
                                $("#message").val(data.message)
                                $("#buyer_enquire_id").val(data.id)
                                $("#status").val(data.status).prop('disabled', true).select2()
                                $("#save_btn").html(`Update`);
                                $("#save_div").show();
                                $("#loading_div").hide();
                                $("#BuyerEnquireModel").modal('show');
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

            function deleteBuyerEnquire(id) {
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
                                url: "{{ route('admin.buyer_enquire.delete') }}",
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
