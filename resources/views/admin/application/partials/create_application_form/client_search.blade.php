<div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">
        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.application.index')}}">List Application</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $isEditable ??  'Create' }} Application</li>
                </ol>
            </nav>
        
        <div class="card-header">
            <h4 class="card-title">Application Form</h4>
        </div>
        <div class="card-body">
            <div class="row" {{ $isEditable ?? 'hidden'  }}>
                <div class="col-sm-4">
                    <label for="search_clients">Search Clients <span class="required">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2 select2-hidden-accessible" style="width: 80%;" tabindex="-1" aria-hidden="true" id="search_clients" name="client_id" data-control="select2" data-placeholder="Search Clients" data-hide-search="true">
                            <option value="{{ $isEditable && $booking_details->user_id  ? $booking_details->user_id : ''  }}">{{ $isEditable ?? false  && $booking_details->fname ? $booking_details->fname : ''  }}</option>
                        </select>
                        <button type="button" class="btn btn-success" title="Add Client" id="addCustomer"><i class="fas fa-plus"></i></button>
                        <p class="invalid_client_id text-danger"></p>
                    </div>
                </div>
            </div>
            <div class="row" id="client_info"></div>
        </div>
    </div>
</div>


<div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">
        
        <div class="card-header">
            <h4 class="card-title">Client Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Client Name</label>
                        <input type="text" class="form-control" value="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Client Reference</label>
                        <input type="text" class="form-control" value="Reference">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Broker</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Intoducer</label>
                        <input type="text" class="form-control" value="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Intoducer Company</label>
                        <input type="text" class="form-control" value="Reference">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Date Produced</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Property</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">
        
        <div class="card-header">
            <h4 class="card-title">Terms</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Regulation</label>
                        <input type="text" class="form-control" value="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Advice Provided By</label>
                        <input type="text" class="form-control" value="Reference">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Product Type</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Borrowers Legals Needed</label>
                        <input type="text" class="form-control" value="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Loan Type</label>
                        <input type="text" class="form-control" value="Reference">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">ERCs</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Terms (Months)</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Overpayments Allowed</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Property Value</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Loan to Value (%)</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Gross Loan Amount</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Net Loan Amount</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Initial Pay Rate</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">% Per Annum Which is</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Per Month</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Initial Rate Period</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Reversionary rate</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Valuation Fee</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Lenders Application Fee</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Lenders Arragement Fee</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Lenders Legal Fee</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Lenders Insurance & Admin Fees</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Crystal package fees</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
            </div>
            <button class="btn btn-success"
            id="book_now">Book Now</button>
        </div>
    </div>
<script>

    $('#addCustomer').click(function() {
            // ClientModal_ResetErrors()
            $('#customer_id').val('');
            $('#saveBtn').html("<i class=\"fa fa-save\"></i>&nbsp; Save");
            $('#customerForm').trigger("reset");
            $('#form-modal').modal('show');
        });

</script>

</div>


    