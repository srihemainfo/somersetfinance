<div id="form-modal" class="modal fixed-left fade" tabindex="-1" role="dialog" style="left:auto !important; width:30%;">
    <div class="modal-dialog modal-dialog-aside" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Customer Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="customerForm" name="customerForm">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">First Name<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter First Name">
                            <p class="text-danger invalid-name"></p>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="last_name" class="col-form-label">Last Name<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name">
                            <p class="text-danger invalid-last_name"></p>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email <span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email">
                            <p class="text-danger invalid-email"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="phone" class="col-form-label">Phone No.<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone No.">
                            <p class="text-danger invalid-phone-no"></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="address1" class="col-form-label">Address Line 1</label>
                             <input type="text" class="form-control" name="address1" id="address1" placeholder="Enter Address line 1">
                            {{--<textarea class="form-control" name="address1" id="address1"></textarea>--}}
                            <p class="text-danger"></p>
                        </div>
                    </div>
                </div>
                
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="address1" class="col-form-label">Address Line 2</label>
                             <input type="text" class="form-control" name="address2" id="address2" placeholder="Enter Address line 2">
                            {{--<textarea class="form-control" name="address1" id="address1"></textarea>--}}
                            <p class="text-danger"></p>
                        </div>
                    </div>
                </div>
              

                <input type="hidden" name="customer_id" id="customer_id">
              </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; Save</button>
        </div>
      </div>
    </div>
  </div>
