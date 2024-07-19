@extends('layouts.admin')
@section('content')


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
                    Application No: <span>APPL00451256</span>
                </div>
            </div>

        </div>

        <div class="card-body">
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
                    <tr>
                        <th scope="row">1</th>
                        <td>Statement</td>
                        <td class="text-center">
                            <img src="{{asset('formupload/file_success.png')}}" class="document_status_img">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#upload_document_modal">Upload</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Payslip</td>
                        <td class="text-center">
                            <img src="{{asset('formupload/file_uploaded.png')}}" class="document_status_img">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success">Upload</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Original Document</td>
                        <td class="text-center">
                            <img src="{{asset('formupload/file_cancel.png')}}" class="document_status_img">
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
                        <p>drag and drop your file here or click to select a file!</p>
                    </label>
                    <input class="input" name="text" id="file_upload" type="file" multiple>
                </div>
            </div>
            <div class="row my-4" id="uploaded_images_container">
                <!-- Uploaded images will be displayed here -->
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="delete_selected">Delete Selected</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Fullscreen Preview Modal -->
<div class="modal fade" id="fullscreenPreviewModal" tabindex="-1" role="dialog" aria-labelledby="fullscreenPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullscreenPreviewModalLabel">Fullscreen Preview</h5>
                <button type="button" class="close" data-toggle="modal" data-target="#upload_document_modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Fullscreen content will be injected here -->
            </div>
        </div>
    </div>
</div>

                

</section>

<script>

$(document).ready(function() {
    $('#file_upload').on('change', function(event) {
        const files = event.target.files;
        const container = $('#uploaded_images_container');
        const currentImageCount = container.children().length / 2; // Each file set takes two divs

        // Display selected files
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create new structure for each file
                    const colDivFile = $('<div>', { class: 'col-6' });
                    const fileDiv = $('<div>', { id: `uploaded_file_${currentImageCount + i + 1}` });

                    const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-file-id': `uploaded_file_${currentImageCount + i + 1}` });
                    fileDiv.append(checkbox);

                    if (file.type.startsWith('image/')) {
                        const img = $('<img>', { src: e.target.result, css: { maxWidth: '100%', height: 'auto' } });
                        fileDiv.append(img);
                    } else if (file.type === 'application/pdf') {
                        const iframe = $('<iframe>', { src: e.target.result, css: { width: '100%', height: '500px' } });
                        fileDiv.append(iframe);
                    }  

                    colDivFile.append(fileDiv);

                    const colDivRemark = $('<div>', { class: 'col-6 d-flex flex-column justify-content-center' });
                    const remarkDiv = $('<div>', { id: `upload_remark_${currentImageCount + i + 1}` });

                    const form = $('<form>');
                    const formGroup = $('<div>', { class: 'form-group row' });

                    const label = $('<label>', { for: `input_remark_${currentImageCount + i + 1}`, class: 'col-sm-4 col-form-label', text: 'Remarks' });
                    const inputDiv = $('<div>', { class: 'col-sm-8' });
                    const input = $('<input>', { type: 'text', class: 'form-control', id: `input_remark_${currentImageCount + i + 1}` });

                    inputDiv.append(input);
                    formGroup.append(label);
                    formGroup.append(inputDiv);
                    form.append(formGroup);
                    remarkDiv.append(form);

                    const buttonDiv = $('<div>', { id: `upload_file_button_${currentImageCount + i + 1}`, class: `d-flex justify-content-around` });

                    const buttonNames = ['Preview', 'Change', 'Delete'];
                    buttonNames.forEach(name => {
                        let iconClass;
                        switch (name) {
                            case 'Preview':
                                iconClass = 'fas fa-eye';
                                btnClass = 'btn btn-success';
                                dismissM = 'modal';
                                break;
                            case 'Change':
                                iconClass = 'far fa-edit';
                                btnClass = 'btn btn-warning';
                                dismissM = 'modal_rough';
                                break;
                            case 'Delete':
                                iconClass = 'fas fa-trash';
                                btnClass = 'btn btn-danger';
                                dismissM = 'modal_rough';
                                break;
                        }
                        const button = $('<button>', {
                            id: `upload_${name.toLowerCase()}_${currentImageCount + i + 1}`,
                            html: `<i class="${iconClass}"></i>`,
                            class: `${btnClass}`,
                            'data-file-id': `uploaded_file_${currentImageCount + i + 1}`,
                            'data-dismiss': `${dismissM}`,
                            'data-file-type': file.type
                        });
                        buttonDiv.append(button);
                    });

                    colDivRemark.append(remarkDiv);
                    colDivRemark.append(buttonDiv);

                    container.append(colDivFile);
                    container.append(colDivRemark);
                }
                reader.readAsDataURL(file);
            }
        }

        // Reset the input value to allow re-upload of the same file
        $(this).val('');
    });

    // Fullscreen Preview
    $(document).on('click', '[id^=upload_preview_]', function(event) {
        const fileId = $(this).data('file-id');
        const fileType = $(this).data('file-type');
        const fileDiv = $(`#${fileId}`);
        const modalBody = $('#fullscreenPreviewModal .modal-body');
        modalBody.empty(); // Clear previous content
        if (fileType.startsWith('image/')) {
            const imageSrc = fileDiv.find('img').attr('src');
            const img = $('<img>', { src: imageSrc, css: { width: '100%', height: 'auto' } });
            modalBody.append(img);
        } else if (fileType === 'application/pdf') {
            const pdfSrc = fileDiv.find('iframe').attr('src');
            const iframe = $('<iframe>', { src: pdfSrc, css: { width: '100%', height: '100%' } });
            modalBody.append(iframe);
        }
        $('#fullscreenPreviewModal').modal('show');
    });

    // Reset modal scrolling on close
    $('#fullscreenPreviewModal').on('hidden.bs.modal', function() {
        const modalBackdrop = $('.modal-backdrop');
        if (modalBackdrop) {
            modalBackdrop.remove();
        }
        $('body').css('overflow', ''); // Reset overflow property
    });

    // Change File
    $(document).on('click', '[id^=upload_change_]', function(event) {
        const fileId = $(this).data('file-id');
        const input = $('<input>', { type: 'file', accept: 'image/*,application/pdf' });
        input.on('change', function(e) {
            const file = e.target.files[0];
            if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileDiv = $(`#${fileId}`);
                    fileDiv.empty(); // Clear previous content
                    const checkbox = $('<input>', { type: 'checkbox', class: 'delete-checkbox', 'data-file-id': fileId });
                    fileDiv.append(checkbox);
                    if (file.type.startsWith('image/')) {
                        const img = $('<img>', { src: e.target.result, css: { maxWidth: '100%', height: 'auto' } });
                        fileDiv.append(img);
                    } else if (file.type === 'application/pdf') {
                        const iframe = $('<iframe>', { src: e.target.result, css: { width: '100%', height: '500px' } });
                        fileDiv.append(iframe);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        input.click();
    });

    // Delete File
    $(document).on('click', '[id^=upload_delete_]', function(event) {
        const fileId = $(this).data('file-id');
        const fileDiv = $(`#${fileId}`);
        const remarkDiv = fileDiv.parent().next();
        fileDiv.parent().remove();
        remarkDiv.remove();
    });

    // Delete Selected Files
    $('#delete_selected').on('click', function() {
        $('.delete-checkbox:checked').each(function() {
            const fileId = $(this).data('file-id');
            const fileDiv = $(`#${fileId}`);
            const remarkDiv = fileDiv.parent().next();
            fileDiv.parent().remove();
            remarkDiv.remove();
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
});


</script>
 

@endsection
@section('scripts')


@endsection



