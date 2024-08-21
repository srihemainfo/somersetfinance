<script>

    var $loader = $('.loader');
    var $loading = $('.loading-overlay');
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if ($isEditable)
            const client_info = {
                name: '{{ $data->customerdetail->name ?? '' }}',
                email: '{{ $data->customerdetail->email ?? '' }}',
                phone: '{{ $data->customerdetail->phone ?? '' }}'
            }

            ShowClientInfo(client_info)

            @if($data->loan_type_id)
                loan_type({{ $data->loan_type_id }})
            @endif
        @endif







        // $('#bookingForm').submit(function(e) {
        //     e.preventDefault();

        //     var isValid = true;

        //     // Clear previous errors
        //     $('.text-danger').html('');

        //     // Validate loan type
        //     var loanTypeId = $('#loan_type_id').val();
        //     if (!loanTypeId) {
        //         isValid = false;
        //         $('#loan_type_id_span').html('Loan type is required.').show();
        //     } else {
        //         $('#loan_type_id_span').hide();
        //     }

        //     // Validate client selection
        //     var clientId = $('#search_clients').val();
        //     if (!clientId) {
        //         isValid = false;
        //         $('.invalid_client_id').html('Client selection is required.');
        //     } else {
        //         $('.invalid_client_id').html('');
        //     }

        //     // Add more validations as needed

        //     if (isValid) {
        //         // If all validations pass, submit the form
        //         // this.submit();

        //         var $data = $('#bookingForm').serialize();



        //         $.ajax({
        //             url: "{{ route('admin.application.store') }}",
        //             method: 'post',
        //             data: $data,
        //             success: function(response) {
        //                 if (response.status) {


        //                 } else {

        //                 }
        //             },
        //             error: function() {


        //             }
        //         });


        //     }
        // });
        $('#bookingForm').submit(function(e) {
            e.preventDefault();

            var isValid = true;

            // Clear previous errors
            $('.text-danger').html('');

            // Validate loan type
            var loanTypeId = $('#loan_type_id').val();
            if (!loanTypeId) {
                isValid = false;
                $('#loan_type_id_span').html('Loan type is required.').show();
            } else {
                $('#loan_type_id_span').hide();
            }

            // Validate client selection
            var clientId = $('#search_clients').val();
            if (!clientId) {
                isValid = false;
                $('.invalid_client_id').html('Client selection is required.');
            } else {
                $('.invalid_client_id').html('');
            }

            // $('.co_applicants_name').each(function(index) {
            //     var coAppName = $(this).val().trim();
            //     var coAppEmail = $(this).closest('.co-applicant').find('.co-applicant-email').val().trim();
            //     // Validate fields as needed
            //     if (coAppName === '' || coAppEmail === '') {
            //         $(this).next('.text-danger').html('Co-applicant name and email are required.');
            //         isValid = false;
            //     }
            // });

            $('.co-applicant-error').remove();

            // Iterate over each set of co-applicant fields
            $('.coapplicant-fields').each(function(index) {
                var coAppName = $(this).find('.co_applicants_name').val().trim();
                var coAppEmail = $(this).find('.co_applicants_email').val().trim();
                var coAppPhone = $(this).find('.co_applicants_phone').val().trim();
                var coAppAddress = $(this).find('.co_applicants_address').val().trim();

                // Validate co-applicant name
                if (coAppName === '') {
                    $(this).find('.co_applicants_name').after(
                        '<p class="text-danger co-applicant-error">Co-applicant name is required.</p>'
                    );
                    isValid = false;
                }

                // Validate co-applicant email
                if (coAppEmail === '') {
                    $(this).find('.co_applicants_email').after(
                        '<p class="text-danger co-applicant-error">Co-applicant email is required.</p>'
                    );
                    isValid = false;
                } else {
                    // Additional email format validation if needed
                }

                // Validate co-applicant phone
                if (coAppPhone === '') {
                    $(this).find('.co_applicants_phone').after(
                        '<p class="text-danger co-applicant-error">Co-applicant phone is required.</p>'
                    );
                    isValid = false;
                }

                // Validate co-applicant address
                if (coAppAddress === '') {
                    $(this).find('.co_applicants_address').after(
                        '<p class="text-danger co-applicant-error">Co-applicant address is required.</p>'
                    );
                    isValid = false;
                }
            });



            // Validate co-applicants
            // $('.co-applicant').each(function() {
            //     var coApplicantIndex = $(this).data('index');
            //     console.log(coApplicantIndex);
            //     var coApplicantName = $(this).find('input[name="co_applicants[' + coApplicantIndex + '][name]"]').val();
            //     var coApplicantEmail = $(this).find('input[name="co_applicants[' + coApplicantIndex + '][email]"]').val();
            //     var coApplicantPhone = $(this).find('input[name="co_applicants[' + coApplicantIndex + '][phone]"]').val();
            //     var coApplicantAddress = $(this).find('textarea[name="co_applicants[' + coApplicantIndex + '][address1]"]').val();

            //     if (!coApplicantName || !coApplicantEmail || !coApplicantPhone || !coApplicantAddress) {
            //         isValid = false;
            //         $(this).find('.co-applicant-error').html('All fields are required for co-applicants.');
            //         console.log('All fields are required for co-applicants.');
            //     } else {
            //         $(this).find('.co-applicant-error').html('');
            //     }
            // });

            // Add more validations as needed...

            if (isValid) {
                // If all validations pass, serialize the form data
                var formData = $('#bookingForm').serialize();



            //   let  $caseid = @if($isEditable) {{ $data->id ?? false }} @endif ;
            //     formData.append('caseid',  $caseid )

                $loading.show();
                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.application.store') }}",
                    method: 'post',
                    data: formData,
                    success: function(response) {
                        if (response.status) {

                            if(response.data){


                                Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Added',
                                text: response.data,
                                showConfirmButton: false,
                                timer: 4000,
                            })

                            $('#bookingForm')[0].reset()
                            $('#bookingForm select').val(null).trigger('change');

                            }

                            window.location.href = response.url;
                            // // Handle success response
                            // $('#successMessage').html('Application submitted successfully.')
                            //     .show();
                            // Optionally, redirect or reset the form
                        } else {
                            // Handle error response
                            $('#errorMessage').html(response.message).show();
                        }
                        $loading.hide();
                    },
                    error: function(response) {
                        // Handle AJAX error response
                        $('#errorMessage').html(
                            'An error occurred while submitting the form.').show();

                        // Display validation errors from server
                        if (response.responseJSON && response.responseJSON.errors) {
                            $.each(response.responseJSON.errors, function(key, error) {
                                $('*[name="' + key + '"]').next('.text-danger')
                                    .html(error[0]);
                            });
                        }
                        $loading.hide();
                    }
                });
            }
        });

        // Additional logic to clear errors on input change
        $('#loan_type_id').change(function() {
            $('#loan_type_id_span').html('').hide();
        });

        $('#search_clients').change(function() {
            $('.invalid_client_id').html('');
        });

        // For dynamically loaded content, ensure to use event delegation
        $(document).on('change', 'input, select', function() {
            $(this).siblings('.text-danger').html('');
        });


        $('#addCoApplicantButton').click(function(e) {
            e.preventDefault();

            // Clone the education qualification fields
            var clonedFields = $('.coapplicant-fields').first().clone();

            // Clear the input values in the cloned fields
            clonedFields.find('input, select').val('');

            clonedFields.find('.remove-btn').remove();
            clonedFields.find('.text-danger').remove();

            // Append the cloned fields to the form

            $('#coapplicant_content_div').last().append(clonedFields);
            clonedFields.append(
                '<div class="col-sm-12"><button type="button" class="col-sm-2 remove-btn btn btn-danger mt-3">Remove</button></div>'
            );

        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.coapplicant-fields').remove();
        });



        //     let coApplicantIndex = 0;

        //     $('#addCoApplicantButton').click(function() {
        //         coApplicantIndex++;
        //         $('#coApplicantsSection').append(`
        //     <div class="co-applicant col-sm-12" id="coApplicant${coApplicantIndex}">
        //         <h5>Co-Applicant ${coApplicantIndex}</h5>
        //         <div class="row">
        //             <div class="col-sm-4">
        //                 <label for="co_applicant_name_${coApplicantIndex}" class="col-form-label">Client Name <span class="required">*</span></label>
        //                 <input type="text" id="co_applicant_name_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][name]" class="form-control" placeholder="Enter client name">
        //                 <p class="text-danger invalid-client-name"></p>
        //             </div>
        //             <div class="col-sm-4">
        //                 <label for="co_applicant_email_${coApplicantIndex}" class="col-form-label">Email</label>
        //                 <input type="email" id="co_applicant_email_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][email]" class="form-control" placeholder="Enter client email">
        //                 <p class="text-danger invalid-client-email"></p>
        //             </div>
        //             <div class="col-sm-4">
        //                 <label for="co_applicant_mobile_${coApplicantIndex}" class="col-form-label">Mobile <span class="required">*</span></label>
        //                 <input type="text" id="co_applicant_mobile_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][phone]" class="form-control" placeholder="Enter client mobile">
        //                 <p class="text-danger invalid-client-mobile"></p>
        //             </div>
        //             <div class="col-sm-4">
        //                 <label for="co_applicant_address1_${coApplicantIndex}" class="col-form-label">Address <span class="required">*</span></label>
        //                 <input type="text" id="co_applicant_address1_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][address1]" class="form-control" placeholder="Enter client mobile">
        //                 <p class="text-danger invalid-client-mobile"></p>
        //             </div>
        //         </div>
        //         <button type="button" class="btn btn-danger removeCoApplicantButton" data-index="${coApplicantIndex}">Remove</button>
        //         <hr>
        //     </div>
        // `);
        //     });



        // <div class="co-applicant" id="coApplicant${coApplicantIndex}">
        //             <h5>Co-Applicant ${coApplicantIndex}</h5>
        //             <div class="form-group">
        //                 <label for="co_applicant_name_${coApplicantIndex}">Name</label>
        //                 <input type="text" class="form-control" id="co_applicant_name_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][name]">
        //             </div>
        //             <div class="form-group">
        //                 <label for="co_applicant_email_${coApplicantIndex}">Email</label>
        //                 <input type="email" class="form-control" id="co_applicant_email_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][email]">
        //             </div>
        //             <div class="form-group">
        //                 <label for="co_applicant_phone_${coApplicantIndex}">Phone</label>
        //                 <input type="text" class="form-control" id="co_applicant_phone_${coApplicantIndex}" name="co_applicants[${coApplicantIndex}][phone]">
        //             </div>
        //             <button type="button" class="btn btn-danger removeCoApplicantButton" data-index="${coApplicantIndex}">Remove</button>
        //         </div>

        // Remove co-applicant section
        $(document).on('click', '.removeCoApplicantButton', function() {
            let index = $(this).data('index');
            $(`#coApplicant${index}`).remove();
        });

        function populateSelect(selectElement, data) {
            selectElement.empty(); // Clear existing options
            selectElement.append('<option value="">Select</option>'); // Add default option

            $.each(data, function(key, value) {
                selectElement.append('<option value="' + key + '">' + value + '</option>');
            });
        }


        // $(document).on('change', '#loan_type_id', function() {

        //     var loanTypeId = $(this).val();
        //     $('#document_type_data').html('');

        //     if (loanTypeId) {
        //         $.ajax({
        //             url: "{{ route('admin.getLoanTypeDetails') }}",
        //             method: 'post',
        //             data: {
        //                 loan_type_id: loanTypeId
        //             },
        //             success: function(response) {
        //                 if (response.status) {

        //                     if (response.document_content) {
        //                         $('#document_type_data').html(response.document_content);
        //                         // $('#document_container').show();
        //                     } else {
        //                         $('#document_type_data').html('');
        //                         // $('#document_container').hide();
        //                     }

        //                     if (response.formUpload_content) {
        //                         $('#formupload_type_data').html(response
        //                             .formUpload_content);
        //                         // $('#formupload_container').show();

        //                     } else {
        //                         $('#formupload_type_data').html('');
        //                         // $('#formupload_container').hide();
        //                     }


        //                     var document_typs = response.document_typs;
        //                     if (document_typs && Object.keys(document_typs).length > 0) {
        //                         populateSelect($('#document_id'), document_typs);
        //                         $('#document_types_container').show();
        //                         // $('#document_container').show();
        //                     } else {
        //                         $('#document_id').empty();
        //                         $('#document_types_container').hide();
        //                         // $('#document_container').hide();
        //                     }

        //                     var form_uploads_typs = response.form_uploads_typs;
        //                     if (form_uploads_typs && Object.keys(form_uploads_typs).length >
        //                         0) {
        //                         populateSelect($('#form_upload_id'), form_uploads_typs);
        //                         $('#form_upload_container').show();
        //                         // $('#formupload_container').show();

        //                     } else {
        //                         $('#form_upload_id').empty();
        //                         $('#form_upload_container').hide();
        //                         // $('#formupload_container').hide();
        //                     }
        //                     // if (!response.document_content && (!document_typs && !(Object
        //                     // .keys(document_typs).length > 0))) {
        //                     //     $('#document_container').show();
        //                     // }else{
        //                     //     $('#document_container').hide();
        //                     // }

        //                     if (!response.document_content && (!document_typs || Object
        //                             .keys(document_typs).length === 0)) {
        //                         $('#document_container')
        //                             .hide(); // Hide if no document content and no document types
        //                     } else {
        //                         $('#document_container')
        //                             .show(); // Show if either document content or document types are present
        //                     }

        //                     if (!response.formUpload_content && (!form_uploads_typs ||
        //                             Object
        //                             .keys(form_uploads_typs).length === 0)) {
        //                         $('#formupload_container')
        //                             .hide(); // Hide if no document content and no document types
        //                     } else {
        //                         $('#formupload_container')
        //                             .show(); // Show if either document content or document types are present
        //                     }




        //                     // formupload_container
        //                     // formupload_type_data

        //                     // if(response.document_content){
        //                     //     $('#document_type_data').html(response.document_content);
        //                     //     $('#document_container').show();
        //                     // }else{
        //                     //     $('#document_type_data').html('');
        //                     // $('#document_container').hide();
        //                     // }


        //                 } else {
        //                     $('#document_type_data').html('');
        //                     $('#document_container').hide();
        //                     $('#formupload_type_data').html('');
        //                     $('#formupload_container').hide();
        //                     $('#form_upload_id').empty();
        //                     $('#document_id').empty();
        //                     $('#document_types_container').hide();
        //                     $('#form_upload_container').hide();
        //                 }
        //             },
        //             error: function() {
        //                 $('#document_type_data').html('');
        //                 $('#document_container').hide();
        //                 $('#formupload_type_data').html('');
        //                 $('#formupload_container').hide();
        //                 $('#form_upload_id').empty();
        //                 $('#document_id').empty();
        //                 $('#document_types_container').hide();
        //                 $('#form_upload_container').hide();
        //             }
        //         });
        //     } else {
        //         $('#document_type_data').html('');
        //         $('#document_container').hide();
        //         $('#formupload_type_data').html('');
        //         $('#formupload_container').hide();
        //         $('#form_upload_id').empty();
        //         $('#document_id').empty();
        //         $('#document_types_container').hide();
        //         $('#form_upload_container').hide();
        //     }

        // });

        $(document).on('change', '#loan_type_id', function() {
            var loanTypeId = $(this).val();
            clearFieldsAndHideContainers();

            if (loanTypeId) {
                loan_type(loanTypeId)

                // $loading.show();
                // $.ajax({
                //     url: "{{ route('admin.getLoanTypeDetails') }}",
                //     method: 'post',
                //     data: {
                //         loan_type_id: loanTypeId
                //     },
                //     success: function(response) {
                //         if (response.status) {
                //             handleContent(response.document_content, '#document_type_data');
                //             handleContent(response.formUpload_content,
                //                 '#formupload_type_data');

                //             handleSelect(response.document_typs, '#document_id',
                //                 '#document_types_container');
                //             handleSelect(response.form_uploads_typs, '#form_upload_id',
                //                 '#form_upload_container');

                //             toggleContainer(response.document_content, response
                //                 .document_typs, '#document_container');
                //             toggleContainer(response.formUpload_content, response
                //                 .form_uploads_typs, '#formupload_container');
                //         } else {
                //             clearFieldsAndHideContainers();
                //         }

                //         $loading.hide();
                //     },
                //     error: function() {
                //         clearFieldsAndHideContainers();
                //         $loading.hide();
                //     }
                // });
            } else {
                clearFieldsAndHideContainers();
                $loading.hide();
            }
        });

        function handleContent(content, target) {
            if (content) {
                $(target).html(content);
            } else {
                $(target).html('');
            }
        }

        function handleSelect(data, selectElement, container) {
            if (data && Object.keys(data).length > 0) {
                populateSelect($(selectElement), data);
                $(container).show();
            } else {
                $(selectElement).empty();
                $(container).hide();
            }
        }

        function toggleContainer(content, data, container) {
            if (!content && (!data || Object.keys(data).length === 0)) {
                $(container).hide();
            } else {
                $(container).show();
            }
        }

        function populateSelect(selectElement, data) {
            selectElement.empty(); // Clear existing options
            selectElement.append('<option value="">Select</option>'); // Add default option

            $.each(data, function(key, value) {
                selectElement.append('<option value="' + key + '">' + value + '</option>');
            });
        }

        function clearFieldsAndHideContainers() {
            $('#document_type_data').html('');
            $('#formupload_type_data').html('');
            $('#document_id').empty();
            $('#form_upload_id').empty();
            $('#document_types_container').hide();
            $('#form_upload_container').hide();
            $('#document_container').hide();
            $('#formupload_container').hide();
        }


        $(document).on('click', '.add_addition_document', function() {
            let location = $('.addition_document').val() ? $('.addition_document').val() : null;
            let location_array = $('input[name="addition_document[]"]').map(function() {
                return $(this).val();
            }).get();

                let new_location_field = `<div class="col-sm-4 mb-2 d-flex justify-content-between location_row">
                                            <input type="text" name="addition_document[]" class="form-control from_location mr-1" value="${location}">
                                            <button type="button" class="btn btn-danger remove_booking" title="Remove Location">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </button>
                                        </div>`

                if (location && !location_array.includes(location)) {
                    $('.pick_up_point_select').val('').trigger('change');
                    $('#points_values').append(new_location_field);
                    $('.addition_document').val('');  // Clear the input field
                } else {
                    console.log('No Values.');
                }


        });

        $(document).on('click', '.remove_booking', function() {
            $(this).closest('.location_row').remove()
        });






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

        function loan_type(loanTypeId){
            $loading.show();
                $.ajax({
                    url: "{{ route('admin.getLoanTypeDetails') }}",
                    method: 'post',
                    data: {
                        loan_type_id: loanTypeId
                    },
                    success: function(response) {
                        if (response.status) {
                            handleContent(response.document_content, '#document_type_data');
                            handleContent(response.formUpload_content,
                                '#formupload_type_data');

                            handleSelect(response.document_typs, '#document_id',
                                '#document_types_container');
                            handleSelect(response.form_uploads_typs, '#form_upload_id',
                                '#form_upload_container');

                            toggleContainer(response.document_content, response
                                .document_typs, '#document_container');




                            toggleContainer(response.formUpload_content, response
                                .form_uploads_typs, '#formupload_container');

                                 // Handling the pre-selected values from the backend
                                 @if ($isEditable)
                                 @if(count($data->applicantDocument1) > 0)
                                    @php
                                        $docIds = $data->applicantDocument1->pluck('id');
                                    @endphp

                                    var selectedDocumentIds = @json($docIds);
                                    selectedDocumentIds.forEach(function(id) {
                                        $('#document_id option[value="' + id + '"]').prop('selected', true);
                                    });
                                @endif


                                @if(count($data->applicantformUpload2) > 0)
                                    @php
                                        $docIds = $data->applicantformUpload2->pluck('id');
                                    @endphp

                                    var selectedDocumentIds = @json($docIds);
                                    selectedDocumentIds.forEach(function(id) {
                                        $('#form_upload_id option[value="' + id + '"]').prop('selected', true);
                                    });
                                @endif


                                @if(count($data->applicationLoanDocument2) > 0)
                                    @php
                                        $docIds = $data->applicationLoanDocument2->pluck('id')->toArray();
                                    @endphp

                                    var selectedDocumentIds = @json($docIds);
                                    selectedDocumentIds.forEach(function(id) {
                                        $('input[name="document_features[]"][value="' + id + '"]').prop('checked', true);
                                    });
                                @endif


                                @if(count($data->applicationLoanFormUpload2) > 0)
                                    @php
                                        $docIds = $data->applicationLoanFormUpload2->pluck('id')->toArray();
                                    @endphp

                                    var selectedDocumentIds = @json($docIds);
                                    selectedDocumentIds.forEach(function(id) {
                                        $('input[name="uploadForm_features[]"][value="' + id + '"]').prop('checked', true);
                                    });
                                @endif

                                @if(count($data->additionalDocument2) > 0)
                                        @php
                                            $documentsArray = $data->additionalDocument2;
                                        @endphp

                                        var documents = @json($documentsArray);
                                        documents.forEach(function(doc) {
                                            var fieldHtml = `
                                                <div class="col-sm-4 mb-2 d-flex justify-content-between location_row">
                                                    <input type="text" name="addition_document[]" class="form-control from_location mr-1" value="${doc.title}">
                                                    <button type="button" class="btn btn-danger remove_booking" title="Remove Document">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            `;
                                            $('#points_values').append(fieldHtml);
                                        });
                                @endif

                                @endif



                                // $applicationDetail->additionalDocument2()

                                // $applicant->applicationLoanFormUpload2()-

                        } else {
                            clearFieldsAndHideContainers();
                        }

                        $loading.hide();
                    },
                    error: function() {
                        clearFieldsAndHideContainers();
                        $loading.hide();
                    }
                });

        }


        // Ajax for Save New Client
        $('#saveBtn').click(function(e) {
            e.preventDefault();

            $loading.show();

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

                    $loading.hide();
                    $loading.hide();
                },
                error: function(data) {
                    $loading.hide();
                    $loading.hide();

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


        $('#addCustomer').click(function() {
            // ClientModal_ResetErrors()
            $('#customer_id').val('');
            $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
            $('#customerForm').trigger("reset");
            $('#form-modal').modal('show');
        });


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





        */
        ?>
    })


</script>
