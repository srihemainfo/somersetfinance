<style>
    .select2 {
        width: 100% !important;
    }
    .select2-container--default{
        width: 70% !important;
    }
</style>
<style>
.custom-file-upload {
  border: none;
  display: flex;
  padding: 0.65rem 1.5rem;
  background-color: #488aec;
  color: #ffffff;
  font-size: 0.75rem;
  line-height: 1rem;
  font-weight: 700;
  text-align: center;
  text-transform: uppercase;
  vertical-align: middle;
  align-items: center;
  border-radius: 0.5rem;
  user-select: none;
  gap: 0.75rem;
  box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
  transition: all .6s ease;
  cursor: pointer;
}

.custom-file-upload:hover {
  box-shadow: 0 10px 15px -3px #488aec4f, 0 4px 6px -2px #488aec17;
}

.custom-file-upload:focus,
.custom-file-upload:active {
  opacity: .85;
  box-shadow: none;
}

.upload-icon {
  width: 1.25rem;
  height: 1.25rem;
  left: 1%;
  transform: translate(0);
}
</style>

<style>
    
.choose_your {
  width: 300px;
  height: 220px;
  background-color: rgb(255, 255, 255);
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px 30px;
  gap: 13px;
  position: relative;
  overflow: hidden;
  box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.062);
}

.heading_title {
  font-size: 1.2em;
  font-weight: 800;
  color: rgb(26, 26, 26);
}

.description_title {
  text-align: center;
  font-size: 0.7em;
  font-weight: 600;
  color: rgb(99, 99, 99);
}

.description_title a {
  --tw-text-opacity: 1;
  color: rgb(59 130 246);
}

.description_title a:hover {
  -webkit-text-decoration-line: underline;
  text-decoration-line: underline;
}

.buttonContainer {
  display: flex;
  gap: 20px;
  flex-direction: row;
}

.acceptButton {
  width: 80px;
  height: 30px;
  background-color: #7b57ff;
  transition-duration: .2s;
  border: none;
  color: rgb(241, 241, 241);
  cursor: pointer;
  font-weight: 600;
  border-radius: 20px;
  box-shadow: 0 4px 6px -1px #977ef3, 0 2px 4px -1px #977ef3;
  transition: all .6s ease;
}

.declineButton {
  width: 110px;
  height: 45px;
  background-color: #dadada;
  transition-duration: .2s;
  color: rgb(46, 46, 46);
  border: none;
  font-weight: 600;
  border-radius: 5px;
  box-shadow: 0 4px 6px -1px #bebdbd, 0 2px 4px -1px #bebdbd;
  transition: all .6s ease;
  display: flex;
  justify-content: center;
  align-items: center;
}

.declineButton:focus {
    outline:none;
}

.declineButton:hover {
  background-color: #ebebeb;
  box-shadow: 0 10px 15px -3px #bebdbd, 0 4px 6px -2px #bebdbd;
  transition-duration: .2s;
  cursor: pointer;
}

.acceptButton:hover {
  background-color: #9173ff;
  box-shadow: 0 10px 15px -3px #977ef3, 0 4px 6px -2px #977ef3;
  transition-duration: .2s;
}

input.error, select.error {
    border-color: red;
}

.error-message {
    color: red;
    display: none;
}
    
</style>


<div class="card">
    {{-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.application.index') }}">List Case</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $isEditable ?? 'Create' }} Case</li>
        </ol>
    </nav> --}}

    <div class="card-header">
    <div class="row">
       <div class='{{ $isEditable ? 'col-md-6' : 'col-md-12' ;  }}'>
        Case Form
           
       </div>
       @if($isEditable)
       <div class='col-md-6'>
       <div class='row'>
           
            <div class="col-sm-4">
                    <label for="case_status">Case Status </label>
                    <div class="input-group">
                        <select class="form-control select2"  id="case_status" name="client_id" data-placeholder="Case Status">
                        <option value="">Select Status</option>
                        @php
                            $stages = ["Submitted", "Completed", "Canceled"];
                             $condition = false; 
                            if(isset($isEditable) && isset($data->status) && in_array($data->status, $stages)){
                               $condition = true; 
                            
                            }
                        @endphp
                        @if($condition)
                            <option value="Processing">Processing</option>
                        @endif
                        <option value="Submitted">Submitted</option>
                        <option value="Completed">Completed</option>
                        <option value="Canceled">Canceled</option>
                        </select>
                       
                </div>
            </div>
           
           
       </div>
       </div>
       @endif
        
    </div>
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


<div class="card">
    <div class="card-header row">
        <div class="col-6">
            Co-Applicants
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
                        <input type="text" class="form-control co_applicants_address" name="co_applicants_address[]" placeholder="Enter Co-applicant Address" value={{ $coapplicant->address ?? '' }}>
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


