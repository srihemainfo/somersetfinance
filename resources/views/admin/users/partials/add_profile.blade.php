<div id="profile_form_modal" class="modal fixed-left fade" tabindex="-1" role="dialog" style="left:auto !important; width:30%;">
    <div class="modal-dialog modal-dialog-aside" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="profileForm" name="profileForm">
                <div class="row">
                     <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_name" class="col-form-label">Name<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="profile_name" id="profile_name">
                            <p class="text-danger invalid-profile_name"></p>
                        </div>
                    </div>
                    
                     <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_phone" class="col-form-label">Phone<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="profile_phone" id="profile_phone" oninput="validateInput(this)">
                            <p class="text-danger invalid-profile_phone"></p>
                        </div>
                    </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_company_address_1" class="col-form-label">Company Address Line1<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="profile_company_address_1" id="profile_company_address_1">
                            <p class="text-danger invalid-profile_company_address_1"></p>
                        </div>
                    </div>
                    
                     <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_company_address_2" class="col-form-label">Company Address Line2</label>
                            <input type="text" class="form-control" name="profile_company_address_2" id="profile_company_address_2">
                            <p class="text-danger invalid-profile_company_address_2"></p>
                        </div>
                    </div>
                    
                     <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_company_phone" class="col-form-label">Company Phone</span></label>
                            <input type="text" class="form-control" name="profile_company_phone" id="profile_company_phone">
                            <p class="text-danger invalid-profile_company_phone"></p>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                            <p class="text-danger invalid-password"></p>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" autocomplete="new-password">
                            <p class="text-danger invalid-password_confirmation"></p>
                        </div>
                    </div>

                      <div class="col-sm-12">
                        <div class="form-group">
                            <label for="profile_file_path" class="col-form-label">Upload Image</span></label>
                            <input type="file" class="form-control" name="profile_file_path" id="profile_file_path" accept=".png, .jpg, .jpeg">
                            <p class="text-danger invalid-profile_file_path"></p>
                        </div>
                    </div>
                    
                </div>
              </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="profileSaveBtn"><i class="fa fa-save"></i>&nbsp; Save</button>
        </div>
      </div>
    </div>
  </div>
