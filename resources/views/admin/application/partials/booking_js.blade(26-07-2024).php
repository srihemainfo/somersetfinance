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

        $(document).on('change', '#loan_type_id', function() {
            var loanTypeId = $(this).val();
            clearFieldsAndHideContainers();

            if (loanTypeId) {
                loan_type(loanTypeId)

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

    })
    
       
         function validateInput(input) {
            input.value = input.value
                .replace(/[^0-9.]/g, '')  // Remove any non-numeric and non-period characters
                .replace(/(\..*)\./g, '$1');  // Allow only one period
        }



</script>
