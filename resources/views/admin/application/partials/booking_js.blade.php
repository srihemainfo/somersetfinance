<script>
    var $loading = $('.loading-overlay');
    $(function() {
        //Allowed places for autofill address
        // const auto_fill_places = ['Airports', 'Seaports', 'Hotels', 'Southampton Hotels',
        //     'Heathrow Airport Hotels', 'Train stations'
        // ]

        // $('#one_way_pickup_date, #return_pickup_date, #one_way_flight_date, #return_flight_date').datepicker({
        //     format: "dd-mm-yyyy",

        //     autoclose: true,
        //     todayHighlight: true,
        //     startDate: new Date(), // Set minimum date to today (disable past dates)
        //     weekStart: 1
        // });

        // Set default date for all datepickers to today
        // $('#one_way_pickup_date, #return_pickup_date, #one_way_flight_date, #return_flight_date').datepicker("setDate", new Date());

        //     $('#one_way_pickup_date, #return_pickup_date, #one_way_flight_date, #return_flight_date').datepicker({
        //         format: "dd-mm-yyyy",
        //         weekStart: 1
        //     }).datepicker("setDate", "0")

        //Hide return container on pageload
        // ReturnContainerVisibility(false)

        //Hide flight details container on pageload
        // ArrivalDetailsContainer('', '')
        // DepartureDetailsContainer('', '')

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Select2 AJAX search for clients
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

        $('#search_clients').change(function() {
            let client_id = $('#search_clients').val()

            $.ajax({
                type: "POST",
                url: "{{ route('admin.GetClientInfo') }}",
                data: {
                    id: client_id
                },
                beforeSend: function() {
                    $loading.show();

                },
                success: function(response) {
                    //
                    ShowClientInfo(response[0])
                    $loading.hide();
                },
                error: function(data) {

                }
            });
        })


           // Ajax for Save New Client
        $('#saveBtn').click(function(e) {
            e.preventDefault();

            $.ajax({
                data: $('#customerForm').serialize(),
                url: "{{ route('admin.customerStore') }}",
                type: "POST",
                dataType: 'json',
                beforSend: function() {
                    $loading.show()
                },
                success: function(response) {

                    ClientModal_ResetErrors()

                    if (response.status == 400 && response.errors) {

                        ClientModal_ShowErrors(response.errors)
                    }

                    if (response.status == 400 && !response.errors) {
                        Swal.fire("Error", "Add or Update failed", "error");
                    }

                    if (response.status == 200) {
                        $('#customerForm').trigger("reset");
                        $('#form-modal').modal('hide');

                        if (response.data.created_at === response.data.updated_at) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Added',
                                text: 'New Customer added successfully',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Updated',
                                text: 'Customer updated successfully',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                        }
                    }

                    $loading.hide()
                },
                error: function(data) {
                    $loading.hide()

                }
            });
        });

        function ClientModal_ShowErrors(errors) {
        if (errors.name) {
            $('.invalid-name').text(errors.name);
        }
        if (errors.phone) {
            $('.invalid-phone-no').text(errors.phone);
        }
        if (errors.email) {
            $('.invalid-email').text(errors.email);
        }
    }

    function ClientModal_ResetErrors() {
        $('.invalid-name, .invalid-phone-no, .invalid-email').text('');
    }

    <?php /*


        // $('#customer_sms_send_btn').click(function() {

        //     let customer_number = $('#customer_no').val();
        //     let customer_message = $('#customer_message').val();

        //     let encoded_message = encodeURIComponent(customer_message.trim());
        //     var linkHref =
        //         `https://web.whatsapp.com/send?phone=${customer_number}&text=${encoded_message}`;

        //     // Create <a> tag dynamically
        //     var $link = $('<a>', {
        //         href: linkHref,
        //         target: '_blank'
        //     });

        //     // Append the <a> tag to the DOM (optional, for visibility)
        //     $('#tag_click').append($link);
        //     // Simulate click on the <a> tag
        //     $link.get(0).click();

        //     // Clear the content inside the #tag_click element
        //     $('#tag_click').html('');

        // });


        // $('#driver_sms_send_btn').click(function() {


        //     let driver_number = $('#driver_no').val()
        //     let driver_message = $('#driver_message').val()
        //     let encoded_message = encodeURIComponent(driver_message.trim());
        //     var linkHref =
        //         `https://web.whatsapp.com/send?phone=${driver_number}&text=${encoded_message}`;

        //     // Create <a> tag dynamically
        //     var $link = $('<a>', {
        //         href: linkHref,
        //         target: '_blank'
        //     });

        //     // Append the <a> tag to the DOM (optional, for visibility)
        //     $('#tag_click').append($link);
        //     // Simulate click on the <a> tag
        //     $link.get(0).click();

        //     // Clear the content inside the #tag_click element
        //     $('#tag_click').html('');

        // });

        // $('#sms_send_btn').click(function() {
        //     let customer_number = $('#customer_no').val()
        //     let driver_number = $('#driver_no').val()
        //     let customer_message = $('#customer_message').val()
        //     let driver_message = $('#driver_message').val()
        //     let sms_customer = $('#customer_sms:checked').val() ? true : false
        //     let sms_driver = $('#driver_sms:checked').val() ? true : false

        //     if (sms_customer || sms_driver) {


        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('SMSBookingDetails') }}",
        //             data: {
        //                 customer_number: customer_number,
        //                 driver_number: driver_number,
        //                 customer_message: customer_message,
        //                 driver_message: driver_message,
        //                 sms_customer: sms_customer,
        //                 sms_driver: sms_driver
        //             },
        //             beforeSend: function() {
        //                 $('#load_animation_sms').show()
        //                 $loading.show()
        //             },
        //             success: function(response) {
        //                 $('#load_animation_sms').hide()

        //                 if (response.status == 200) {
        //                     Swal.fire({
        //                         position: 'top-end',
        //                         icon: 'success',
        //                         title: 'SMS Status',
        //                         text: response.message,
        //                         showConfirmButton: false,
        //                         timer: 5000,
        //                     })
        //                 }
        //                 $loading.hide()
        //             },
        //             error: function(data) {
        //                 $('#load_animation_sms').hide()
        //                 $loading.hide()

        //             }
        //         });
        //     } else {
        //         Swal.fire({
        //             position: 'bottom-start',
        //             icon: 'warning',
        //             title: 'Recipient not selected',
        //             text: 'Please select either driver or customer.',
        //             showConfirmButton: false,
        //             timer: 3000,
        //         })
        //     }
        // })



        // //Load data for selected client
        // $('#search_clients').change(function() {
        //     let client_id = $('#search_clients').val()

        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('GetClientInfo') }}",
        //         data: {
        //             id: client_id
        //         },
        //         beforeSend: function() {
        //             $loading.show();

        //         },
        //         success: function(response) {
        //             //
        //             ShowClientInfo(response[0])
        //             $loading.hide();
        //         },
        //         error: function(data) {

        //         }
        //     });
        // })

        // //Client Modal Form Trigger
        // $('#addCustomer').click(function() {
        //     ClientModal_ResetErrors()
        //     $('#customer_id').val('');
        //     $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
        //     $('#customerForm').trigger("reset");
        //     $('#form-modal').modal('show');
        // });

        // // Ajax for Save New Client
        // $('#saveBtn').click(function(e) {
        //     e.preventDefault();

        //     $.ajax({
        //         data: $('#customerForm').serialize(),
        //         url: "{{ route('customer.store') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         beforSend: function() {
        //             $loading.show()
        //         },
        //         success: function(response) {

        //             ClientModal_ResetErrors()

        //             if (response.status == 400 && response.errors) {

        //                 ClientModal_ShowErrors(response.errors)
        //             }

        //             if (response.status == 400 && !response.errors) {
        //                 Swal.fire("Error", "Add or Update failed", "error");
        //             }

        //             if (response.status == 200) {
        //                 $('#customerForm').trigger("reset");
        //                 $('#form-modal').modal('hide');

        //                 if (response.data.created_at === response.data.updated_at) {
        //                     Swal.fire({
        //                         position: 'top-end',
        //                         icon: 'success',
        //                         title: 'Added',
        //                         text: 'New Customer added successfully',
        //                         showConfirmButton: false,
        //                         timer: 2000,
        //                     })
        //                 } else {
        //                     Swal.fire({
        //                         position: 'top-end',
        //                         icon: 'success',
        //                         title: 'Updated',
        //                         text: 'Customer updated successfully',
        //                         showConfirmButton: false,
        //                         timer: 2000,
        //                     })
        //                 }
        //             }

        //             $loading.hide()
        //         },
        //         error: function(data) {
        //             $loading.hide()

        //         }
        //     });
        // });

        // //Calculate distance, duration, coordinates and fare for one way trip
        // $('#car_type, #one_way_pick_up, #one_way_drop_off, #one_way_flight_meet_greet').change(function() {
        //     let car_type = $('#car_type').val()
        //     let from_area = $('#one_way_pick_up').val()
        //     let to_area = $('#one_way_drop_off').val()
        //     let meet_greet = $('#one_way_flight_meet_greet').val();
        //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false

        //     if (car_type && from_area && to_area) {

        //         //show pickpoint checkbox
        //         $("#pickup_points_container").show()

        //         $.ajax({
        //             data: {
        //                 car_type: car_type,
        //                 from_area: from_area,
        //                 to_area: to_area,
        //                 meet_greet: meet_greet
        //             },
        //             url: "{{ route('GetQuote') }}",
        //             type: "POST",
        //             dataType: 'json',
        //             beforeSend: function() {
        //                 $loading.show();

        //             },
        //             success: function(response) {

        //                 if (response.miles < 1) {
        //                     Swal.fire("Location Errors", "Location route Not Available");
        //                 }
        //                 AssignValues(response, 'one_way')
        //                 if (journey_type) {
        //                     ReturnTripInitialAutoCalculation(journey_type)
        //                 }

        //                 $loading.hide();
        //             },
        //             error: function(data) {
        //                 $loading.hide();
        //             }
        //         });
        //     } else {

        //     }
        // });

        // //Calculate distance, duration, coordinates and fare for return trip
        // $('#car_type, #return_pick_up, #return_drop_off,#return_flight_meet_greet').change(function() {
        //     let car_type = $('#car_type').val()
        //     let from_area = $('#return_pick_up').val()
        //     let to_area = $('#return_drop_off').val()
        //     let meet_greet = $('#return_flight_meet_greet').val()
        //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false

        //     if (car_type && from_area && to_area) {
        //         $.ajax({
        //             data: {
        //                 car_type: car_type,
        //                 from_area: from_area,
        //                 to_area: to_area,
        //                 meet_greet: meet_greet
        //             },
        //             url: "{{ route('GetQuote') }}",
        //             type: "POST",
        //             dataType: 'json',
        //             beforeSend: function() {
        //                 $loading.show();
        //             },
        //             success: function(response) {
        //                 if (response.miles < 1) {
        //                     Swal.fire("Location Errors", "Location route Not Available");
        //                 }
        //                 AssignValues(response, 'return')

        //             },
        //             error: function(data) {

        //             }
        //         });
        //     } else {

        //     }
        // });

        // // Ajax for Save and Update
        // $('#book_now').click(function(e) {
        //     e.preventDefault();

        //     const request_url =
        //         '{{ $isEditable ? route('booking.update', $booking_details->id) : route('booking.store') }}'
        //     const request_method = '{{ $isEditable ? 'PUT' : 'POST' }}'

        //     $.ajax({
        //         data: $('#bookingForm').serialize(),
        //         url: request_url,
        //         type: request_method,
        //         dataType: 'json',
        //         beforeSend: function() {
        //             $loading.show();

        //         },
        //         success: function(response) {
        //             //

        //             if (response.status == 400 && response.trip_errors) {
        //                 ShowTripErrors(response.trip_errors)
        //             }

        //             if (response.status == 400 && response.one_way_errors) {
        //                 ShowOneWayErrors(response.one_way_errors)
        //             }

        //             if (response.status == 400 && response.return_errors) {
        //                 ShowReturnErrors(response.return_errors)
        //             }

        //             if (response.trip_errors || response.one_way_errors || response
        //                 .return_errors) {
        //                 Swal.fire("Validation Errors", "Please provide appropriate data.",
        //                     "error");
        //             }

        //             if (response.status == 400 && !response.data) {
        //                 Swal.fire("Validation Errors",
        //                     "Please provide appropriate data, Booking not saved.",
        //                     "error");
        //             }

        //             if (response.status == 200) {
        //                 window.location = response.redirect_url
        //             }

        //             $loading.hide();
        //         },
        //         error: function(data) {

        //             Swal.fire("Booking Error", "Booking not saved.", "error");
        //             $loading.hide();
        //         }
        //     });
        // })

        // //Load car type details on pageload
        // CarCapacityMaker($('#car_type').val())

        // //Load car type details on change
        // $('#child_seat_count').change(function() {
        //     // ChildSeatMaker($('#child_seat_count').val())
        // })

        // //Load car type details on change
        // $('#car_type').change(function() {
        //     CarCapacityMaker($('#car_type').val())
        // })

        // //Special date extra charges check for one way pickup date
        // $('#one_way_pickup_date').change(function() {
        //     CheckSpecialDay($('#one_way_pickup_date').val(), 'one_way')
        // })

        // //Special date extra charges check for return pickup date
        // $('#return_pickup_date').change(function() {
        //     CheckSpecialDay($('#return_pickup_date').val(), 'return')
        // })

        // //One way auto address fill for pickup
        // $('#one_way_pick_up').change(function() {
        //     let place = $('#one_way_pick_up').select2('data')[0].place_type ?
        //         $('#one_way_pick_up').select2('data')[0].place_type : ''

        //     //

        //     let place_id = $('#one_way_pick_up').select2('data')[0].place_id ?
        //         $('#one_way_pick_up').select2('data')[0].place_id : ''

        //     let area = $('#one_way_pick_up').val()

        //     let address = $('#one_way_pick_up').select2('data')[0].area_address ?
        //         $('#one_way_pick_up').select2('data')[0].area_address : area

        //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false


        //     if (area && auto_fill_places.includes(place)) {
        //         $('#one_way_pickup_address').val(address)
        //         $('#from_place_id').val(place_id)
        //     } else {
        //         $('#one_way_pickup_address').val('')
        //         $('#from_place_id').val(place_id)
        //     }

        //     //Flight details container visibility for one way
        //     ArrivalDetailsContainer(place, 'one_way')

        //     if (journey_type) {
        //         AutoSelectReturnPickup()
        //         AutoSelectReturnDrop()
        //         AutoViapointTrip();
        //     }
        // })

        // //One way auto address fill for dropoff
        // $('#one_way_drop_off').change(function() {

        //     let place = $('#one_way_drop_off').select2('data')[0].place_type ?
        //         $('#one_way_drop_off').select2('data')[0].place_type : ''

        //     //
        //     //

        //     let place_id = $('#one_way_drop_off').select2('data')[0].place_id ?
        //         $('#one_way_drop_off').select2('data')[0].place_id : ''

        //     let area = $('#one_way_drop_off').val()
        //     //

        //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false

        //     let address = $('#one_way_drop_off').select2('data')[0].area_address ?
        //         $('#one_way_drop_off').select2('data')[0].area_address : area

        //     $('#one_way_drop_off_place_type').val(place)

        //     if (area && auto_fill_places.includes(place)) {
        //         $('#one_way_dropoff_address').val(address)
        //         $('#to_place_id').val(place_id)
        //     } else {
        //         $('#one_way_dropoff_address').val('')
        //         $('#to_place_id').val(place_id)
        //     }

        //     //Flight details container visibility for one way
        //     DepartureDetailsContainer(place, 'one_way')

        //     if (journey_type) {
        //         AutoSelectReturnPickup()
        //         AutoSelectReturnDrop()
        //         AutoViapointTrip();
        //         //Flight details container visibility for return
        //         ArrivalDetailsContainer(place, 'return')
        //     }
        // })

        // $("#pickup_points").click(function() {
        //     if ($(this).is(":checked")) {
        //         $("#pickup_point_container").show();
        //     } else {
        //         $("#pickup_point_container").hide();
        //     }
        // })

        // $("#return_pickup_points").click(function() {
        //     if ($(this).is(":checked")) {
        //         $("#return_pickup_point_container").show();
        //     } else {
        //         $("#return_pickup_point_container").hide();
        //     }
        // })



        // $('.pick_up_point_select, .return_pick_up_point_select').select2({
        //     ajax: {
        //         url: "{{ route('GetLocations') }}",
        //         type: "post",
        //         dataType: 'json',
        //         delay: 400,
        //         data: function(params) {
        //             return {
        //                 search: params.term // search term
        //             };
        //         },
        //         processResults: function(response) {
        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     }
        // })

        // $(document).on('click', '.add_pickup_point', function() {
        //     let location = $('.pick_up_point_select').val() ? $('.pick_up_point_select').val() : null;
        //     let location_array = $('input[name="pickup_location[]"]').map(function() {
        //         return $(this).val();
        //     }).get();
        //     if (location_array.length < 4) {



        //         let new_location_field = `<div class="col-sm-4 mb-2 d-flex justify-content-between location_row">
        //                                     <input type="text" name="pickup_location[]" class="form-control from_location mr-1" value="${location}">
        //                                     <button type="button" class="btn btn-danger remove_booking" title="Remove Location">
        //                                         <i class="fa fa-times" aria-hidden="true"></i>
        //                                     </button>
        //                                 </div>`

        //         if (location && !location_array.includes(location)) {
        //             $('.pick_up_point_select').val('').trigger('change');
        //             $('#points_values').append(new_location_field);




        //         } else {

        //         }


        //     } else {
        //         alert('Only 3 via points allowed');
        //     }

        //     AutoViapointTrip();


        // })

        // $(document).on('click', '.return_add_pickup_point', function() {
        //     let location = $('.return_pick_up_point_select').val() ? $('.return_pick_up_point_select')
        //         .val() : null;
        //     let location_array = $('input[name="return_pickup_location[]"]').map(function() {
        //         return $(this).val();
        //     }).get();
        //     if (location_array.length < 4) {



        //         let new_location_field = `<div class="col-sm-4 mb-2 d-flex justify-content-between location_row">
        //                                     <input type="text" name="return_pickup_location[]" class="form-control from_location mr-1" value="${location}">
        //                                     <button type="button" class="btn btn-danger remove_booking" title="Remove Location">
        //                                         <i class="fa fa-times" aria-hidden="true"></i>
        //                                     </button>
        //                                 </div>`

        //         if (location && !location_array.includes(location)) {
        //             $('.return_pick_up_point_select').val('').trigger('change');
        //             $('#return_points_values').append(new_location_field);
        //         } else {

        //         }

        //     } else {
        //         alert('Only 3 via points allowed');
        //     }
        // })


        // $(document).on('click', '.return_calc_new_amount', function() {
        //     let car_type = $('#car_type').val()
        //     let from_location = $('#return_pick_up').val()
        //     let to_location = $('#return_drop_off').val()
        //     let location_array = $('input[name="return_pickup_location[]"]').map(function() {
        //         return $(this).val();
        //     }).get();

        //     location_array.unshift(from_location)
        //     location_array.push(to_location)

        //     //

        //     $.ajax({
        //         data: {
        //             car_type: car_type,
        //             pick_up_points: location_array,
        //         },
        //         url: "{{ route('RecalculateBooking') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         beforeSend: function() {
        //             $loading.show()

        //         },
        //         success: function(response) {
        //             if (response.status === 200) {
        //                 $('#return_distance').val(response.total_distance)
        //                 $('#return_total_cost').val(response.total_fare)
        //                 $('#one_way_travel_time').val(response.total_duration)
        //                 $('#return_actual_amount').val(response.total_fare)

        //                 //calculate total cost
        //                 CalculateAmount('return')

        //                 Swal.fire({
        //                     position: 'top-end',
        //                     icon: 'success',
        //                     text: 'Recalculation Done',
        //                     showConfirmButton: false,
        //                     timer: 2000,
        //                 })



        //             } else {
        //                 Swal.fire({
        //                     position: 'top-end',
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'unable to fetch new calculated details.',
        //                     showConfirmButton: false,
        //                     timer: 2000,
        //                 })
        //             }
        //             $loading.hide()
        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //             $loading.hide()
        //         }
        //     })


        // })



        // $(document).on('click', '.calc_new_amount', function() {
        //     let car_type = $('#car_type').val()
        //     let from_location = $('#one_way_pick_up').val()
        //     let to_location = $('#one_way_drop_off').val()
        //     let location_array = $('input[name="pickup_location[]"]').map(function() {
        //         return $(this).val();
        //     }).get();

        //     location_array.unshift(from_location)
        //     location_array.push(to_location)

        //     //

        //     $.ajax({
        //         data: {
        //             car_type: car_type,
        //             pick_up_points: location_array,
        //         },
        //         url: "{{ route('RecalculateBooking') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         beforeSend: function() {
        //             $loading.show();
        //         },
        //         success: function(response) {
        //             if (response.status === 200) {
        //                 $('#one_way_distance').val(response.total_distance)
        //                 $('#one_way_total_cost').val(response.total_fare)
        //                 $('#one_way_travel_time').val(response.total_duration)
        //                 $('#one_way_actual_amount').val(response.total_fare)

        //                 //calculate total cost
        //                 CalculateAmount('one_way')

        //                 Swal.fire({
        //                     position: 'top-end',
        //                     icon: 'success',
        //                     text: 'Recalculation Done',
        //                     showConfirmButton: false,
        //                     timer: 2000,
        //                 })
        //             } else {
        //                 Swal.fire({
        //                     position: 'top-end',
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'unable to fetch new calculated details.',
        //                     showConfirmButton: false,
        //                     timer: 2000,
        //                 })
        //             }

        //             $loading.hide();
        //         },

        //         error: function(data) {
        //             console.log('Error:', data);
        //             $loading.hide()
        //         }
        //     })


        // })

        // $(document).on('click', '.remove_booking', function() {
        //     $(this).closest('.location_row').remove()
        // })

        // //Select2 AJAX search for locations
        // $('#one_way_pick_up, #one_way_drop_off, #return_pick_up, #return_drop_off').select2({
        //     ajax: {
        //         url: "{{ route('GetLocations') }}",
        //         type: "post",
        //         dataType: 'json',
        //         delay: 400,
        //         data: function(params) {

        //             return {
        //                 search: params.term // search term
        //             };
        //         },
        //         processResults: function(response) {

        //             return {
        //                 results: response
        //             };
        //         },
        //         cache: true
        //     }
        // })

        // //Special date extra charges check for one way pickup date
        // $('#one_way_payment_method').change(function() {
        //     $is_partial_payment = $('#one_way_payment_method').val();

        //     if ($is_partial_payment == 'partial_Payment') {

        //         $('.hidden_content').show();
        //     } else {

        //         $('.hidden_content').hide();

        //     }

        // })




        // $('body').on('keyup', '#one_way_paid_amount', function() {

        //     const isNumeric = (string) => {
        //         return /^[+-]?\d+(\.\d+)?$/.test(string);
        //     };

        //     let one_way_total_cost = $('#one_way_total_cost').val();
        //     let one_way_paid_amount = $('#one_way_paid_amount').val();
        //     one_way_paid_amount = one_way_paid_amount == '' ? 0 : parseFloat(one_way_paid_amount)
        //     one_way_total_cost = one_way_total_cost == '' ? 0 : parseFloat(one_way_total_cost)


        //     if (isNumeric(one_way_total_cost) && isNumeric(one_way_paid_amount)) {


        //         if (Math.sign(one_way_total_cost) != -1 && Math.sign(one_way_paid_amount) != -1) {

        //             one_way_total_cost >= one_way_paid_amount ?
        //                 $('#one_way_pending_amount').val(one_way_total_cost - one_way_paid_amount) :
        //                 ($('#one_way_paid_amount').val(''), $('#one_way_pending_amount').val(
        //                     one_way_total_cost));
        //         } else {
        //             $('#one_way_paid_amount').val('')
        //             $('#one_way_pending_amount').val(one_way_total_cost)
        //         }

        //     } else {


        //         $('#one_way_paid_amount').val('')
        //         $('#one_way_pending_amount').val(one_way_total_cost)

        //     }

        // });

        // $('body').on('keyup', '#one_way_total_cost', function() {
        //     const isNumeric = (string) => {
        //         return /^[+-]?\d+(\.\d+)?$/.test(string);
        //     };

        //     let $onway_total_cost = $('#one_way_total_cost');
        //     let $oneway_paid_amount = $("#one_way_paid_amount");
        //     let $one_way_pending_amount = $("#one_way_pending_amount");

        //     let $onway_total_cost_value = $onway_total_cost.val();
        //     let $oneway_paid_amount_value = $oneway_paid_amount.val();

        //     // Convert empty strings to 0
        //     $onway_total_cost_value = $onway_total_cost_value === '' ? 0 : parseFloat(
        //         $onway_total_cost_value);
        //     $oneway_paid_amount_value = $oneway_paid_amount_value === '' ? 0 : parseFloat(
        //         $oneway_paid_amount_value);

        //     if (isNumeric($onway_total_cost_value) && isNumeric($oneway_paid_amount_value)) {
        //         // Absolute values don't seem to be used here, so removing these lines
        //         Math.abs($onway_total_cost_value);
        //         Math.abs($oneway_paid_amount_value);

        //         let previousValue = $onway_total_cost.data('previousValue');
        //         if ($onway_total_cost_value < $oneway_paid_amount_value) {
        //             $onway_total_cost.val(previousValue);
        //             $oneway_paid_amount.val('');
        //             $one_way_pending_amount.val(previousValue);
        //             alert('Total amount and paid amount are mismatched and onway paid amount removed');
        //             return; // Return here to prevent further execution
        //         }

        //         var value = $onway_total_cost_value - $oneway_paid_amount_value == 0 ? '' :
        //             $onway_total_cost_value - $oneway_paid_amount_value

        //         $one_way_pending_amount.val(value);
        //         $onway_total_cost.data('previousValue', $onway_total_cost_value);
        //     } else {
        //         $oneway_paid_amount.val('');
        //         $one_way_pending_amount.val('');
        //         $onway_total_cost.val('');
        //         // alert('give the number value only ');
        //     }
        // });





        // @if (!$isEditable)
        //     //Special date extra charges check for on pageload
        //     CheckSpecialDay($('#one_way_pickup_date').val(), 'one_way')

        //     //Journey type selection for return container visibility
        //     $('[name="journey_type"]').click(function() {
        //         let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false
        //         let place = $('#one_way_drop_off_place_type').val()

        //         ReturnContainerVisibility(journey_type)
        //         AutoSelectReturnPickup()
        //         AutoSelectReturnDrop()
        //         // AutoViapointTrip()
        //         ReturnTripInitialAutoCalculation(journey_type)
        //         //Flight details container visibility for return
        //         ArrivalDetailsContainer(place, 'return')
        //     })

        //     //Return auto address fill for pickup
        //     $('#return_pick_up').change(function() {
        //         let place = $('#return_pick_up').select2('data')[0].place_type ?
        //             $('#return_pick_up').select2('data')[0].place_type : ''

        //         let place_id = $('#return_pick_up').select2('data')[0].place_id ?
        //             $('#return_pick_up').select2('data')[0].place_id : ''

        //         let area = $('#return_pick_up').val()
        //         let address = $('#return_pick_up').select2('data')[0].area_address ?
        //             $('#return_pick_up').select2('data')[0].area_address : area
        //         if (area && auto_fill_places.includes(place)) {
        //             $('#return_pickup_address').val(address)
        //             $('#return_place_from').val(place_id)
        //         } else {
        //             $('#return_pickup_address').val('')
        //             $('#return_place_from').val(place_id)
        //         }

        //         //Flight details container visibility for return
        //         ArrivalDetailsContainer(place, 'return')
        //     })

        //     //Return auto address fill for dropoff
        //     $('#return_drop_off').change(function() {
        //         let place = $('#return_drop_off').select2('data')[0].place_type ?
        //             $('#return_drop_off').select2('data')[0].place_type : ''

        //         let place_id = $('#return_drop_off').select2('data')[0].place_id ?
        //             $('#return_drop_off').select2('data')[0].place_id : ''

        //         let area = $('#return_drop_off').val()

        //         let address = $('#return_drop_off').select2('data')[0].area_address ?
        //             $('#return_drop_off').select2('data')[0].area_address : area

        //         if (area && auto_fill_places.includes(place)) {
        //             $('#return_dropoff_address').val(address)
        //             $('#return_place_to').val(place_id)
        //         } else {
        //             $('#return_dropoff_address').val('')
        //             $('#return_place_to').val(place_id)
        //         }
        //     })
        // @endif

        */ ?>
    })

    <?php /*

    // function ShowClientInfo(data) {
    //     $('#client_info').empty()

    //     $('#client_info').html(
    //         `<div class="col-sm-4">
    //             <label for="client_name" class="col-form-label">Client Name <span class="required">*</span></label>
    //             <input type="text" id="client_name" name="client_name" class="form-control" value="${data.f_name}" placeholder="Enter client name">
    //             <p class="text-danger invalid-client-name"></p>
    //         </div>
    //         <div class="col-sm-4">
    //             <label for="client_email" class="col-form-label">Email</label>
    //             <input type="text" id="client_email" name="client_email" class="form-control" value="${data.email}" placeholder="Enter client email">
    //             <p class="text-danger invalid-client-email"></p>
    //         </div>
    //         <div class="col-sm-4">
    //             <label for="client_mobile" class="col-form-label">Mobile <span class="required">*</span></label>
    //             <input type="text" id="client_mobile" name="client_mobile" class="form-control" value="${data.phone}" placeholder="Enter client mobile">
    //             <p class="text-danger invalid-client-mobile"></p>
    //         </div>`
    //     )
    // }

    // function ClientModal_ResetErrors() {
    //     $('.invalid-first-name, .invalid-phone-no, .invalid-email').text('');
    // }

    // function ClientModal_ShowErrors(errors) {
    //     if (errors.first_name) {
    //         $('.invalid-first-name').text(errors.first_name);
    //     }
    //     if (errors.phone) {
    //         $('.invalid-phone-no').text(errors.phone);
    //     }
    //     if (errors.email) {
    //         $('.invalid-email').text(errors.email);
    //     }
    // }

    // function ReturnContainerVisibility(visibility) {
    //     if (visibility) {
    //         $('#return_container').show()
    //         CheckSpecialDay($('#return_pickup_date').val(), 'return')
    //     } else {
    //         $('#return_container').hide()
    //     }
    // }

    // function CarCapacityMaker(car_type) {
    //     $('#passenger_count').empty()
    //     $('#luggage_count').empty()
    //     $('#hand_luggage_count').empty()

    //     if (car_type) {

    //         $.ajax({
    //             data: {
    //                 car_type: car_type,
    //             },
    //             url: "{{ route('GetCarDetails') }}",
    //             type: "POST",
    //             dataType: 'json',
    //             beforeSend: function() {
    //                 $loading.show();
    //             },
    //             success: function(response) {
    //                 //dd means dropdown
    //                 let passenger_dd = ''
    //                 let luggage_dd = ''
    //                 let hand_luggage_dd = ''
    //                 let child_dd = ''

    //                 //

    //                 @if ($isEditable)
    //                     for (let i = 1; i <= response[0].passenger; i++) {
    //                         passenger_dd +=
    //                             `<option value="${i}" ${i == {{ $booking_details->passengers ? $booking_details->passengers : 0 }} ? "selected" : ""}>${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].luggage; i++) {
    //                         luggage_dd +=
    //                             `<option value="${i}" ${i == {{ $booking_details->baggages ? $booking_details->baggages : 0 }} ? "selected" : ""}>${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].hand_luggage; i++) {
    //                         hand_luggage_dd +=
    //                             `<option value="${i}" ${i == {{ $booking_details->hand_luggages ? $booking_details->hand_luggages : 0 }} ? "selected" : ""}>${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].child; i++) {
    //                         child_dd +=
    //                             `<option value="${i}" ${i == {{ $booking_details->child_seat ? $booking_details->child_seat : 0 }} ? "selected" : ""}>${i}</option>`
    //                     }
    //                 @else


    //                     for (let i = 1; i <= response[0].passenger; i++) {
    //                         passenger_dd += `<option value="${i}">${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].luggage; i++) {
    //                         luggage_dd += `<option value="${i}">${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].hand_luggage; i++) {
    //                         hand_luggage_dd += `<option value="${i}">${i}</option>`
    //                     }
    //                     for (let i = 0; i <= response[0].child; i++) {
    //                         child_dd += `<option value="${i}">${i}</option>`
    //                     }
    //                 @endif

    //                 $('#passenger_count').html(passenger_dd)
    //                 $('#luggage_count').html(luggage_dd)
    //                 $('#hand_luggage_count').html(hand_luggage_dd)
    //                 $('#child_seat_count').html(child_dd)


    //                 @if ($isEditable)
    //                     // Select child seat count
    //                     $('#child_seat_count').trigger('change')
    //                 @endif

    //                 $loading.hide()
    //             },
    //             error: function(data) {
    //                 $loading.hide()
    //             }
    //         });
    //     }
    // }

    // function ChildSeatMaker(seat_count) {
    //     let child_seat_dropdown = ''

    //     @if ($isEditable)
    //         for (let i = 1; i <= seat_count; i++) {
    //             if (i === 1) {
    //                 child_seat_dropdown += `<div class="col-sm-3">
    //                         <label for="baby_seat_${i}">Child Seat ${i}</label>
    //                         <select class="form-control" id="baby_seat_${i}" name="baby_seat_${i}">
    //                             <option value="Rear Facing" ${ "Rear Facing" === "{{ $booking_details->firstbaby ? $booking_details->firstbaby : '' }}" ? "selected" : "" }>Rear Facing</option>
    //                             <option value="Forward Facing" ${ "Forward Facing" === "{{ $booking_details->firstbaby ? $booking_details->firstbaby : '' }}" ? "selected" : "" }>Forward Facing</option>
    //                             <option value="Booster" ${ "Booster" === "{{ $booking_details->firstbaby ? $booking_details->firstbaby : '' }}" ? "selected" : "" }>Booster</option>
    //                         </select>
    //                         <p class="text-danger invalid-baby-seat-${i}"></p>
    //                     </div>`
    //             } else if (i === 2) {
    //                 child_seat_dropdown += `<div class="col-sm-3">
    //                         <label for="baby_seat_${i}">Child Seat ${i}</label>
    //                         <select class="form-control" id="baby_seat_${i}" name="baby_seat_${i}">
    //                             <option value="Rear Facing" ${ "Rear Facing" === "{{ $booking_details->secondbaby ? $booking_details->secondbaby : '' }}" ? "selected" : "" }>Rear Facing</option>
    //                             <option value="Forward Facing" ${ "Forward Facing" === "{{ $booking_details->secondbaby ? $booking_details->secondbaby : '' }}" ? "selected" : "" }>Forward Facing</option>
    //                             <option value="Booster" ${ "Booster" === "{{ $booking_details->secondbaby ? $booking_details->secondbaby : '' }}" ? "selected" : "" }>Booster</option>
    //                         </select>
    //                         <p class="text-danger invalid-baby-seat-${i}"></p>
    //                     </div>`
    //             } else if (i === 3) {
    //                 child_seat_dropdown += `<div class="col-sm-3">
    //                         <label for="baby_seat_${i}">Child Seat ${i}</label>
    //                         <select class="form-control" id="baby_seat_${i}" name="baby_seat_${i}">
    //                             <option value="Rear Facing" ${ "Rear Facing" === "{{ $booking_details->thirdbaby ? $booking_details->thirdbaby : '' }}" ? "selected" : "" }>Rear Facing</option>
    //                             <option value="Forward Facing" ${ "Forward Facing" === "{{ $booking_details->thirdbaby ? $booking_details->thirdbaby : '' }}" ? "selected" : "" }>Forward Facing</option>
    //                             <option value="Booster" ${ "Booster" === "{{ $booking_details->thirdbaby ? $booking_details->thirdbaby : '' }}" ? "selected" : "" }>Booster</option>
    //                         </select>
    //                         <p class="text-danger invalid-baby-seat-${i}"></p>
    //                     </div>`
    //             }
    //         }
    //     @else
    //         for (let i = 1; i <= seat_count; i++) {
    //             child_seat_dropdown += `<div class="col-sm-3">
    //                     <label for="baby_seat_${i}">Child Seat ${i}</label>
    //                     <select class="form-control" id="baby_seat_${i}" name="baby_seat_${i}">
    //                         <option value="Rear Facing" selected>Rear Facing</option>
    //                         <option value="Forward Facing">Forward Facing</option>
    //                         <option value="Booster">Booster</option>
    //                     </select>
    //                     <p class="text-danger invalid-baby-seat-${i}"></p>
    //                 </div>`
    //         }
    //     @endif

    //     if (seat_count < 1) {
    //         $('#child_seat_container').empty()
    //     } else {
    //         $('#child_seat_container').html(child_seat_dropdown)
    //     }
    // }

    // function ArrivalDetailsContainer(place_type, journey_type) {
    //     const extra_details_places = ['Airports', 'Seaports']

    //     let place = place_type ? place_type.trim() : '';


    //     let journey = journey_type ? journey_type.trim() : '';

    //     if (place === '' && journey === '') {
    //         $('.one_way_arrival_flight_ship_details').hide()
    //         $('.return_flight_ship_details').hide()
    //     }

    //     if (extra_details_places.includes(place) && journey === 'one_way') {
    //         $('.one_way_arrival_flight_ship_details').show()
    //         $('.invalid_one_way_flight_number').text('')
    //         $('.invalid_one_way_flight_from').text('')


    //         if (place === 'Airports') {
    //             $('#is_airport_or_ship_one_way').val(1)

    //             $("label[for|='one_way_flight_number']").html('Flight Number <span class="required">*</span>')
    //             $("label[for|='one_way_flight_from']").html('Flight From <span class="required">*</span>')

    //             $('#one_way_flight_number').attr('placeholder', 'Flight Number');
    //             $('#one_way_flight_from').attr('placeholder', 'Flight From');

    //             $('#pickup_time_container').show()
    //         } else if (place === 'Seaports') {
    //             $('#is_airport_or_ship_one_way').val(2)


    //             $("label[for|='one_way_flight_number']").html('Arrival Cruise Name <span class="required">*</span>')
    //             $("label[for|='one_way_flight_from']").html('Cruise From <span class="required">*</span>')

    //             $('#one_way_flight_number').attr('placeholder', 'Arrival Cruise Name');
    //             $('#one_way_flight_from').attr('placeholder', 'Cruise From');

    //             $('#pickup_time_container').hide()
    //             $('#one_way_flight_meet_greet').val('')
    //         }

    //     } else if (!extra_details_places.includes(place) && journey === 'one_way') {
    //         $('#is_airport_or_ship_one_way').val(0)
    //         $('.one_way_arrival_flight_ship_details').hide()
    //         $('#pickup_time_container').hide()
    //         $('#one_way_flight_meet_greet').val('')
    //     }

    //     if (extra_details_places.includes(place) && journey === 'return') {
    //         $('.return_flight_ship_details').show()
    //         $('.invalid_return_flight_number').text('')
    //         $('.invalid_return_flight_from').text('')

    //         if (place === 'Airports') {
    //             $('#is_airport_or_ship_return').val(1)
    //             $('#return_transport_name').text('Flight Information')

    //             $("label[for|='return_flight_number']").html('Flight Number <span class="required">*</span>')
    //             $("label[for|='return_flight_from']").html('Flight From <span class="required">*</span>')

    //             $('#return_flight_number').attr('placeholder', 'Flight Number');
    //             $('#return_flight_from').attr('placeholder', 'Flight From');

    //             $('#return_time_container').show()
    //         } else if (place === 'Seaports') {
    //             $('#is_airport_or_ship_return').val(2)
    //             $('#return_transport_name').text('Cruise Information')

    //             $("label[for|='return_flight_number']").html('Cruise Name <span class="required">*</span>')
    //             $("label[for|='return_flight_from']").html('Cruise From <span class="required">*</span>')

    //             $('#return_flight_number').attr('placeholder', 'Cruise Name');
    //             $('#return_flight_from').attr('placeholder', 'Cruise From');
    //             $('#return_time_container').hide()
    //             $('#return_flight_meet_greet').val('')
    //         }
    //     } else if (!extra_details_places.includes(place) && journey === 'return') {
    //         $('#is_airport_or_ship_return').val(0)
    //         $('.return_flight_ship_details').hide()
    //         $('#return_time_container').hide()
    //         $('#return_flight_meet_greet').val('')
    //     }
    // }

    // function DepartureDetailsContainer(place_type, journey_type) {
    //     let place = place_type ? place_type.trim() : '';
    //     let journey = journey_type ? journey_type.trim() : '';

    //     if (place === '' || journey === '') {
    //         $('.one_way_departure_flight_ship_details').hide()
    //     }

    //     if (place === 'Seaports') {
    //         $('.one_way_departure_flight_ship_details').show()
    //     } else {
    //         $('.one_way_departure_flight_ship_details').hide()
    //     }
    // }

    // function AssignValues(data, journey_type) {
    //     if (journey_type === 'one_way') {
    //         $('#one_way_travel_time').val(data.duration)
    //         $('#one_way_distance').val(data.miles)
    //         $('#one_way_actual_amount').val(data.total_fare)

    //         //these values are stored in hidden input fields
    //         $('#one_way_from_lati').val(data.from_lati)
    //         $('#one_way_from_longi').val(data.from_longi)
    //         $('#one_way_to_lati').val(data.to_lati)
    //         $('#one_way_to_longi').val(data.to_longi)

    //         //calculate total cost
    //         CalculateAmount('one_way')

    //     } else if (journey_type === 'return') {
    //         $('#return_travel_time').val(data.duration)
    //         $('#return_distance').val(data.miles)
    //         $('#return_actual_amount').val(data.total_fare)

    //         //these values are stored in hidden input fields
    //         $('#return_from_lati').val(data.from_lati)
    //         $('#return_from_longi').val(data.from_longi)
    //         $('#return_to_lati').val(data.to_lati)
    //         $('#return_to_longi').val(data.to_longi)

    //         //calculate total cost
    //         CalculateAmount('return')
    //     }
    // }

    function ShowClientInfo(data) {
        $('#client_info').empty()

        $('#client_info').html(
            `<div class="col-sm-4">
                <label for="client_name" class="col-form-label">Client Name <span class="required">*</span></label>
                <input type="text" id="client_name" name="client_name" class="form-control" value="${data.name}" placeholder="Enter client name">
                <p class="text-danger invalid-client-name"></p>
            </div>
            <div class="col-sm-4">
                <label for="client_email" class="col-form-label">Email</label>
                <input type="text" id="client_email" name="client_email" class="form-control" value="${data.email}" placeholder="Enter client email">
                <p class="text-danger invalid-client-email"></p>
            </div>
            <div class="col-sm-4">
                <label for="client_mobile" class="col-form-label">Mobile <span class="required">*</span></label>
                <input type="text" id="client_mobile" name="client_mobile" class="form-control" value="${data.phone}" placeholder="Enter client mobile">
                <p class="text-danger invalid-client-mobile"></p>
            </div>`
        )
    }







    // function ShowTripErrors(errors) {
    //     if ($('#search_clients').val() == '') {
    //         ShowClientInfo({
    //             f_name: '',
    //             email: '',
    //             phone: ''
    //         })
    //     }

    //     if (errors.client_id) {
    //         $('.invalid_client_id').text(errors.client_id)
    //     } else {
    //         $('.invalid_client_id').text('')
    //     }

    //     if (errors.client_name) {
    //         $('.invalid-client-name').text(errors.client_name)
    //     } else {
    //         $('.invalid-client-name').text('')
    //     }

    //     if (errors.client_email) {
    //         $('.invalid-client-email').text(errors.client_email)
    //     } else {
    //         $('.invalid-client-email').text('')
    //     }

    //     if (errors.client_mobile) {
    //         $('.invalid-client-mobile').text(errors.client_mobile)
    //     } else {
    //         $('.invalid-client-mobile').text('')
    //     }

    //     if (errors.journey_type) {
    //         $('.invalid-journey-type').text(errors.journey_type)
    //     } else {
    //         $('.invalid-journey-type').text('')
    //     }

    //     if (errors.booking_date) {
    //         $('.invalid-booking-date').text(errors.booking_date)
    //     } else {
    //         $('.invalid-booking-date').text('')
    //     }

    //     if (errors.car_type) {
    //         $('.invalid-car-type').text(errors.car_type)
    //     } else {
    //         $('.invalid-car-type').text('')
    //     }

    //     if (errors.passenger_count) {
    //         $('.invalid-passenger-count').text(errors.passenger_count)
    //     } else {
    //         $('.invalid-passenger-count').text('')
    //     }

    //     if (errors.child_seat_count) {
    //         $('.invalid-child-seat-count').text(errors.child_seat_count)
    //     } else {
    //         $('.invalid-child-seat-count').text('')
    //     }

    //     if (errors.luggage_count) {
    //         $('.invalid-luggage-count').text(errors.luggage_count)
    //     } else {
    //         $('.invalid-luggage-count').text('')
    //     }

    //     if (errors.hand_luggage_count) {
    //         $('.invalid-hand-luggage-count').text(errors.hand_luggage_count)
    //     } else {
    //         $('.invalid-hand-luggage-count').text('')
    //     }

    //     if (errors.baby_seat_1) {
    //         $('.invalid-baby-seat-1').text(errors.baby_seat_1)
    //     } else {
    //         $('.invalid-baby-seat-1').text('')
    //     }

    //     if (errors.baby_seat_2) {
    //         $('.invalid-baby-seat-2').text(errors.baby_seat_2)
    //     } else {
    //         $('.invalid-baby-seat-2').text('')
    //     }

    // }

    // function ShowOneWayErrors(errors) {
    //     if (errors.one_way_pick_up) {
    //         $('.invalid_one_way_pick_up').text(errors.one_way_pick_up)
    //     } else {
    //         $('.invalid_one_way_pick_up').text('')
    //     }

    //     if (errors.one_way_drop_off) {
    //         $('.invalid_one_way_drop_off').text(errors.one_way_drop_off)
    //     } else {
    //         $('.invalid_one_way_drop_off').text('')
    //     }

    //     if (errors.one_way_pickup_date) {
    //         $('.invalid_one_way_pickup_date').text(errors.one_way_pickup_date)
    //     } else {
    //         $('.invalid_one_way_pickup_date').text('')
    //     }

    //     if (errors.one_way_pickup_time) {
    //         $('.invalid_one_way_pickup_time').text(errors.one_way_pickup_time)
    //     } else {
    //         $('.invalid_one_way_pickup_time').text('')
    //     }

    //     if (errors.one_way_pickup_address) {
    //         $('.invalid_one_way_pickup_address').text(errors.one_way_pickup_address)
    //     } else {
    //         $('.invalid_one_way_pickup_address').text('')
    //     }

    //     if (errors.one_way_dropoff_address) {
    //         $('.invalid_one_way_dropoff_address').text(errors.one_way_dropoff_address)
    //     } else {
    //         $('.invalid_one_way_dropoff_address').text('')
    //     }

    //     if (errors.one_way_flight_date) {
    //         $('.invalid_one_way_flight_date').text(errors.one_way_flight_date)
    //     } else {
    //         $('.invalid_one_way_flight_date').text('')
    //     }

    //     if (errors.one_way_flight_time) {
    //         $('.invalid_one_way_flight_time').text(errors.one_way_flight_time)
    //     } else {
    //         $('.invalid_one_way_flight_time').text('')
    //     }

    //     // if (errors.one_way_flight_pickup_time) {
    //     //     $('.invalid_one_way_flight_pickup_time').text(errors.one_way_flight_pickup_time)
    //     // } else {
    //     //     $('.invalid_one_way_flight_pickup_time').text('')
    //     // }

    //     if (errors.one_way_flight_number) {
    //         $('.invalid_one_way_flight_number').text(errors.one_way_flight_number)
    //     } else {
    //         $('.invalid_one_way_flight_number').text('')
    //     }

    //     if (errors.one_way_flight_from) {
    //         $('.invalid_one_way_flight_from').text(errors.one_way_flight_from)
    //     } else {
    //         $('.invalid_one_way_flight_from').text('')
    //     }

    //     if (errors.one_way_payment_status) {
    //         $('.invalid_one_way_payment_status').text(errors.one_way_payment_status)
    //     } else {
    //         $('.invalid_one_way_payment_status').text('')
    //     }

    //     if (errors.one_way_payment_method) {
    //         $('.invalid_one_way_payment_method').text(errors.one_way_payment_method)
    //     } else {
    //         $('.invalid_one_way_payment_method').text('')
    //     }

    //     if (errors.one_way_order_status) {
    //         $('.invalid_one_way_order_status').text(errors.one_way_order_status)
    //     } else {
    //         $('.invalid_one_way_order_status').text('')
    //     }

    //     if (errors.one_way_total_cost) {
    //         $('.invalid_one_way_total_cost').text(errors.one_way_total_cost)
    //     } else {
    //         $('.invalid_one_way_total_cost').text('')
    //     }

    //     if (errors.one_way_extra_cost) {
    //         $('.invalid_one_way_extra_cost').text(errors.one_way_extra_cost)
    //     } else {
    //         $('.invalid_one_way_extra_cost').text('')
    //     }

    //     if (errors.one_way_distance) {
    //         $('.invalid_one_way_distance').text(errors.one_way_distance)
    //     } else {
    //         $('.invalid_one_way_distance').text('')
    //     }

    //     if (errors.one_way_travel_time) {
    //         $('.invalid_one_way_travel_time').text(errors.one_way_travel_time)
    //     } else {
    //         $('.invalid_one_way_travel_time').text('')
    //     }

    //     if (errors.one_way_dest_ship_name) {
    //         $('.invalid_one_way_dest_ship_name').text(errors.one_way_dest_ship_name)
    //     } else {
    //         $('.invalid_one_way_dest_ship_name').text('')
    //     }

    // }

    // function ShowReturnErrors(errors) {

    //     if (errors.return_pick_up) {
    //         $('.invalid_return_pick_up').text(errors.return_pick_up)
    //     } else {
    //         $('.invalid_return_pick_up').text('')
    //     }

    //     if (errors.return_drop_off) {
    //         $('.invalid_return_drop_off').text(errors.return_drop_off)
    //     } else {
    //         $('.invalid_return_drop_off').text('')
    //     }

    //     if (errors.return_pickup_date) {
    //         $('.invalid_return_pickup_date').text(errors.return_pickup_date)
    //     } else {
    //         $('.invalid_return_pickup_date').text('')
    //     }

    //     if (errors.return_pickup_time) {
    //         $('.invalid_return_pickup_time').text(errors.return_pickup_time)
    //     } else {
    //         $('.invalid_return_pickup_time').text('')
    //     }

    //     if (errors.return_pickup_address) {
    //         $('.invalid_return_pickup_address').text(errors.return_pickup_address)
    //     } else {
    //         $('.invalid_return_pickup_address').text('')
    //     }

    //     if (errors.return_dropoff_address) {
    //         $('.invalid_return_dropoff_address').text(errors.return_dropoff_address)
    //     } else {
    //         $('.invalid_return_dropoff_address').text('')
    //     }

    //     if (errors.return_flight_date) {
    //         $('.invalid_return_flight_date').text(errors.return_flight_date)
    //     } else {
    //         $('.invalid_return_flight_date').text('')
    //     }

    //     if (errors.return_flight_time) {
    //         $('.invalid_return_flight_time').text(errors.return_flight_time)
    //     } else {
    //         $('.invalid_return_flight_time').text('')
    //     }

    //     if (errors.return_flight_pickup_time) {
    //         $('.invalid_return_flight_pickup_time').text(errors.return_flight_pickup_time)
    //     } else {
    //         $('.invalid_return_flight_pickup_time').text('')
    //     }

    //     if (errors.return_flight_number) {
    //         $('.invalid_return_flight_number').text(errors.return_flight_number)
    //     } else {
    //         $('.invalid_return_flight_number').text('')
    //     }

    //     if (errors.return_flight_from) {
    //         $('.invalid_return_flight_from').text(errors.return_flight_from)
    //     } else {
    //         $('.invalid_return_flight_from').text('')
    //     }

    //     if (errors.return_payment_status) {
    //         $('.invalid_return_payment_status').text(errors.return_payment_status)
    //     } else {
    //         $('.invalid_return_payment_status').text('')
    //     }

    //     if (errors.return_payment_method) {
    //         $('.invalid_return_payment_method').text(errors.return_payment_method)
    //     } else {
    //         $('.invalid_return_payment_method').text('')
    //     }

    //     if (errors.return_order_status) {
    //         $('.invalid_return_order_status').text(errors.return_order_status)
    //     } else {
    //         $('.invalid_return_order_status').text('')
    //     }

    //     if (errors.return_total_cost) {
    //         $('.invalid_return_total_cost').text(errors.return_total_cost)
    //     } else {
    //         $('.invalid_return_total_cost').text('')
    //     }

    //     if (errors.return_extra_cost) {
    //         $('.invalid_return_extra_cost').text(errors.return_extra_cost)
    //     } else {
    //         $('.invalid_return_extra_cost').text('')
    //     }

    //     if (errors.return_distance) {
    //         $('.invalid_return_distance').text(errors.return_distance)
    //     } else {
    //         $('.invalid_return_distance').text('')
    //     }

    //     if (errors.return_travel_time) {
    //         $('.invalid_return_travel_time').text(errors.return_travel_time)
    //     } else {
    //         $('.invalid_return_travel_time').text('')
    //     }

    //     if (errors.one_way_driver_amount) {
    //         $('.invalid_one_way_driver_amount').text(errors.one_way_driver_amount)
    //     } else {
    //         $('.invalid_one_way_driver_amount').text('')
    //     }
    // }

    // function CheckSpecialDay(special_date, journey_type) {


    //     $.ajax({
    //         data: {
    //             special_date: special_date,
    //         },
    //         url: "{{ route('CheckSpecialDay') }}",
    //         type: "POST",
    //         dataType: 'json',
    //         beforeSend: function() {
    //             $loading.show()

    //         },
    //         success: function(response) {
    //             if (response.length > 0) {
    //                 if (journey_type === 'one_way') {
    //                     $('#one_way_special_day_percentage').val(response[0].cost)

    //                     //calculate total cost
    //                     CalculateAmount('one_way')

    //                     Swal.fire({
    //                         position: 'top-end',
    //                         icon: 'success',
    //                         title: 'For outward trip: Extra ' + response[0].cost + '% applicable.',
    //                         showConfirmButton: false,
    //                         timer: 2000
    //                     })
    //                 } else if (journey_type === 'return') {
    //                     $('#return_special_day_percentage').val(response[0].cost)

    //                     //calculate total cost
    //                     CalculateAmount('return')

    //                     Swal.fire({
    //                         position: 'top-end',
    //                         icon: 'success',
    //                         title: 'For return trip: Extra ' + response[0].cost + '% applicable.',
    //                         showConfirmButton: false,
    //                         timer: 5000
    //                     })
    //                 }
    //             } else {
    //                 if (journey_type === 'one_way') {
    //                     $('#one_way_special_day_percentage').val(0)
    //                     CalculateAmount('one_way')
    //                 } else if (journey_type === 'return') {
    //                     $('#return_special_day_percentage').val(0)
    //                     CalculateAmount('return')
    //                 }
    //             }
    //             $loading.hide()
    //         },
    //         error: function(data) {
    //             $loading.hide()
    //         }
    //     });
    // }

    // function CalculateAmount(journey_type) {
    //     let total_cost = parseFloat({{ $isEditable && $booking_details->net_total ? $booking_details->net_total : 0 }})
    //     let isEditable = {{ $isEditable ? 'true' : 'false' }}

    //     let one_way_extra_percentage = parseFloat($('#one_way_special_day_percentage').val())
    //     let one_way_actual_amount = parseFloat($('#one_way_actual_amount').val())
    //     let one_way_caculated_amount = one_way_extra_percentage > 0 ? one_way_actual_amount + (one_way_actual_amount * (
    //         one_way_extra_percentage / 100)) : one_way_actual_amount

    //     let return_extra_percentage = parseFloat($('#return_special_day_percentage').val())
    //     let return_actual_amount = parseFloat($('#return_actual_amount').val())
    //     let return_caculated_amount = return_extra_percentage > 0 ? return_actual_amount + (return_actual_amount * (
    //         return_extra_percentage / 100)) : return_actual_amount

    //     if (journey_type === 'one_way') {
    //         $('#one_way_total_cost').val(Math.ceil(isEditable && total_cost !== 0 ? total_cost :
    //             one_way_caculated_amount).toFixed(2))

    //         var $oneway_total_cost = $('#one_way_total_cost').val() == '' ? 0 : $('#one_way_total_cost').val();
    //         var $one_way_paid_amount = $('#one_way_paid_amount').val() == '' ? 0 : $('#one_way_paid_amount').val();

    //         if ($oneway_total_cost < $one_way_paid_amount) {
    //             $('#one_way_pending_amount').val('');
    //             $('#one_way_paid_amount').val('');
    //             $('#one_way_total_cost').val('');
    //             alert('Total amount and paid amount are mismatched and onway paid amount removed');
    //         } else {
    //             $('#one_way_pending_amount').val($oneway_total_cost - $one_way_paid_amount);
    //         }


    //     } else if (journey_type === 'return') {
    //         $('#return_total_cost').val(Math.ceil(return_caculated_amount).toFixed(2))
    //     }

    //     $loading.hide();
    // }

    // function AutoSelectReturnPickup() {
    //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false
    //     let area = $('#one_way_drop_off').val()
    //     let address = $('#one_way_dropoff_address').val() ? $('#one_way_dropoff_address').val() : ''
    //     let place_id = $('#one_way_drop_off').select2('data')[0].place_id ?
    //         $('#one_way_drop_off').select2('data')[0].place_id : ''

    //     if (journey_type && area) {
    //         $('#return_pick_up').append(`<option value="${area}" selected>${area}</option>`)
    //         $('#return_pickup_address').val(address)
    //         $('#return_place_from').val(place_id)
    //     }
    // }

    // function AutoSelectReturnDrop() {
    //     let journey_type = $('[name="journey_type"]:checked').val() === 'Return' ? true : false
    //     let area = $('#one_way_pick_up').val()
    //     let address = $('#one_way_pickup_address').val() ? $('#one_way_pickup_address').val() : ''

    //     let place_id = $('#one_way_pick_up').select2('data')[0].place_id ?
    //         $('#one_way_pick_up').select2('data')[0].place_id : ''

    //     if (journey_type && area) {
    //         $('#return_drop_off').append(`<option value="${area}" selected>${area}</option>`)
    //         $('#return_dropoff_address').val(address)
    //         $('#return_place_to').val(place_id)
    //     }
    // }

    // function ReturnTripInitialAutoCalculation(journey_type) {
    //     if (journey_type) {
    //         $('#return_from_lati').val($('#one_way_to_lati').val())
    //         $('#return_from_longi').val($('#one_way_to_longi').val())

    //         $('#return_to_lati').val($('#one_way_from_lati').val())
    //         $('#return_to_longi').val($('#one_way_from_longi').val())

    //         $('#return_total_cost').val($('#one_way_actual_amount').val())
    //         $('#return_actual_amount').val($('#one_way_actual_amount').val())

    //         $('#return_distance').val($('#one_way_distance').val())
    //         $('#return_travel_time').val($('#one_way_travel_time').val())
    //     } else {
    //         $('#return_from_lati').val('')
    //         $('#return_from_longi').val('')
    //         $('#return_to_lati').val('')
    //         $('#return_to_longi').val('')
    //         $('#return_total_cost').val('')
    //         $('#return_actual_amount').val()
    //         $('#return_distance').val('')
    //         $('#return_travel_time').val('')
    //     }
    // } */ ?>

</script>
