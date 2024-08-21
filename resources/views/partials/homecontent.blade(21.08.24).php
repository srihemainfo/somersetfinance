<div class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            Case List
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="open-cases-tab" data-toggle="tab" href="#open-cases" role="tab" aria-controls="open-cases" aria-selected="true">Open Cases</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="completed-cases-tab" data-toggle="tab" href="#completed-cases" role="tab" aria-controls="completed-cases" aria-selected="false">Completed Cases</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="canceled-cases-tab" data-toggle="tab" href="#canceled-cases" role="tab" aria-controls="canceled-cases" aria-selected="false">Canceled Cases</a>
                              </li>
                            </ul>
                            
                            <div class="tab-content" id="myTabContent">
                              <div class="tab-pane fade show active" id="open-cases" role="tabpanel" aria-labelledby="open-cases-tab">
                                <div class="card-body">
                                    <table class=" table table-striped table-hover ajaxTable datatable datatable-caseList text-center">
                                        <thead>
                                          <tr>
                                            <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                      
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="completed-cases" role="tabpanel" aria-labelledby="completed-cases-tab">
                                <div class="card-body">
                                    <table class="table table-striped table-hover ajaxTable datatable datatable-caseList2 text-center">
                                        <thead>
                                          <tr>
                                               <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="canceled-cases" role="tabpanel" aria-labelledby="canceled-cases-tab">
                                <div class="card-body">
                                    <table class="table table-striped table-hover ajaxTable datatable datatable-caseList3 text-center">
                                        <thead>
                                          <tr>
                                            <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                    </table>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            Enquiry List
                        </div>
                        <div class="card-body">
                            <table class=" table table-striped table-hover ajaxTable datatable datatable-caseList4 text-center">
                                <thead>
                                  <tr>
                                    <th scope="col"></th>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Name</th>
                                    @if( $roleTitle == 1)
                                        <th scope="col">Partner</th>
                                    @endif
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                              
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>