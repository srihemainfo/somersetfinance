@extends('layouts.admin')
@section('content')



@if(isset($isdocument) && $isdocument == true )
    <section class="content" style="padding-top: 20px;padding-bottom:20px;">

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" placeholder="Enter Application No">
                    <div class="input-group-prepend">
                        <button class="input-group-text">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-lg-6 col-md-6 text-left">
                        Document Uploading Details
                    </div>
                    <div class="col-lg-6 col-md-6 text-right">
                        Application No: <span>{{ $data->ref_no ?? 'N/A' }}</span>
                    </div>
                </div>

            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" style="width:10%;">S.NO</th>
                            <th scope="col">Name</th>
                            <th scope="col" style="width:10%;" class="text-center">Status</th>
                            <th scope="col" style="width:20%;" class="text-center">Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data->applicationLoanDocument2))
                        @php
                        $si = 1;
                        @endphp
                        @foreach($data->applicationLoanDocument2 as $document)

                        @php
                            // Fetch the related image for this document

                            // $documentImage = $documentImages->where('document_id', $document->id)->where('status', 'rejected');

                            $documentImagesForDocument = $documentImages->where('document_id', $document->id);

                            // dd($documentImagesForDocument  );
                                // Count the images based on their status
                                $pendingCount = $documentImagesForDocument->where('status', 'Pending')->count();
                                $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                            @endphp
                                <tr>
                                    <th scope="row">{{ $si }}</th>
                                    <td>{{ $document->title ?? N/A }}</td>
                                    <td class="text-center">
                                        @if($documentImagesForDocument)
                                        @if($rejectedCount)
                                                <img src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                        @elseif($pendingCount )
                                                <img src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                        @elseif($uploadCount)
                                                <img src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                        @elseif($acceptedCount)
                                                <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                        @else
                                        <img src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                        @endif
                                        @else
                                         <img src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                        <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                    </td> --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success upload_document_modal" data-toggle="modal"
                                            data-target="#upload_document_modal1"
                                                data-application-id="{{ $data->id }}"
                                                data-document-id="{{ $document->id }}"
                                            >Upload</button>
                                    </td>
                                </tr>

                                @php
                                $si++;

                                @endphp


                        @endforeach



                        @endif
                        <tr>
                            <th scope="row">1</th>
                            <td>Statement</td>
                            <td class="text-center">
                                <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success upload_document_modal" data-toggle="modal"
                                    data-target="#upload_document_modal1">Upload</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Payslip</td>
                            <td class="text-center">
                                <img src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success">Upload</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Original Document</td>
                            <td class="text-center">
                                <img src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success">Upload</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Document Upload Modal -->
        <div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" id='form_data'>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Document Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <label for="file_upload" class="labelFile">
                                    <span>
                                        <svg style="left:50%;" xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                            id="Capa_1" version="1.1" width="60px" height="60px">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813
                                                C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834
                                                C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538
                                                c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392
                                                c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094
                                                c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168
                                                c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391
                                                v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill:#010002;"></path>
                                                    </g>
                                                    <g>
                                                        <path d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078
                                                c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045
                                                L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227
                                                c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036
                                                C104.91,91.608,107.183,91.608,108.586,90.201z" style="fill:#010002;">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>drag and drop your file here or click to select a file!</p>
                                </label>
                                <input class="input" name="text[]" id="file_upload" type="file"
                                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                            </div>
                        </div>
                        
                        <div class="row my-4" id="uploaded_images_container">
                            <input type="hidden" name="application_id" id="modal_application_id">
                            <input type="hidden" name="document_id" id="modal_document_id">
                            <!-- Uploaded images will be displayed here -->
                        </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="delete_selected">Delete Selected</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id='submit_button'>Save changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Fullscreen Preview Modal -->
        <div class="modal fade" id="fullscreenPreviewModal" tabindex="-1" role="dialog"
            aria-labelledby="fullscreenPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fullscreenPreviewModalLabel">Fullscreen Preview</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                   
                    <div class="modal-body">
                        <!-- Fullscreen content will be injected here -->
                    </div>
               
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary remark_submit">Save changes</button>
                    </div>
                </div>
            </div>
        </div>



    </section>
    @else
    <div class="card">

        <div class="card-body table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                        <th colspan='4' class='text-center' >NO Data Available</th>

                    </tr>
                </thead>

            </table>
        </div>
    </div>

    @endif

