<div id="form-modal" class="modal fixed-left fade" tabindex="-1" role="dialog" style="left:auto !important; width:30%;">
    <div class="modal-dialog modal-dialog-aside" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Case Assign</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="clientAssignForm" name="clientAssignForm">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Case NO<span class="required">&nbsp;</span></label>
                            <input type="text" class="form-control" name="case_No" id="case_No" placeholder="Enter Name" readonly>
                            <p class="text-danger invalid-name"></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group">
                            <label for="client" class="col-form-label">Clients<span class="required">&nbsp;</span></label>
                        <select class="form-control select2 select2-hidden-accessible" style="width: 70% !important;" tabindex="-1" aria-hidden="true" id="client_id" name="client_id" data-control="select2" data-placeholder="Search Clients" data-hide-search="true">

                        </select>
                            <p class="text-danger invalid-client"></p>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="job_id" id="job_id">
              </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; Save</button>
        </div>
      </div>
    </div>
  </div>




