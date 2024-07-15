@extends('layouts.admin')

@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>

    <div class="card">
        <div class="card-header">
            Seller Enquire List
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SellerEnquire text-center">
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
                            Brand
                        </th>
                        <th>
                            Model
                        </th>

                        <th>
                            Created Date
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


    <div class="modal" tabindex="-1" id="SellerEnquireModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">name</h5>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="first_name" class="required">First Name</label>
                            <input type="hidden" name="seller_enquire_id" id="seller_enquire_id" value="">
                            <input type="text" class="form-control" id="first_name" name="first_name" value=""
                                readonly>
                            <span id="first_name_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="last_name" class="required">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value=""
                                readonly>
                            <span id="last_name_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="email" class="required">email</label>
                            <input type="email" class="form-control" id="email" name="email" value=""
                                readonly>
                            <span id="email_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="phone" class="required">phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value=""
                                readonly>
                            <span id="phone_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="post_code" class="required">Post Code</label>
                            <input type="text" class="form-control" id="post_code" name="post_code" value=""
                                readonly>
                            <span id="post_code_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="model" class="required">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value=""
                                readonly>
                            <span id="model_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="brand" class="required">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" value=""
                                readonly>
                            <span id="brand_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="registration_no" class="required">Register NO</label>
                            <input type="text" class="form-control" id="registration_no" name="registration_no"
                                value="" readonly>
                            <span id="registration_no_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="year" class="required">Vehicle Year</label>
                            <input type="text" class="form-control" id="year" name="year" value=""
                                readonly>
                            <span id="year_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="millage" class="required">Vehicle Millage</label>
                            <input type="text" class="form-control" id="millage" name="millage" value=""
                                readonly>
                            <span id="millage_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="colour" class="required">Vehicle Colour</label>
                            <input type="text" class="form-control" id="colour" name="colour" value=""
                                readonly>
                            <span id="colour_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="fuel_type" class="required">Vehicle Fuel Type</label>
                            <input type="text" class="form-control" id="fuel_type" name="fuel_type" value=""
                                readonly>
                            <span id="fuel_type_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="transmission" class="required">Vehicle Transmission</label>
                            <input type="text" class="form-control" id="transmission" name="transmission"
                                value="" readonly>
                            <span id="transmission_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="service_history" class="required">Vehicle Service History</label>
                            <input type="text" class="form-control" id="service_history" name="service_history"
                                value="" readonly>
                            <span id="service_history_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="service_book_history" class="required">Vehicle Service Book History</label>
                            <input type="text" class="form-control" id="service_book_history"
                                name="service_book_history" value="" readonly>
                            <span id="service_book_history_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="last_service_date" class="required">Last Service Date</label>
                            <input type="text" class="form-control" id="last_service_date" name="last_service_date"
                                value="" readonly>
                            <span id="last_service_date_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="last_service_millage" class="required">Last Service Millage</label>
                            <input type="text" class="form-control" id="last_service_millage"
                                name="last_service_millage" value="" readonly>
                            <span id="last_service_millage_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="number_of_owner" class="required">Number Of Owner</label>
                            <input type="text" class="form-control" id="number_of_owner" name="number_of_owner"
                                value="" readonly>
                            <span id="number_of_owner_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="car_modified" class="required">Car Modified</label>
                            <input type="text" class="form-control" id="car_modified" name="car_modified"
                                value="" readonly>
                            <span id="car_modified_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="tyre_brand" class="required">Tyre Brand</label>
                            <input type="text" class="form-control" id="tyre_brand" name="tyre_brand" value=""
                                readonly>
                            <span id="tyre_brand_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="tyre_condition" class="required">Tyre Condition</label>
                            <input type="text" class="form-control" id="tyre_condition" name="tyre_condition"
                                value="" readonly>
                            <span id="tyre_condition_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="damage_report" class="required">Damage Report</label>
                            <input type="text" class="form-control" id="damage_report" name="damage_report"
                                value="" readonly>
                            <span id="damage_report_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="outstanding_finance" class="required">Outstanding Finance</label>
                            <input type="text" class="form-control" id="outstanding_finance"
                                name="outstanding_finance" value="" readonly>
                            <span id="outstanding_finance_span" class="text-danger text-center"
                                style="display:none;font-size:0.9rem;"></span>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 form-group">
                            <label for="desired_value" class="required">Desired Value</label>
                            <input type="text" class="form-control" id="desired_value" name="desired_value"
                                value="" readonly>
                            <span id="desired_value_span" class="text-danger text-center"
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
                                    url: "{{ route('admin.seller_enquire.massDestroy') }}",
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

            if ($.fn.DataTable.isDataTable('.datatable-SellerEnquire')) {
                $('.datatable-SellerEnquire').DataTable().destroy();
            }
            let dtOverrideGlobals = {
                buttons: dtButtons,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.seller_enquire.index') }}",
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
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
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
            let table = $('.datatable-SellerEnquire').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        };

        function openModal() {

            $("#name").val('')
            $("#seller_enquire_id").val('')
            $("#email").val('')
            $("#phone").val('')
            $("#message").val('')
            $("#status").val('').prop('disabled', false).select2()
            $("#loading_div").hide();
            $("#save_btn").html(`Save`);
            $("#save_div").show();
            $("#SellerEnquireModel").modal('show');
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
                let id = $("#seller_enquire_id").val();
                let status = $("#status").val();
                $.ajax({
                    url: "{{ route('admin.seller_enquire.store') }}",
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
                        $("#SellerEnquireModel").modal('hide');
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

                $("#first_name").val('')
                $("#email").val('')
                $("#phone").val('')
                $("#message").val('')
                $("#seller_enquire_id").val('')
                $("#status").val('').prop('disabled', false).select2()
                $('.secondLoader').show()
                $.ajax({
                        url: "{{ route('admin.seller_enquire.view') }}",
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

                                for (var key in data) {

                                    if (data.hasOwnProperty(key)) {

                                        if (key == 'id') {
                                            $('#seller_enquire_id').val(data[key]);
                                        } else {
                                            $('#' + key).val(data[key]);

                                        }

                                    }
                                }

                                    // $("#name").val(data.name)
                                    // $("#email").val(data.email)
                                    // $("#phone").val(data.phone)
                                    // $("#message").val(data.message)
                                    // $("#seller_enquire_id").val(data.id)
                                    $("#status").prop('disabled', true).select2()
                                    $("#save_div").hide();
                                    $("#loading_div").hide();
                                    $("#SellerEnquireModel").modal('show');
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
                    $("#seller_enquire_id").val('')
                    $("#status").val('').prop('disabled', false).select2()
                    $('.secondLoader').show()
                    $.ajax({
                            url: "{{ route('admin.seller_enquire.edit') }}",
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

                                    for (var key in data) {

                                        if (data.hasOwnProperty(key)) {

                                            if (key == 'id') {
                                                $('#seller_enquire_id').val(data[key]);
                                            } else {
                                                $('#' + key).val(data[key]);

                                            }

                                        }
                                    }
                                        $("#status").prop('disabled', false).select2()
                                        $("#save_btn").html(`Update`);
                                        $("#save_div").show();
                                        $("#loading_div").hide();
                                        $("#SellerEnquireModel").modal('show');
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
                                    url: "{{ route('admin.seller_enquire.delete') }}",
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
                                            Swal.fire('', 'Request Failed With Status: ' + jqXHR
                                                .statusText,
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