<div class="card">
    <div class="card-header">
        Loan Details
    </div>
    <div class="card-body">
            <div class="row">

                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Loan Amount</label>
                        <input type="text" name='loan_amount' id='loan_amount' class="form-control" oninput="validateInput(this)"  value={{ $isEditable && $data->loan_amount ? $data->loan_amount : '' }} >
                        <span class="error-message">This field is required.</span>
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
                        <label for="exampleInputPassword1" >Term Year</label>
                        <input type="text" name='term_year' id = 'term_year' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->term_year ? $data->term_year : 0 }}  >
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Term Month</label>
                        <input type="text" name='term_month' id = 'term_month' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->term_month ? $data->term_month : 0 }}  >
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Rate%</label>
                        <input type="text" name='rate' title='Enter Numeric' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->rate ? $data->rate : '' }} >
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" >Broker Fee</label>
                        <input type="text" name='proc_fee' title='Enter Number only' oninput="validateInput(this)" class="form-control" value={{ $isEditable && $data->proc_fee ? $data->proc_fee : '' }} >
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


{{-- <div class="card" >

    <div class="card-body">

        <div class="card-header">
            Terms
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


{{-- <div class="card">
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


<div class="card" id="choose_your_div">
            <div class="card-header">
                Choose Your Route
            </div>
            <div class="card-body row" style="gap:50px;">
                
                <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="loan_category" id="loan_category">
                        <option selected value="">Select</option>
                        <option  value="broker" {{ $isEditable && $data->loan_category == 'broker' ?'selected' : '' }}>Broker</option>
                        <option  value="introducer" {{ $isEditable && $data->loan_category == 'introducer' ? 'selected' : '' }}>Introducer</option>
                    </select>
                    <span class="error-message">This field is required.</span>
                    {{--
                    <input type="radio" name="loan_category" value="broker" id="broker_radio" {{  $isEditable && $data->loan_category == 'broker' ? 'checked' : '' }}>
                    <input type="radio" name="loan_category" value="introducer" id="introducer_radio" {{  $isEditable && $data->loan_category == 'introducer' ? 'checked' : '' }}>
                    <label for="broker_radio" class="declineButton" id="broker_btn">Broker</label>
                    <label for="introducer_radio" class="declineButton" id="introducer_btn">Introducer</label> --}}
               
                </div>
            </div>
        </div>

        <!-- Broker Form -->
        <div class="card" id="broker_form" style="{{ $isEditable && $data-> loan_category == 'broker' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                Answer the few Questions
            </div>
            <div class="card-body">
                <h5 class="text-info">Is the mortgage more than 80%?</h5>
                
                 <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="mortgage_status" id="mortgage_status">
                        <!--<option selected value="">Select</option>-->
                        <option value="no" {{ $isEditable && $data->mortgage_status == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                
                {{--
                <div class="d-flex justify-content-center" style="gap:50px;">
                    <input type="radio" name="mortgage_status" value="yes" id="mortage_question_yes_radio" {{ $isEditable && $data->mortgage_status == 'yes' ? 'checked' : '' }}>
                    <input type="radio" name="mortgage_status" value="no" id="mortage_question_no_radio" {{  $isEditable && $data->mortgage_status == 'no' ? 'checked' : '' }}>
                    <label for="mortage_question_yes_radio" class="declineButton" id="mortage_question_yes">Yes</label>
                    <label for="mortage_question_no_radio" class="declineButton" id="mortage_question_no">No</label>
                </div>
                --}}
                
            </div>
        </div>

        <!-- Mortgage Question Yes Result -->
        <div class="card" id="mortage_question_yes_result" style="{{  $isEditable && $data->mortgage_status == 'yes' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                Answer the few Questions
            </div>
            <div class="card-body">
                <h5 class="text-info">Sorry, we can't process further. Kindly contact the admin.</h5>
              
            </div>
        </div>

        <!-- Mortgage Question No Result -->
        
        <div class="card" id="mortage_question_no_result" style="{{  $isEditable && $data->mortgage_status == 'no' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                Loan Requirements
            </div>
            <div class="card-body">
        
                    <div class="row">
                        {{--
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="loan_type">Choose the Loan Type</label>
                                <select class="form-control" name='loan_type' id="loan_type">
                                    @foreach($loan_types as $id => $value)
                                    <option  value="{{$id}}" {{  $isEditable && $data->loan_category_type == $value ?'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                    
                                </select>
                                <span class="error-message">This field is required.</span>
                            </div>
                        </div> --}}
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="purpose_loan">Purpose of Loan</label>
                                <select class="form-control" name='purpose_loan' id="purpose_loan">
                                    <option selected value='' >Select</option>
                                    <option value='Purchase Property' {{ $isEditable && $data->purpose_loan == 'Purchase Property' ?'selected' : '' }} >Purchase Property</option>
                                    <option value='Purchase the Other' {{ $isEditable && $data->purpose_loan == 'Purchase the Other' ?'selected' : '' }}>Purchase the Other</option>
                                    <option value='Debit Consolidation'{{ $isEditable && $data->purpose_loan == 'Debit Consolidation' ?'selected' : '' }}>Debit Consolidation</option>
                                    <option value='Home Improvement' {{ $isEditable && $data->purpose_loan == 'Home Improvement' ?'selected' : '' }}>Home Improvement</option>
                                    <option value='Capital Raise' {{ $isEditable && $data->purpose_loan == 'Capital Raise' ?'selected' : '' }} >Capital Raise ( No an additional loan)</option>
                                    <option value='Transfer of Equit' {{ $isEditable && $data->purpose_loan == 'Transfer of Equit' ?'selected' : '' }}>Transfer of Equity</option>
                                    <option value='Other' {{ $isEditable && $data->purpose_loan == 'Other'?'selected' : '' }}>Other</option>
                                </select>
                                <span class="error-message">This field is required.</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="application_made">How is the application being made?</label>
                                <select class="form-control" name='application_made' id="application_made">
                                    <option selected value=''>Select</option>
                                    <option value='Personal Name' {{ $isEditable && $data->application_made == 'Personal Name' ?'selected' : '' }}  >Personal Name</option>
                                    <option value='Ltd. Company' {{ $isEditable && $data->application_made == 'Ltd. Company' ?'selected' : '' }} >Ltd. Company</option>
                                    <option value='LLP' {{ $isEditable && $data->application_made == 'LLP' ?'selected' : '' }}  >LLP</option>
                                    <option value='Trust' {{ $isEditable && $data->application_made == 'Trust' ?'selected' : '' }} >Trust</option>
                                </select>
                                <span class="error-message">This field is required.</span>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <label>Does the client or family members live or intend to live in 40% or more of the property?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" {{ $isEditable && $data->live_or_intent_property == 'yes' ?'checked' : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property" value="yes">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" {{ $isEditable && $data->live_or_intent_property == 'no' ?'checked' : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property1" value="no">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        <span class="error-message">This field is required.</span>
                        </div>
                    </div>
            </div>
         
        </div>
        
        <div class="card" id="introducer_form" {{ ($isEditable && $data->application_made == 'Personal Name' && $data->mortgage_status == 'no'  ) ?  'display:block' : 'display:none' }}>
    <div class="card-header">
        Client Provide Details
    </div>
    <div class="card-body">
      
            <div class="row">
                {{--
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Title</label>
                        <select class="form-control" name='loan_category_type' id="loan_category_type">
                             @foreach($loan_types as $id => $value)
                            <option  value="{{$id}}" {{ $isEditable && $data->loan_category_type == $id ?'selected' : '' }}>{{$value}}</option>
                            @endforeach
                        </select>
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
               
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client First Name</label>
                        <input type="text" name='client_first_name' id="client_first_name"  value="{{ $isEditable && $data->client_first_name ?? ''}}" class="form-control" placeholder="Enter First Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Last Name</label>
                        <input type="text" name='client_last_name' id="client_last_name" value="{{$isEditable && $data->client_last_name ?? ''}}"  class="form-control" placeholder="Enter Last Name">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Mobile Telephone</label>
                        <input type="text" name='client_phone' id="client_phone"  value="{{$isEditable && $data->client_phone ?? ''}}" class="form-control" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Email Address</label>
                        <input type="email" name='client_email' id="client_email" value="{{ $isEditable && $data->client_email ?? ''}}"  class="form-control" placeholder="Enter Email Address">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Loan Amount</label>
                        <input type="text"  name='client_loan_amount' id="client_loan_amount"  value="{{$isEditable && $data->client_loan_amount ?? ''}}" class="form-control" placeholder="Enter Loan Amount" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                 --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Property Value</label>
                        <input type="text" name='client_propertity_value' title='Enter number only' id="client_propertity_value" value="{{ ($isEditable && $data->client_propertity_value) ? ( $data->client_propertity_value  ?? '') : '' }}"    class="form-control" placeholder="Enter Property Value" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Any Other Notes?</label>
                        <input type="text" name='client_extra_comment' id="client_extra_comment" value="{{  ($isEditable && $data->client_extra_comment) ? ( $data->client_extra_comment ?? '' ) : ''}}"   class="form-control">
                        <!--<textarea class="form-control" rows="1"></textarea>-->
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                
            </div>
    
    </div>
   
</div>

<div class="card" id="introducer_company_form"  style="{{ ($isEditable && $data->application_made != 'Personal Name' && $data->mortgage_status == 'no'  ) ?  'display:block' : 'display:none' }}">
            <div class="card-header">
                Please Provide Company and client Details
            </div>
            <div class="card-body">
                <!--  -->
      
            <!-- company details start-->
              <p>Company Details</p>
              <hr>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" name='company_name' id="company_name" class="form-control" value="{{  ($isEditable && $data->company_name ) ? ($data->company_name ?? '') : '' }}" placeholder="Enter Company Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_no">Company No.</label>
                        <input type="text" name='company_no' id="company_no" class="form-control" value="{{  ($isEditable && $data->company_no ) ? ( $data->company_no ?? '') : '' }}" placeholder="Enter Company NO.">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_address_1">Company Address Line 1</label>
                        <input type="text" name='company_address_1' id="company_address_1"  value="{{  ($isEditable && $data->company_address_1 ) ? ( $data->company_address_1 ?? '') : '' }}"  class="form-control" placeholder="Enter Company Address Line1">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_address_2">Company Address Line 2</label>
                        <input type="text" name='company_address_2' id="company_address_2" value="{{ ($isEditable && $data->company_address_2 ) ? ( $data->company_address_2 ?? '') : '' }}"  class="form-control" placeholder="Enter Company Address Line2">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
          </div>
          
          <!--security Details start-->
          
             <p>Security Details</p>
              <hr>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_security_address_line1">Security Address Line1</label>
                        <input type="text" name='company_security_address_line1' id="company_security_address_line1" class="form-control" value="{{  ($isEditable && $data->company_security_address_line1 ) ? ($data->company_security_address_line1 ?? '') : '' }}" placeholder="Enter Security Address Line1">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="company_security_address_line2">Security Address Line2</label>
                        <input type="text" name='company_security_address_line2' id="company_security_address_line2" class="form-control" value="{{  ($isEditable && $data->company_security_address_line2 ) ? ( $data->company_security_address_line2 ?? '') : '' }}" placeholder="Enter Security Address Line2">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                
          </div>
          
          
          <!--security Details end-->
                 
                <p>Other Details</p>
                    <hr>
                 <div class="row">
                     
                     
                   
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_type">Property Value</label>
                            <input type="text" name='company_client_propertity_value' value="{{ ($isEditable && $data->client_propertity_value ) ? (  $data->client_propertity_value ?? '' ) : ''}}" id="company_client_propertity_value"   class="form-control" placeholder="Enter Property Value" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_type">Any Other Notes?</label>
                            <input type="text" name='company_client_extra_comment' id="company_client_extra_comment" value="{{ ($isEditable && $data->client_extra_comment ) ? ( $data->client_extra_comment ?? '' ) : ''}}"  class="form-control">
                          
                         <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    
                </div>
                 </div>
                
                 
                 
        </div>
      






<div class="card" id='document_container' style='display:none'>
    <div class="card-body">

        <div class="card-header">
            Choose the Document Type
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
                Additional Document
            </div>

            <div class="row">
                <div class="col-sm-12 my-3 form-group row align-items-end">
                    <div class="col-sm-4">
                        <label for="exampleInputPassword1">Document Name</label>
                        <input type="text" class="addition_document form-control" placeholder="Enter Additional Document Name">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-primary add_addition_document" title="Add New Pickup Point">
                            <i class="fa fa-plus" aria-hidden="true"></i> &nbsp;&nbsp; Add Additional Document
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="points_values">
            </div>
            
              <div class="card-header">
                  Additional Form Document
            </div>

            <div class="row">
                <div class="col-sm-12 my-3 form-group row align-items-end">
                    <div class="col-sm-4">
                        <label for="exampleInputPassword1">Additional Form Document Name</label>
                        <input type="text" class="addition_form_document form-control" placeholder="Enter Additional Form Document Name">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-primary add_addition_form_document" title="Add New Additional Form Document">
                            <i class="fa fa-plus" aria-hidden="true"></i> &nbsp;&nbsp; Add Additional Form Document
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="points_form_values">
            </div>
            
            

            {{-- <button class="btn btn-success" id="book_now">Book Now</button> --}}
        </div>
    </div>

</div>

<div class="card" id='formupload_container' style='display:none'>


    <div class="card-header">
        Choose the Form Upload Type
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

    <div class="card">
        <div class="card-body">
         <button class="btn btn-success" id="book_now">Submit Now</button>
        </div>
    </div>