@endsection
@if(isset($isdocument) && $isdocument == true)
@section('scripts')
<script>
    $(document).ready(function() {

        let uploadimage = 0;
        let selectedFiles = [];

        // // Function to add file details to selectedFiles array
        // function addFileToSelectedFiles(file, remark, uploaded_file) {
        //     selectedFiles.push({ file: file, remark: remark , uploaded_file: uploaded_file});
        // }

        function addOrUpdateFileInSelectedFiles(file, remark, uploaded_file) {
            const existingFileIndex = selectedFiles.findIndex(item => item.uploaded_file === uploaded_file);
            if (existingFileIndex !== -1) {
                // Update the existing entry
                selectedFiles[existingFileIndex] = { file: file, remark: remark, uploaded_file: uploaded_file };
            } else {
                // Add a new entry
                selectedFiles.push({ file: file, remark: remark, uploaded_file: uploaded_file });
            }
        }



        $(document).on('click', '[id^=upload_delete_]', function(event) {
            const fileId = $(this).data('file-id');
            const fileDiv = $(`#${fileId}`);
            fileDiv.parent().remove();
        });


        $(document).on('click', '.remark_submit', function(e) {
            e.preventDefault();
            var applicationId =  window['applicationId1'];
            var documentId = window['documentId1'] ;
            var imageId = window['imageId'] ;
            var radio = $('input[name="radio"]').val() ;
            var admin_remark = $('input[name="admin_remark"]').val() ;

            let formData = new FormData();
            formData.append('applicationId', applicationId);
            formData.append('documentId', documentId);
            formData.append('imageId', imageId);
            formData.append('radio', radio);
            formData.append('admin_remark', admin_remark);

            $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.document.remarkUpdatedadmin') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false, // Don't process the files
                    contentType: false,
                    success: function(response) {

                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {

                            Swal.fire('', response.message, 'success');


                         } else{
                            Swal.fire('', response.message, 'error');

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
                window['imageId']= '';


        });

    
        $(document).on('click', '#submit_button', function(e) {
            e.preventDefault();
            var applicationId =  window['applicationId1'];
            var documentId = window['documentId1'] ;
            var selectedFiles1 = selectedFiles ;
            let inputNames = [];
            $('#uploaded_images_container form').each(function() {
                inputNames.push($(this).serialize());
            });

            let formData = new FormData();
            formData.append('applicationId', applicationId);
            formData.append('documentId', documentId);
            formData.append('inputNames', JSON.stringify(inputNames));

            $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.document.remarkUpdated') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false, // Don't process the files
                    contentType: false,
                    success: function(response) {

                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {

                            Swal.fire('', response.message, 'success');


                         } else{
                            Swal.fire('', response.message, 'error');

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
        });


        $(document).on('click', '.upload_document_modal', function() {
            var applicationId = $(this).data('application-id');
            var documentId = $(this).data('document-id');

            window['applicationId1'] = applicationId ;
            window['documentId1'] = documentId ;
        // var files = $('#file_upload').val();
        // var filesInput = $('#file_upload')[0];
        // var files = filesInput.files;

        if (applicationId == undefined || documentId == undefined ) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                const formData = new FormData();
                formData.append('applicationId', applicationId);
                formData.append('documentId', documentId);
                formData.append('documentId', documentId);  
                // formData.append('files', files);
                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.document.upload_image_get') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {

                            // start

                            const container = $('#uploaded_images_container');
                                container.html('') ;
                                var data =  response.data ;
                            data.forEach((document, index) => {
                                const colDivFile = $('<div>', { class: 'col-6 my-2 all_file_data' });
                                    const form1 = $('<form>') ;
                                const fileDiv = $('<div>', { id: `uploaded_file_${index + 1}`, class: 'uploaded_file position-relative' });

                                const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-image-id' : document.id, 'data-file-id': `uploaded_file_${index + 1}` });
                                const hiddenbox = $('<input>', { type: 'hidden', name: 'uploadform',  value:  `uploaded_file_${index + 1}`});
                                fileDiv.append(checkbox);
                                fileDiv.append(hiddenbox);

                                if (document.file_path.endsWith('.pdf')) {
                                    const pdfPlaceholder = '{{ asset('formupload/pdf_preview.png') }}';
                                    const img = $('<img>', { src: pdfPlaceholder, css: { maxWidth: '100%', height: '100%' } });
                                    fileDiv.append(img);
                                    const iframe = $('<iframe>', { src: `{{ asset('formupload') }}/${document.file_path}`, css: { display: 'none' } });
                                    fileDiv.append(iframe);
                                } else {
                                    const img = $('<img>', { src: `{{ asset('formupload') }}/${document.file_path}`, css: { maxWidth: '100%', height: '100%' } });
                                    fileDiv.append(img);
                                }

                                form1.append(fileDiv);
                                colDivFile.append(form1);

                                const remarkDiv = $('<div>', { id: `upload_remark_${index + 1}`, class: 'upload_remark' });

                                const form = $('<form>');
                                const formGroup = $('<div>', { class: 'form-group' });

                                const inputDiv = $('<div>');
                                // const input = $('<input>', { type: 'text', name: 'imageData[]', class: 'form-control form-control-sm', id: `input_remark_${index + 1}`, placeholder: 'Remark', value: document.remark, readonly: true });
                                const input = $('<input>', { type: 'text', name:'input_remark', class: 'form-control form-control-sm', id: `input_remark_${index + 1}`, placeholder: 'Remark', value: document.remark});
                                const input2 = $('<input>', { type: 'hidden', name: 'imageData', value: document.id});

                                inputDiv.append(input);
                                inputDiv.append(input2);
                                formGroup.append(inputDiv);
                                // form.append(formGroup);
                                remarkDiv.append(formGroup);

                                const buttonDiv = $('<div>', { id: `upload_file_button_${index + 1}`, class: `d-flex justify-content-center upload_file_button` });

                                const buttonNames = ['Preview', 'Change', 'Delete'];
                                buttonNames.forEach(name => {
                                    let iconClass;
                                    switch (name) {
                                        case 'Preview':
                                            iconClass = 'fas fa-eye';
                                            btnClass = 'btn btn-outline-primary btn-sm';
                                            break;
                                        case 'Change':
                                            iconClass = 'far fa-edit';
                                            btnClass = 'btn btn-outline-warning btn-sm';
                                            break;
                                        case 'Delete':
                                            iconClass = 'fas fa-trash';
                                            btnClass = 'btn btn-outline-danger btn-sm';
                                            break;
                                    }
                                    const button = $('<button>', {
                                        id: `upload_${name.toLowerCase()}_${index + 1}`,
                                        html: `<i class="${iconClass}"></i>`,
                                        class: `${btnClass}`,
                                        title: `${name}`,
                                        'data-file-id': `uploaded_file_${index + 1}`,
                                        'data-image-id' : document.id,
                                        'data-remark-id' : document.admin_remark,
                                        'data-file-type': document.file_path.split('.').pop()
                                    });
                                    buttonDiv.append(button);
                                });

                                fileDiv.append(buttonDiv);
                                fileDiv.append(remarkDiv);
                                container.append(colDivFile);

                                addOrUpdateFileInSelectedFiles( `{{ asset('formupload') }}/${document.file_path}`, '', `uploaded_file_${index + 1}` );
                            });


                                    $('#modal_application_id').val(applicationId);
                                    $('#modal_document_id').val(documentId);
                                    $('#upload_document_modal').modal('show') ;

                                        $url = response.url;

                                        if($url != ''){
                                            // window.location.href = $url ;

                                        }else{

                                            Swal.fire('', 'No any upload option', 'error');
                                        }
                                        
                        } 
                        else {
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

      
    });


        $('#file_upload').on('change', function(event) {
            const files = event.target.files;
            const container = $('#uploaded_images_container');
            const currentImageCount = container.children().length;
             uploadimage =  uploadimage ;
             // Each file set takes One divs
             var applicationId = window['applicationId1'];
             var documentId = window['documentId1'];
             const formData = new FormData();
             formData.append('applicationId', applicationId);
             formData.append('documentId', documentId);

            // Display selected files
            for (let i = 0; i < files.length; i++) {

                const file = files[i];
                if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
                    
                    // formData.append('documentId', documentId);  
                    formData.append('file', file);  
                    // formData.append('files', files);
                    $('.secondLoader').show()

                    $.ajax({
                    url: "{{ route('admin.document.upload_image_get') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if(response.data){

                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    // Create new structure for each file
                                    const colDivFile = $('<div>', { class: 'col-6 my-2 all_file_data' });
                                    const form1 = $('<form>') ;
                                    const fileDiv = $('<div>', { id: `uploaded_file_${currentImageCount + i + 1}`, class: 'uploaded_file position-relative' });

                                    const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-image-id' : response.data.id,  'data-file-id': `uploaded_file_${currentImageCount + i + 1}` });
                                    const hiddenbox = $('<input>', { type: 'hidden', name: 'uploadform',  value:  `uploaded_file_${currentImageCount + i + 1}`});
                                    const input2 = $('<input>', { type: 'hidden', name: 'imageData', value: response.data.id});
                                    fileDiv.append(checkbox);
                                    fileDiv.append(hiddenbox);
                                    fileDiv.append(input2);
                                    // fileDiv.append(checkbox);

                                    if (file.type.startsWith('image/')) {
                                        const img = $('<img>', { src: e.target.result, css: { maxWidth: '100%', height: '100%' } });
                                        fileDiv.append(img);
                                    } else if (file.type === 'application/pdf') {
                                        // Use a placeholder image for PDF preview
                                        const pdfPlaceholder = '{{asset('formupload/pdf_preview.png')}}'; // Replace with the path to your placeholder image
                                        const img = $('<img>', { src: pdfPlaceholder, css: { maxWidth: '100%', height: '100%' } });
                                        fileDiv.append(img);
                                        const iframe = $('<iframe>', { src: e.target.result, css: { display: 'none' } });
                                        fileDiv.append(iframe);
                                    }

                                    
                                    form1.append(fileDiv);
                                    colDivFile.append(form1);

                                    const remarkDiv = $('<div>', { id: `upload_remark_${currentImageCount + i + 1}`, class: 'upload_remark' });

                                    const form = $('<form>');
                                    const formGroup = $('<div>', { class: 'form-group' });

                                    const inputDiv = $('<div>');
                                    const input = $('<input>', { type: 'text', name:'input_remark', class: 'form-control form-control-sm', id: `input_remark_${currentImageCount + i + 1}`, placeholder: 'Remark' });

                                    inputDiv.append(input);
                                    formGroup.append(inputDiv);
                                    // form.append(formGroup);
                                    remarkDiv.append(formGroup);

                                    const buttonDiv = $('<div>', { id: `upload_file_button_${currentImageCount + i + 1}`, class: `d-flex justify-content-center upload_file_button` });

                                    const buttonNames = ['Preview', 'Change', 'Delete'];
                                    buttonNames.forEach(name => {
                                        let iconClass;
                                        switch (name) {
                                            case 'Preview':
                                                iconClass = 'fas fa-eye';
                                                btnClass = 'btn btn-outline-primary btn-sm';
                                                break;
                                            case 'Change':
                                                iconClass = 'far fa-edit';
                                                btnClass = 'btn btn-outline-warning btn-sm';
                                                break;
                                            case 'Delete':
                                                iconClass = 'fas fa-trash';
                                                btnClass = 'btn btn-outline-danger btn-sm';
                                                break;
                                        }
                                        const button = $('<button>', {
                                            id: `upload_${name.toLowerCase()}_${currentImageCount + i + 1}`,
                                            html: `<i class="${iconClass}"></i>`,
                                            class: `${btnClass}`,
                                            title: `${name}`,
                                            'data-file-id': `uploaded_file_${currentImageCount + i + 1}`,
                                            'data-image-id' : response.data.id, 
                                            'data-remark-id' : response.data.admin_remark, 
                                            'data-file-type': file.type
                                        });
                                        buttonDiv.append(button);
                                    });

                                    fileDiv.append(buttonDiv);
                                    fileDiv.append(remarkDiv);

                                    container.append(colDivFile);
                                }
                                reader.readAsDataURL(file);
                                addOrUpdateFileInSelectedFiles(file, '', `uploaded_file_${currentImageCount + i + 1}` );
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
                });

            }


               
              

                uploadimage++;
            }

            // Reset the input value to allow re-upload of the same file
            $(this).val('');
        });

        // Function to create new added content dynamically
        function createNewAddedContent(index,remark) {
            console.log(remark)
            const newContent = $('<div>', { class: 'container-fluid', id: `new_added_content_${index}` });

            const pane = $('<div>', { class: 'pane m-3' });
            const idkLabel = $('<label>', { class: 'label' }).html(`<span>Idk</span><input id="idk_${index}" class="input idk" name="radio" type="radio" checked="checked" value="">`);
            const yesLabel = $('<label>', { class: 'label' }).html(`<span>Yes</span><input id="yes_${index}" class="input yes" name="radio" type="radio" value="Verified">`);
            const noLabel = $('<label>', { class: 'label' }).html(`<span>No</span><input id="no_${index}" class="input no" name="radio" type="radio" value="Canceled>`);
            pane.append(idkLabel, yesLabel, noLabel, $('<span>', { class: 'selection' }));
            const extraInput = $('<div>', { class: 'extra-input m-3', id: `extra-input_${index}` });
            const extraText = $('<input>', { type: 'text', name: 'admin_remark', id: `extra-text_${index}`, class: 'form-control', placeholder: 'Reason', value:remark });
            extraInput.append(extraText);

            newContent.append(pane, extraInput);
            return newContent;
        }

        // Fullscreen Preview
        $(document).on('click', '[id^=upload_preview_]', function(event) {
            event.preventDefault();
            const fileId = $(this).data('file-id');
            const fileType = $(this).data('file-type');
            const remark = $(this).data('remark-type');
            const imageId = $(this).data('image-id');
            const fileDiv = $(`#${fileId}`);
            const modalBody = $('#fullscreenPreviewModal .modal-body');
            modalBody.empty(); // Clear previous content
            window['imageId'] = imageId ;
            
            const index = fileId.split('_').pop(); // Get the index from the fileId

            if (fileType.startsWith('image/')) {
                const imageSrc = fileDiv.find('img').attr('src');
                const img = $('<img>', { src: imageSrc, css: { width: '100%', height: 'auto' } });
                modalBody.append(img);
            } else if (fileType === 'application/pdf') {
                const pdfSrc = fileDiv.find('iframe').attr('src');
                const iframe = $('<iframe>', { src: pdfSrc, css: { width: '100%', height: '100%' } });
                modalBody.append(iframe);

            }else if (fileType === 'pdf') {
                const pdfSrc = fileDiv.find('iframe').attr('src');
                const iframe = $('<iframe>', { src: pdfSrc, css: { width: '100%', height: '100%' } });
                modalBody.append(iframe);

            }else  if (['jpg', 'jpeg', 'png'].includes(fileType)) {
                const imageSrc = fileDiv.find('img').attr('src');
                const img = $('<img>', { src: imageSrc, css: { width: '100%', height: 'auto' } });
                modalBody.append(img);
            } 




            // Append dynamically created content
            modalBody.append(createNewAddedContent(index,remark));

            $('#fullscreenPreviewModal').modal('show');
        });

        // // Change File
        // $(document).on('click', '[id^=upload_change_]', function(event) {
        //     const fileId = $(this).data('file-id');
        //     const input = $('<input>', { type: 'file', accept: 'image/*,application/pdf' });
        //     input.on('change', function(e) {
        //         const file = e.target.files[0];
        //         if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
        //             const reader = new FileReader();
        //             reader.onload = function(e) {

        //                 const fileDiv = $(`#${fileId}`);
        //                 fileDiv.empty(); // Clear previous content
        //                 const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-file-id': fileId });
        //                 fileDiv.append(checkbox);
        //                 if (file.type.startsWith('image/')) {
        //                     const img = $('<img>', { src: e.target.result, css: { maxWidth: '100%', height: '100%' } });
        //                     fileDiv.append(img);
        //                 } else if (file.type === 'application/pdf') {
        //                     const iframe = $('<iframe>', { src: e.target.result, css: { width: '100%', height: '100%' } });
        //                     fileDiv.append(iframe);
        //                 }
        //             }
        //             reader.readAsDataURL(file);
        //         }
        //     });
        //     input.click();
        // });

        $(document).on('click', '[id^=upload_change_]', function(event) {
            event.preventDefault();
            const fileId = $(this).data('file-id');
            const imageId = $(this).data('image-id');
            const input = $('<input>', { type: 'file', accept: 'image/*,application/pdf' });

            var applicationId = window['applicationId1'];
            var documentId = window['documentId1'];
            const formData = new FormData();
            formData.append('applicationId', applicationId);
            formData.append('documentId', documentId);
            formData.append('imageId', imageId);
    
            input.on('change', function(e) {


            const file = e.target.files[0];
            if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {

                 // formData.append('documentId', documentId);  
                 formData.append('file', file);  
                    // formData.append('files', files);
                    $('.secondLoader').show()

                    $.ajax({
                    url: "{{ route('admin.document.upload_image_get') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if(response.data){
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileDiv = $(`#${fileId}`);
                    fileDiv.empty(); // Clear previous content

                    // Re-append the checkbox
                    const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-image-id': imageId, 'data-file-id': fileId });
                    fileDiv.append(checkbox);

                    // Add the new content
                    if (file.type.startsWith('image/')) {
                        const img = $('<img>', { src: e.target.result, css: { maxWidth: '100%', height: '100%' } });
                        fileDiv.append(img);
                    } else if (file.type === 'application/pdf') {
                        // Use a placeholder image for PDF preview
                        const pdfPlaceholder = '{{asset('formupload/pdf_preview.png')}}'; // Replace with the path to your placeholder image
                        const img = $('<img>', { src: pdfPlaceholder, css: { maxWidth: '100%', height: '100%' } });
                        fileDiv.append(img);
                        const iframe = $('<iframe>', { src: e.target.result, css: { display: 'none' } });
                        fileDiv.append(iframe);
                    }

                    const remarkDiv = $('<div>', { id: `upload_remark_${fileId.split('_').pop()}`, class: 'upload_remark' });

                    const form = $('<form>');
                    const formGroup = $('<div>', { class: 'form-group' });

                    const inputDiv = $('<div>');
                    const input = $('<input>', { type: 'text', name : 'input_remark', class: 'form-control form-control-sm', id: `input_remark_${fileId.split('_').pop()}`, placeholder: 'Remark' });

                    inputDiv.append(input);
                    formGroup.append(inputDiv);
                    // form.append(formGroup);
                    remarkDiv.append(formGroup);

                    // Re-append the buttons
                    const buttonDiv = $('<div>', { class: `d-flex justify-content-center upload_file_button` });

                    const buttonNames = ['Preview', 'Change', 'Delete'];
                    buttonNames.forEach(name => {
                        let iconClass;
                        let btnClass;
                        switch (name) {
                            case 'Preview':
                                iconClass = 'fas fa-eye';
                                btnClass = 'btn btn-outline-primary btn-sm';
                                break;
                            case 'Change':
                                iconClass = 'far fa-edit';
                                btnClass = 'btn btn-outline-warning btn-sm';
                                break;
                            case 'Delete':
                                iconClass = 'fas fa-trash';
                                btnClass = 'btn btn-outline-danger btn-sm';
                                break;
                        }
                        const button = $('<button>', {
                            id: `upload_${name.toLowerCase()}_${fileId.split('_').pop()}`,
                            html: `<i class="${iconClass}"></i>`,
                            class: `${btnClass}`,
                            title: `${name}`,
                            'data-file-id': fileId,
                            'data-image-id': imageId,
                            'data-remark-id': response.data.admin_remark,
                            'data-file-type': file.type
                        });
                        buttonDiv.append(button);
                    });

                    fileDiv.append(buttonDiv);
                    fileDiv.append(remarkDiv);
                }
                reader.readAsDataURL(file);
                addOrUpdateFileInSelectedFiles(file, '', `upload_remark_${fileId.split('_').pop()}` );

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
                });
            }
        });
        input.click();
            });




        // Delete File
        $(document).on('click', '[id^=upload_delete_]', function(event) {
             event.preventDefault();

             const image_id = $(this).data('image-id');
            var applicationId = window['applicationId1'];
            var documentId = window['documentId1'];
            const formData = new FormData();
                formData.append('applicationId', applicationId);
                formData.append('documentId', documentId);
                // formData.append('documentId', documentId);  
                formData.append('image_id', image_id);  
                // formData.append('files', files);
                $('.secondLoader').show()

                $.ajax({
                    url: "{{ route('admin.document.upload_image_get_delete') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if(response.status){
                            const fileId = $(this).data('file-id');
                            const fileDiv = $(`#${fileId}`);
                            fileDiv.parent().remove();
                        }

                     },
                    error: function() {

                    } });


        });

        // Delete Selected Files
        // $('#delete_selected').on('click', function(event) {
        //     event.preventDefault();
        //     $('.delete-checkbox:checked').each(function() {

        //     const image_id = $(this).data('image-id');
        //     var applicationId = window['applicationId1'];
        //     var documentId = window['documentId1'];
        //     const formData = new FormData();
        //         formData.append('applicationId', applicationId);
        //         formData.append('documentId', documentId);
        //         // formData.append('documentId', documentId);  
        //         formData.append('image_id', image_id);  
        //         // formData.append('files', files);
        //         $('.secondLoader').show()

        //         $.ajax({
        //             url: "{{ route('admin.document.upload_image_get_delete') }}",
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             success: function(response) {

        //                 if(response.status){
        //                     const fileId = $(this).data('file-id');
        //                     const fileDiv = $(`#${fileId}`);
        //                     fileDiv.parent().remove();
        //                 }

        //              },
        //             error: function() {

        //             } });

        //         // const fileId = $(this).data('file-id');
        //         // const fileDiv = $(`#${fileId}`);
        //         // fileDiv.parent().remove();
        //     });

        //     // Hide the delete selected button if no checkboxes are left
        //     if ($('.delete-checkbox:checked').length === 0) {
        //         $('#delete_selected').hide();
        //     }
        // });


        $('#delete_selected').on('click', function(event) {
    event.preventDefault();
    
    var $checkedBoxes = $('.delete-checkbox:checked');
    
    if ($checkedBoxes.length === 0) {
        Swal.fire('', 'Please select at least one item to delete.', 'error');
        return;
    }

    var formData = new FormData();

    $checkedBoxes.each(function() {
        var image_id = $(this).data('image-id');
        formData.append('image_ids[]', image_id); // Append multiple image_ids
    });

    var applicationId = window['applicationId1'];
    var documentId = window['documentId1'];

    formData.append('applicationId', applicationId);
    formData.append('documentId', documentId);

    $('.secondLoader').show();

    $.ajax({
        url: "{{ route('admin.document.upload_image_get_deletes') }}",
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('.secondLoader').hide(); // Hide loader
            
            if (response.status) {
                $checkedBoxes.each(function() {
                    var image_id = $(this).data('file-id');
                    $(`#${image_id}`).remove(); // Assuming the image ID is used as the ID for the image container
                });
                Swal.fire('', response.message, response.status);
                $('#delete_selected').hide();
            } else {

                Swal.fire('', response.message, 'error');
            }
        },
        error: function() {
            $('.secondLoader').hide(); // Hide loader

            Swal.fire('', 'An error occurred. Please try again.', 'error');
        }
    });

    // Hide the delete selected button if no checkboxes are left
    if ($('.delete-checkbox:checked').length === 0) {
        $('#delete_selected').hide();
    }
});


        // Show/hide delete selected button based on checkbox state
        $(document).on('change', '.delete-checkbox', function() {
            if ($('.delete-checkbox:checked').length > 0) {
                $('#delete_selected').show();
            } else {
                $('#delete_selected').hide();
            }
        });

        // Initially hide the delete selected button
        $('#delete_selected').hide();

        $('#fullscreenPreviewModal').on('hidden.bs.modal', function () {
          // Ensure the upload document modal scroll is enabled after closing the fullscreen modal
          $('#upload_document_modal').css('overflow-y', 'auto');
        });

        // Optionally, you can also reset the scroll position
        $('#fullscreenPreviewModal').on('hidden.bs.modal', function () {
          $('#upload_document_modal .modal-body').scrollTop(0);
        });

    });
    </script>
@endsection
@endif
