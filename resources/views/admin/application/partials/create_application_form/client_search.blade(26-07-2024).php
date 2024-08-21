<style>
    .select2 {
        width: 100% !important;
    }
    .select2-container--default{
        width: 70% !important;
    }
</style>
<div class="col-sm-12 main-card mb-3 card">
    <div class="card-body">

        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.application.index') }}">List Case</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $isEditable ?? 'Create' }} Case</li>
            </ol>
        </nav> --}}

        <div class="card-header">
            <h4 class="card-title">Case Form</h4>
        </div>
        <div class="card-body">
            <div class="row" {{ $isEditable ?? 'hidden' }}>
                <div class="col-sm-4">
                    <label for="search_clients">Search Clients <span class="required">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2 select2-hidden-accessible" style="width: 70% !important;"
                            tabindex="-1" aria-hidden="true" id="search_clients" name="client_id"
                            data-control="select2" data-placeholder="Search Clients" data-hide-search="true">
                            <option
                                value="{{ $isEditable && $data->customer_id ? $data->customer_id : '' }}">
                                {{ $isEditable ?? false && ($data->customerdetail->name &&  $data->customerdetail->email ) ? $data->customerdetail->name . '(' . $data->customerdetail->email   .')': '' }}
                            </option>
                        </select>
                        <button type="button" class="btn btn-success m-auto" title="Add Client" id="addCustomer"><i
                                class="fas fa-plus"></i></button>
                        <p class="invalid_client_id text-danger"></p>
                         <input type="hidden" name='case_id' value='{{ $data->id ?? false }}'>
                    </div>
                </div>
            </div>
            <div class="row" id="client_info"></div>
        </div>
    </div>
</div>


<div class="col-sm-12 main-card mb-3 card">
    <div class="card-header row">
        <div class="col-6">
            <h4 class="card-title">Co-Applicants</h4>
        </div>
        <div class="col-6 text-right">
            <button type="button" id="addCoApplicantButton" class="btn btn-primary">Add Co-Applicant</button>
        </div>
    </div>
    <div class="card-body">

        <!-- Co-Applicants Section -->
        <div id="coApplicantsSection" class='mb-5'>


            <div id="coapplicant_content_div">
                @if($isEditable && count($data->co_applicant1) > 0 )

                @php
                    $i = true;
                @endphp

                @foreach ($data->co_applicant1 as $coapplicant)

                <div class="row coapplicant-fields my-3">
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label co-applicant-name">Co-Applicant Name</label>
                        <input type="text" class="form-control co_applicants_name" name="co_applicants_name[]" placeholder="Enter Co-applicant name" value={{ $coapplicant->name ?? '' }}>
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant Email</label>
                        <input type="email" class="form-control co_applicants_email" name="co_applicants_email[]" placeholder="Enter Co-applicant Email"  value={{ $coapplicant->email ?? '' }}>
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant phone</label>
                        <input type="number" class="form-control co_applicants_phone" name="co_applicants_phone[]" placeholder="Enter Co-applicant Phone" value={{ $coapplicant->phone ?? '' }}>
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant Address</label>
                        <input type="text" class="form-control co_applicants_address" name="co_applicants_address[]" placeholder="Enter Co-applicant Address">
                        {{-- <textarea class="form-control co_applicants_address" name="co_applicants_address[]" id="" cols="10" rows="3" placeholder="Enter Co-applicant Address">
                            {{ $coapplicant->address ?? '' }}
                        </textarea> --}}
                    </div>
                         @if(!$i)
                            <div class="col-sm-12"><button type="button" class="col-sm-2 remove-btn btn btn-danger mt-3">Remove</button></div>
                        @endif
                </div>

                @php
                $i = false;
                @endphp
                @endforeach

                @else

                <div class="row coapplicant-fields my-3">
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label co-applicant-name">Co-Applicant Name</label>
                        <input type="text" class="form-control co_applicants_name" name="co_applicants_name[]" placeholder="Enter Co-applicant name">
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant Email</label>
                        <input type="email" class="form-control co_applicants_email" name="co_applicants_email[]" placeholder="Enter Co-applicant Email">
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant phone</label>
                        <input type="number" class="form-control co_applicants_phone" name="co_applicants_phone[]" placeholder="Enter Co-applicant Phone">
                    </div>
                    <div class="col-sm-3">
                        <label for="co_applicants" class="form-label">Co-Applicant Address</label>
                        <input type="text" class="form-control co_applicants_address" name="co_applicants_address[]" placeholder="Enter Co-applicant Address">
                        {{-- <textarea class="form-control co_applicants_address" name="co_applicants_address[]" id="" cols="2" rows="1" placeholder="Enter Co-applicant Address"></textarea> --}}
                    </div>
                </div>

                @endif



            </div>

        </div>


        {{-- <button type="submit" class="btn btn-success">Submit</button> --}}

    </div>
