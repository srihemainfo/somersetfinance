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
                 Swal.fire("Error", "Loan Type Required", "error");
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
                     Swal.fire("Error", "Co-applicant name is required", "error");
                }

                // Validate co-applicant email
                if (coAppEmail === '') {
                    $(this).find('.co_applicants_email').after(
                        '<p class="text-danger co-applicant-error">Co-applicant email is required.</p>'
                    );
                    isValid = false;
                     Swal.fire("Error", "Co-applicant email is required.", "error");
                } else {
                    // Additional email format validation if needed
                }

                // Validate co-applicant phone
                if (coAppPhone === '') {
                    $(this).find('.co_applicants_phone').after(
                        '<p class="text-danger co-applicant-error">Co-applicant phone is required.</p>'
                    );
                    isValid = false;
                     Swal.fire("Error", "Co-applicant phone is required.", "error");
                }

                // Validate co-applicant address
                if (coAppAddress === '') {
                    $(this).find('.co_applicants_address').after(
                        '<p class="text-danger co-applicant-error">Co-applicant address is required.</p>'
                    );
                    isValid = false;
                     Swal.fire("Error", "Co-applicant address is required.", "error");
                }
            });
            
        //   var  $fileError = false;
        //     $('#points_form_values .location_row').each(function() {
        //         let fileInput = $(this).find('input[type="file"]');
        //         console.log(fileInput.val());
        //         if (!fileInput.val()) {
        //             console.log($(this).find('input[type="file"]'));
        //             isValid = false;
        //              $fileError = true;
        //             fileInput.css('border', '2px solid red'); // Highlight the file input
        //             fileInput.closest('.location_row').append('<span class="text-danger">File is required.</span>'); // Show error message
        //         } else {
        //             console.log('fail');
        //             fileInput.css('border', ''); // Remove highlight if file is selected
        //             fileInput.closest('.location_row').find('.text-danger').remove(); // Remove any existing error message
                     
        //         }
        //     });
            
        //     if($fileError ){
                
        //         Swal.fire("Error", "Additional Form File(s) is required.", "error");
        //     }
        
        
        // $('#points_form_values .location_row').each(function() {
        //     let fileInput = $(this).find('input[type="file"]');
        //     if (!fileInput.hasClass('existing-file') && !fileInput.val()) {
        //         isValid = false;
        //         fileInput.css('border', '2px solid red'); // Highlight the file input
        //         if (!fileInput.closest('.location_row').find('.text-danger').length) {
        //             fileInput.closest('.location_row').append('<span class="text-danger">File is required.</span>'); // Show error message
        //         }
        //     } else {
        //         fileInput.css('border', ''); // Remove highlight if file is selected
        //         fileInput.closest('.location_row').find('.text-danger').remove(); // Remove any existing error message
        //     }
        // });
        
        $('#points_form_values .location_row').each(function() {
        let fileInput = $(this).find('input[type="file"]');
        if (!fileInput.hasClass('existing-file') && !fileInput.val()) {
            isValid = false;
            fileInput.css('border', '2px solid red'); // Highlight the file input
            if (!fileInput.closest('.location_row').find('.text-danger').length) {
                fileInput.closest('.location_row').append('<span class="text-danger">File is required.</span>'); // Show error message
            }
        } else {
            fileInput.css('border', ''); // Remove highlight if file is selected
            fileInput.closest('.location_row').find('.text-danger').remove(); // Remove any existing error message
        }
    });
            
            



            if (isValid) {
                // If all validations pass, serialize the form data
                // var formData = $('#bookingForm').serialize();
                
                  var formData = new FormData(this);



            //   let  $caseid = @if($isEditable) {{ $data->id ?? false }} @endif ;
            //     formData.append('caseid',  $caseid )

                $loading.show();
                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.application.store') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
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
        
        
        let documentCounter = 0;
        const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB
        
        $(document).on('click', '.add_addition_form_document', function() {
            let location = $('.addition_form_document').val() ? $('.addition_form_document').val().toLowerCase().trim() : null;
            let location_array = $('input[name^="addition_form_document"]').map(function() {
                return $(this).val().toLowerCase().trim();
            }).get();
        
            if (location && !location_array.includes(location)) {
                documentCounter++;
                let new_location_field = `
                    <div class="col-sm-4 mb-2 location_row">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text" name="addition_form_document[]" class="form-control mr-1" value="${location}" readonly style="width:65%;">
                            <label for="file-upload_${location}" class="custom-file-upload my-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" class="upload-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#ffffff" stroke-width="2"></path>
                                    <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <input id="file-upload_${location}" type="file" style="display: none;" name="addition_form_document_file[]" class="form-control-file mr-1 file-input" accept=".pdf, .doc, .docx, .png, .jpg, .jpeg">
                            </label>
                            <button type="button" class="btn btn-danger remove_booking" title="Remove Location">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>`;
                
                $('#points_form_values').append(new_location_field);
                $('.addition_form_document').val('');  // Clear the input field
            } else {
                console.log('No Values or Duplicate Value.');
            }
        });
        
        $(document).on('change', '.file-input', function() {
            let file = this.files[0];
            if (file && file.size > MAX_FILE_SIZE) {
                alert('File size exceeds 10 MB. Please choose a smaller file.');
                $(this).val(''); // Clear the file input
            }
        });
        
    //     $(document).on('click', '.remove_booking', function() {
    //         $(this).closest('.location_row').remove();
    // });

        
        

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
                                
                                @if(count($data->additionFormUploads) > 0)
                                
                                    @php
                                        $documentsArray = $data->additionFormUploads;
                                    @endphp
                                
                                 var documents = @json($documentsArray);
                                documents.forEach(function(doc) {
                                
                                    documentCounter++;
                                    let new_location_field = `
                                        <div class="col-sm-4 mb-2 location_row">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="text" name="addition_form_document[]" class="form-control mr-1" value="${doc.title}" readonly style="width:65%;">
                                                <label for="file-upload_${documentCounter}" class="custom-file-upload my-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" class="upload-icon">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#ffffff" stroke-width="2"></path>
                                                        <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <input id="file-upload_${documentCounter}" type="file" style="display: none;" name="addition_form_document_file[]" class="form-control-file mr-1 file-input existing-file" accept=".pdf, .doc, .docx, .png, .jpg, .jpeg">
                                                </label>
                                                <button type="button" class="btn btn-danger remove_booking" title="Remove Location">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>`;
                                
                                $('#points_form_values').append(new_location_field);
                                        });
                                
                                
                                // console.log(@json($data->additionFormUploads));
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
