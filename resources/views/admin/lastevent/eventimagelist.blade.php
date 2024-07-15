<div id="service_content_image_upload_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 75%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create Service Content</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background: transparent;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">
            <form method="post" id="service_content_image_upload_form" enctype="multipart/form-data">
            <div class="md-form mb-5">
                <label for="">Content Image : </label>
                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.web" multiple name="service_content_image[]" id="service_content_image">
                <input type="hidden" name="imageupload_service_article_id" id="imageupload_service_article_id">
                <input type="hidden" name="imageupload_service_content_id" id="imageupload_service_content_id">
                <p id="service_content_image_error" style="color: red;font-size: 12px;margin-left: 2px;"></p>
              </div>
            </form>
            <div class="md-form mb-5">
            <label for="">Uploaded Images : </label>
                <div class="row showservicecontentimages"></div>
            </div>
        </div>
        <div class="modal-footer mx-auto">
            <button class="btn btn-secondary" onclick="UploadServiceContentImages()">Submit</button>
        </div>
      </div>
    </div> <!-- modal-bialog .// -->
  </div> <!-- modal.// -->
