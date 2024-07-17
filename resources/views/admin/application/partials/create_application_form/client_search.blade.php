<style>
    .select3 {
        width: 100% !important;
    }
    .select2-container--default{
        width: 100% !important;
    }
</style>
<div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.application.index') }}">List Application</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $isEditable ?? 'Create' }} Application</li>
            </ol>
        </nav>

        <div class="card-header">
            <h4 class="card-title">Application Form</h4>
        </div>
        <div class="card-body">
            <div class="row" {{ $isEditable ?? 'hidden' }}>
                <div class="col-sm-4">
                    <label for="search_clients">Search Clients <span class="required">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2 select2-hidden-accessible" style="width: 80%;"
                            tabindex="-1" aria-hidden="true" id="search_clients" name="client_id"
                            data-control="select2" data-placeholder="Search Clients" data-hide-search="true">
                            <option
                                value="{{ $isEditable && $booking_details->user_id ? $booking_details->user_id : '' }}">
                                {{ $isEditable ?? false && $booking_details->fname ? $booking_details->fname : '' }}
                            </option>
                        </select>
                        <button type="button" class="btn btn-success" title="Add Client" id="addCustomer"><i
                                class="fas fa-plus"></i></button>
                        <p class="invalid_client_id text-danger"></p>
                    </div>
                </div>
            </div>
            <div class="row" id="client_info"></div>
        </div>
    </div>
</div>


<div class="col-sm-12 main-card mb-3 card">
    <div class="card-header">
        <h4 class="card-title">Co-Applicants</h4>
    </div>
    <div class="card-body">

        <!-- Co-Applicants Section -->
        <div id="coApplicantsSection" class='mb-5'>
            <div id="coapplicant_content_div">
                <div class="row coapplicant-fields">
                <div class="col-sm-4">
                    <label for="co_applicants" class="form-label co-applicant-name">Co-Applicant Name</label>
                    <input type="text" class="form-control co_applicants_name" name="co_applicants_name[]" placeholder="Enter Co-applicant name">
                </div>
                <div class="col-sm-4">
                    <label for="co_applicants" class="form-label">Co-Applicant Email</label>
                    <input type="email" class="form-control co_applicants_email" name="co_applicants_email[]" placeholder="Enter Co-applicant Email">
                </div>
                <div class="col-sm-4">
                    <label for="co_applicants" class="form-label">Co-Applicant phone</label>
                    <input type="number" class="form-control co_applicants_phone" name="co_applicants_phone[]" placeholder="Enter Co-applicant Phone">
                </div>
                <div class="col-sm-4">
                    <label for="co_applicants" class="form-label">Co-Applicant Address</label>
                    <textarea class="form-control co_applicants_address" name="co_applicants_address[]" id="" cols="10" rows="5" placeholder="Enter Co-applicant Phone"></textarea>
                    {{-- <input type="text" class="form-control co_applicants_address" name="co_applicants_address[]" placeholder="Enter Co-applicant Phone"> --}}
                </div>
            </div>
            </div>
        </div>

        <button type="button" id="addCoApplicantButton" class="btn btn-primary">Add Co-Applicant</button>
        {{-- <button type="submit" class="btn btn-success">Submit</button> --}}

    </div>
</div>


<div class="col-sm-12 main-card mb-3 card" >

    <div class="card-body">

        <div class="card-header">
            <h4 class="card-title">Terms</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-4 col-md-4">
                    <label for="loan_type_id" class="required">Loan Type</label>

                    <select name="loan_type_id" id="loan_type_id" class='form-control select2'>
                        @foreach ($loan_types as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach

                    </select>
                    <span id="loan_type_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                {{-- <div class="col-lg-4 col-md-4">
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
                </div> --}}
            </div>
            {{-- <button class="btn btn-success" id="book_now">Book Now</button> --}}
        </div>
    </div>


</div>


{{-- <div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">

        <!-- Co-Applicants Section -->
        {{-- <div id="coApplicantsSection">
            <h4>Co-Applicants</h4>
            <!-- Co-Applicant details will be appended here -->
        </div>

        <button type="button" id="addCoApplicantButton" class="btn btn-primary">Add Co-Applicant</button>
        <button type="submit" class="btn btn-success">Submit</button> --}}

        {{-- <div class="card-header">
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
</div> --}}


<div class="col-sm-12 main-card mb-3 card" id='document_container' style='display:none'>
    <div class="card-body">

        <div class="card-header">
            <h4 class="card-title">Choose the Document Type</h4>
        </div>
        <div class="card-body">
            <div class="row" id='document_type_data'>


            </div>

            <div class="row gutters">

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 form-group" id='document_types_container'>
                    <label for="document_id" class="required">Documents</label>

                    <select name="document_id[]" id="document_id" class='form-control select2 select3' multiple>


                    </select>
                    <span id="document_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>



            </div>
            {{-- <button class="btn btn-success" id="book_now">Book Now</button> --}}
        </div>
    </div>

</div>

<div class="col-sm-12 main-card mb-3 card" id='formupload_container' style='display:none'>


    <div class="card-header">
        <h4 class="card-title">Choose the formupload Type</h4>
    </div>
        <div class="card-body">
            <div class="row" id='formupload_type_data'>


            </div>

            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 form-group" id='form_upload_container'>
                    <label for="form_upload_id" class="required">Form Upload</label>

                    <select name="form_upload_id[]" id="form_upload_id" class='form-control select2 select3' multiple>


                    </select>
                    <span id="form_upload_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 mb-1 form-group">
                        <label for="exampleInputPassword1">Addition Document</label>
                        <input type="text" class='addition_document' class="form-control">
                </div>
                <div class="col-sm-2 mb-1">
                    <button type="button" class="btn btn-primary add_addition_document" title="Add New Pickup Point"
                        style="position: absolute; bottom: 0;">
                        <i class="fa fa-plus" aria-hidden="true"></i> &nbsp;&nbsp; Add Point
                    </button>
                </div>
            </div>
            <div class="row mb-3" id="points_values">
            </div>




        </div>

    </div>

    <div class="col-sm-12 main-card mb-3 card">
        <div class="card-body">
         <button class="btn btn-success" id="book_now">Submit Now</button>
        </div>
    </div>