</div>


<div class="col-sm-12 main-card mb-3 card" >

    <div class="card-body">

        <div class="card-header">
            <h4 class="card-title">Loan Details</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Loan Amount</label>
                        <input type="text" name='loan_amount' class="form-control" oninput="validateInput(this)"  value={{ $isEditable && $data->loan_amount ? $data->loan_amount : '' }} >
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1">PP/EV </label>
                        <input type="text" name='pp_ev' class="form-control" value={{ $isEditable && $data->pp_ev ? $data->pp_ev : '' }}  >
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Term</label>
                        <input type="text" name='term' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->term ? $data->term : '' }}  >
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Rate%</label>
                        <input type="text" name='rate' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->rate ? $data->rate : '' }} >
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Broker Fee</label>
                        <input type="text" name='proc_fee' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->proc_fee ? $data->proc_fee : '' }} >
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3">
                    <label for="loan_type_id" class="required">Loan Type</label> <br>

                    <select name="loan_type_id" id="loan_type_id" class='form-control select2'>
                        @foreach ($loan_types as $key => $value)
                            <option  @if( $isEditable && $key == $data->loan_type_id ?? '') selected  @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach

                    </select> <br>
                    <span id="loan_type_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                {{-- <div class="col-lg-4 col-md-4">
                    <label for="loan_type_id" class="required">Loan Type</label> <br>

                    <select name="loan_type_id" id="loan_type_id" class='form-control select2'>
                        @foreach ($loan_types as $key => $value)
                            <option  @if($key == $data->loan_type_id) selected  @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach

                    </select>
                    <span id="loan_type_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div> --}}

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
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Terms (Months)</label>
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
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Property Value</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Loan to Value (%)</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Gross Loan Amount</label>
                        <input type="text" class="form-control" value="Broker">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Net Loan Amount</label>
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
                        <label for="exampleInputPassword1" oninput="validateInput(this)">Per Month</label>
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


{{-- <div class="col-sm-12 main-card mb-3 card" >

    <div class="card-body">

        <div class="card-header">
            <h4 class="card-title">Terms</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-4 col-md-4">
                    <label for="loan_type_id" class="required">Loan Type</label> <br>

                    <select name="loan_type_id" id="loan_type_id" class='form-control select2'>
                        @foreach ($loan_types as $key => $value)
                            <option  @if( $isEditable && $key == $data->loan_type_id ?? '') selected  @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach

                    </select> <br>
                    <span id="loan_type_id_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

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
            <button class="btn btn-success" id="book_now">Book Now</button>
        </div>
    </div>


</div> --}}


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

            <div class="card-header">
                <h4 class="card-title">Additinal Document</h4>
            </div>

            <div class="row">
                <div class="col-sm-12 my-3 form-group row align-items-end">
                    <div class="col-sm-4">
                        <label for="exampleInputPassword1">Document Name</label>
                        <input type="text" class="addition_document form-control" placeholder="Enter Additional Document Name">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-primary add_addition_document" title="Add New Pickup Point">
                            <i class="fa fa-plus" aria-hidden="true"></i> &nbsp;&nbsp; Add Additioanl Document
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="points_values">
            </div>

            {{-- <button class="btn btn-success" id="book_now">Book Now</button> --}}
        </div>
    </div>

</div>

<div class="col-sm-12 main-card mb-3 card" id='formupload_container' style='display:none'>


    <div class="card-header">
        <h4 class="card-title">Choose the Form Upload Type</h4>
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

        </div>

    </div>

    <div class="col-sm-12 main-card mb-3 card">
        <div class="card-body">
         <button class="btn btn-success" id="book_now">Submit Now</button>
        </div>
    </div>




