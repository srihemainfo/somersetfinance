@extends('layouts.admin')
@section('content')

@php

     $userId = auth()->user()->id;
    $user = \App\Models\User::find($userId);
    $assignedRole = $user ? $user->roles->first() : null;
    $roleTitle = $assignedRole ? $assignedRole->id : 0;

@endphp

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
<!-- #page:admin/enquiry_list/edit.blade -->
<form id="enquiry_list" >
      
        <!-- Route Selection -->
        <div class="card" id="choose_your_div">
            <div class="card-header">
                <p>Choose Your Type</p>
            </div>
            <div class="card-body row" style="gap:50px;">
                
                <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="loan_category" id="loan_category">
                        <option selected value="">Select</option>
                        <option  value="broker" {{ ($isEditable && $enquiry->loan_category) ? ($enquiry->loan_category == 'broker' ?'selected' : '') : '' }}>Broker</option>
                        <option  value="introducer" {{ ($isEditable && $enquiry->loan_category) ? ($enquiry->loan_category == 'introducer' ?'selected' : '') : '' }} >Introducer</option>
                    </select>
                    {{--
                    <input type="radio" name="loan_category" value="broker" id="broker_radio" {{  ($isEditable &&$enquiry->loan_category) ?  ($enquiry->loan_category == 'broker' ? 'checked' : '') : '' }}>
                    <input type="radio" name="loan_category" value="introducer" id="introducer_radio" {{ ($isEditable &&$enquiry->loan_category) ?  ($enquiry->loan_category == 'introducer' ? 'checked' : '') : '' }}>
                    <label for="broker_radio" class="declineButton" id="broker_btn">Broker</label>
                    <label for="introducer_radio" class="declineButton" id="introducer_btn">Introducer</label> --}}
               
                </div>
            </div>
        </div>

        <!-- Broker Form -->
        <div class="card" id="broker_form" style="{{  $isEditable &&  $enquiry->loan_category == 'broker' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Is the mortgage more than 80%?</h5>
                
                 <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="mortgage_status" id="mortgage_status">
                        <option selected value="">Select</option>
                        <option value="yes" {{ ($isEditable &&$enquiry->loan_category) ? ($enquiry->mortgage_status == 'yes' ?'selected' : '') : '' }}>Yes</option>
                        <option value="no" {{ ($isEditable &&$enquiry->loan_category) ?  ($enquiry->mortgage_status == 'no' ? 'selected' : '') : '' }}>No</option>
                    </select>
                </div>
                
                
            </div>
        </div>

        <!-- Mortgage Question Yes Result -->
        <div class="card" id="mortage_question_yes_result" style="{{   ($isEditable &&  $enquiry->mortgage_status == 'yes') ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Sorry, we can't process further. Kindly contact the admin.</h5>
              
            </div>
        </div>

        <!-- Mortgage Question No Result -->
        
        <div class="card" id="mortage_question_no_result" style="{{  ($isEditable &&   $enquiry->mortgage_status == 'no') ? 'display:block;' : 'display:none;' }}">
    <div class="card-header">
        <p>Loan Requirements</p>
    </div>
    <div class="card-body">

            <div class="row">
                {{--
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Choose the Loan Type</label>
                        <select class="form-control" name='loan_type' id="loan_type">
                            @foreach($loan_types as $id => $value)
                            <option  value="{{$id}}" {{ ($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->loan_category_type == $value ?'selected' : '') : '' }}>{{$value}}</option>
                            @endforeach
                            <!--<option selected value=''>Select</option>-->
                            <!--<option value='Purchase'>Purchase</option>-->
                            <!--<option value='Remortage'>Remortage</option>-->
                            <!--<option value='Second Charge'>Second Charge</option>-->
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div> --}}
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="purpose_loan">Purpose of Loan</label>
                        <select class="form-control" name='purpose_loan' id="purpose_loan">
                            <option selected value='' >Select</option>
                            <option value='Purchase Property' {{  ($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->purpose_loan == 'Purchase Property' ?'selected' : '') : '' }} >Purchase Property</option>
                            <option value='Purchase the Other' {{ ($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->purpose_loan == 'Purchase the Other' ?'selected' : '' ) : ''}}>Purchase the Other</option>
                            <option value='Debit Consolidation'{{ ($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->purpose_loan == 'Debit Consolidation' ?'selected' : '') : '' }}>Debit Consolidation</option>
                            <option value='Home Improvement' {{ ($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->purpose_loan == 'Home Improvement' ?'selected' : '') : '' }}>Home Improvement</option>
                            <option value='Capital Raise' {{($isEditable &&  $enquiry->loan_category_type ) ?  ($enquiry->purpose_loan == 'Capital Raise' ?'selected' : '') : '' }} >Capital Raise ( No an additional loan)</option>
                            <option value='Transfer of Equit' {{ ($isEditable &&  $enquiry->loan_category_type ) ? ( $enquiry->purpose_loan == 'Transfer of Equit' ?'selected' : '') : '' }}>Transfer of Equity</option>
                            <option value='Other' {{($isEditable &&  $enquiry->loan_category_type ) ?   ($enquiry->purpose_loan == 'Other'?'selected' : '') : '' }}>Other</option>
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="application_made">How is the application being made?</label>
                        <select class="form-control" name='application_made' id="application_made">
                            <option selected value=''>Select</option>
                            <option value='Personal Name' {{  ($isEditable &&  $enquiry->application_made) ?(  $enquiry->application_made == 'Personal Name' ?'selected' : '' ) : '' }}  >Personal Name</option>
                            <option value='Ltd. Company' {{  ($isEditable &&  $enquiry->application_made) ?(    $enquiry->application_made == 'Ltd. Company' ?'selected' : '' ) : '' }} >Ltd. Company</option>
                            <option value='LLP' {{ ($isEditable &&  $enquiry->application_made) ?(   $enquiry->application_made == 'LLP' ?'selected' : '' ) : '' }}  >LLP</option>
                            <option value='Trust' {{  ($isEditable &&  $enquiry->application_made) ?(  $enquiry->application_made == 'Trust' ?'selected' : '' ) : '' }} >Trust</option>
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">What is the required net loan amount?</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">£</div>
                            </div>
                            <input type="text" name='loan_amount' id ='loan_amount' value="{{ ($isEditable && $enquiry->loan_amount) ? ($enquiry->loan_amount ?? '') :  '' }}" class="form-control" oninput="validateInput(this)">
                            <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">What is the required term?</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <input type="text" name='term_year' id ='term_year' value="{{  ($isEditable && $enquiry->term_year) ? ($enquiry->term_year  ?? 0) : 0 }}"  class="form-control" oninput="validateInput(this)">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Years</div>
                                    </div>
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <input type="text" name='term_month' id ='term_month' value="{{  ($isEditable && $enquiry->term_month) ? ($enquiry->term_month  ?? 0) : 0 }}" class="form-control" oninput="validateInput(this)">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Months</div>
                                        <span class="error-message">This field is required.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label>Does the client or family members live or intend to live in 40% or more of the property?</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" {{ ( $isEditable &&  $enquiry->live_or_intent_property) ? ( $isEditable &&  $enquiry->live_or_intent_property == 'yes' ?'checked' : '' ) : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property" value="yes">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                        
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" {{ ($isEditable &&  $enquiry->live_or_intent_property) ?  ( $isEditable &&  $enquiry->live_or_intent_property == 'no' ?'checked' : '' ) : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property" value="no">
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                <span class="error-message">This field is required.</span>
                </div>
            </div>
    </div>
 
</div>

<div class="card"  id="introducer_form" style="{{ ($isEditable && (($enquiry->application_made == 'Personal Name' && $enquiry->mortgage_status == 'no') || ($enquiry->loan_category == 'introducer')) ) ?  'display:block' : 'display:none' }}">
    <div class="card-header">
        <p>Please Provide Contact Details for Your Client</p>
    </div>
    <div class="card-body">
      
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Title</label>
                        <select class="form-control" name='loan_category_type' id="loan_category_type">
                             @foreach($loan_types as $id => $value)
                            <option  value="{{$id}}" {{  $isEditable &&  $enquiry->loan_category_type == $id ?'selected' : '' }}>{{$value}}</option>
                            @endforeach
                        </select>
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client First Name</label>
                        <input type="text" name='client_first_name' id="client_first_name"  value="{{  ( $isEditable &&  $enquiry->client_first_name) ? ( $enquiry->client_first_name ?? '' ) : ''}}" class="form-control" placeholder="Enter First Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Last Name</label>
                        <input type="text" name='client_last_name' id="client_last_name" value="{{   ( $isEditable &&  $enquiry->client_last_name) ? ( $enquiry->client_last_name ?? '') : ''}}"  class="form-control" placeholder="Enter Last Name">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Mobile Telephone</label>
                        <input type="text" name='client_phone' id="client_phone"  value="{{ ( $isEditable &&  $enquiry->client_phone) ? ($enquiry->client_phone ?? '' ) : ''}}" class="form-control" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Email Address</label>
                        <input type="email" name='client_email' id="client_email" value="{{ ($isEditable &&  $enquiry->client_email) ? ( $enquiry->client_email ?? '' ) : ''}}"  class="form-control" placeholder="Enter Email Address">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Loan Amount</label>
                        <input type="text"  name='client_loan_amount' id="client_loan_amount"  value="{{ ($isEditable &&  $enquiry->client_loan_amount) ? ( $enquiry->client_loan_amount ?? '') : ''}}" class="form-control" placeholder="Enter Loan Amount" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Property Value</label>
                        <input type="text" name='client_propertity_value' id="client_propertity_value" value="{{ ($isEditable &&  $enquiry->client_propertity_value) ? (  $enquiry->client_propertity_value ?? '') : ''}}"    class="form-control" placeholder="Enter Property Value" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Any Other Notes?</label>
                        <input type="text" name='client_extra_comment' id="client_extra_comment" value="{{ ($isEditable &&  $enquiry->client_extra_comment) ? ( $enquiry->client_extra_comment ?? '' ) : ''}}"   class="form-control">
                        <!--<textarea class="form-control" rows="1"></textarea>-->
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                
            </div>
    
    <!--</div>          ($isEditable && $enquiry->application_made && $enquiry->application_made != "Personal Name") ?  (" style='display:block' " ): ($isEditable ??   '');-->
   
</div>

</div>





    <div class="card" id="introducer_company_form"  style="{{ ($isEditable && $enquiry->application_made != 'Personal Name' && $enquiry->mortgage_status == 'no'  ) ?  'display:block' : 'display:none' }}">
            <div class="card-header">
                <p>Please Provide Company and client Details</p>
            </div>
            <div class="card-body">
                <!--  -->
      
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_category_type2">Loan Type</label>
                        <select class="form-control" name='loan_category_type2' id="loan_category_type2"> 
                            @foreach($loan_types as $id => $value )
                             <option value ='{{$id ?? ''}}' {{  $isEditable &&  $enquiry->loan_category_type == $id ?'selected' : '' }} >{{$value ?? ''}}</option>
                            @endforeach
                            {{-- <option selected value=''>Select</option>
                            <option value ='Purchase'>Purchase</option>
                            <option value ='Remortage'>Remortage</option>
                            <option value ='Second Charge'>Second Charge</option> --}}
                        </select>
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
            </div>
                
            <!-- company details start-->
              <p>Company Details</p>
              <hr>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" name='company_name' id="company_name" class="form-control" value="{{  ($isEditable && $enquiry->company_name ) ? ($enquiry->company_name ?? '') : '' }}" placeholder="Enter Company Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_no">Company No.</label>
                        <input type="text" name='company_no' id="company_no" class="form-control" value="{{  ($isEditable && $enquiry->company_no ) ? ( $enquiry->company_no ?? '') : '' }}" placeholder="Enter Company NO.">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_address_1">Company Address Line 1</label>
                        <input type="text" name='company_address_1' id="company_address_1"  value="{{  ($isEditable && $enquiry->company_address_1 ) ? ( $enquiry->company_address_1 ?? '') : '' }}"  class="form-control" placeholder="Enter Company Address Line1">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_address_2">Company Address Line 2</label>
                        <input type="text" name='company_address_2' id="company_address_2" value="{{ ($isEditable && $enquiry->company_address_2 ) ? ( $enquiry->company_address_2 ?? '') : '' }}"  class="form-control" placeholder="Enter Company Address Line2">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
          </div>
                    <!-- company details end-->
                <!-- applicant  details start-->
                 <p>Applicant Details</p>
                <hr>
              <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_client_first_name">Client First Name</label>
                        <input type="text" name='company_client_first_name' id="company_client_first_name" value="{{  ($isEditable && $enquiry->client_first_name ) ? ($enquiry->client_first_name ?? '' ) : '' }}" class="form-control" placeholder="Enter First Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_client_last_name">Client Last Name</label>
                        <input type="text" name='company_client_last_name' id="company_client_last_name" value="{{ ($isEditable && $enquiry->client_last_name ) ? ( $enquiry->client_last_name ?? '' ) : '' }}" class="form-control" placeholder="Enter Last Name">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_client_phone">Client Mobile Telephone</label>
                        <input type="text" name='company_client_phone' id="company_client_phone" value="{{  ($isEditable && $enquiry->client_phone ) ? ($enquiry->client_phone ?? '' ) : '' }}" class="form-control" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="company_client_email">Client Email Address</label>
                        <input type="email" name='company_client_email' id="company_client_email"  value="{{ ($isEditable && $enquiry->client_email ) ? (  $enquiry->client_email ?? '' ) : ''}}" class="form-control" placeholder="Enter Email Address">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                 </div>
                   <!-- applicant  details end-->
                   
                    <!-- security Details start-->
                   <p>Security Details</p>
                        <hr>
                    <div id="security-details-container">
                        
                    <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_address_line1">Security Address Line1</label>
                                    <input type="text" name="company_security_address_line1" id = 'company_security_address_line1'  value ="{{ ($isEditable && $enquiry->company_security_address_line1 ) ? ($enquiry->company_security_address_line1 ?? '' ) : ''}}" class="form-control" placeholder="Enter Security Address Line1">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_address_line2">Security Address Line2</label>
                                    <input type="text" name="company_security_address_line2" id = 'company_security_address_line2'   value ="{{($isEditable && $enquiry->company_security_address_line2 ) ? ($enquiry->company_security_address_line2 ?? '' ) : ''}}" class="form-control" placeholder="Enter Security Address Line2">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                   </div>
            
                       <?php /*
                        @if( $isEditable && isset($enquiry->enquiryClients) && count($enquiry->enquiryClients) > 0 )
                        @php
                            $i = true;
                        @endphp
                        @foreach($enquiry->enquiryClients as $client)
                        <div class="row security-details">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_first_name">Security First Name</label>
                                    <input type="text" name="company_security_first_name[]"  value ="{{$client->name ?? ''}}" class="form-control company_security_first_name" placeholder="Enter First Name">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_last_name">Security Last Name</label>
                                    <input type="text" name="company_security_last_name[]"  value ="{{$client->last_name ?? ''}}" class="form-control" placeholder="Enter Last Name">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_phone">Security Mobile Telephone</label>
                                    <input type="text" name="company_security_phone[]" value ="{{$client->phone ?? ''}}" class="form-control company_security_phone" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_email">Security Email Address</label>
                                    <input type="email" name="company_security_email[]" value ="{{$client->email ?? ''}}" class="form-control company_security_email" placeholder="Enter Email Address">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                             @if(!$i)
                            <div class="col-sm-12"><button type="button" class="col-sm-2 mb-3 remove-btn btn btn-danger mt-3">Remove</button></div>
                            @endif
                        </div>
                         @php
                            $i = false;
                        @endphp
                        @endforeach
                        @else
                        
                        <div class="row security-details">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_first_name">Security First Name</label>
                                    <input type="text" name="company_security_first_name[]" class="form-control company_security_first_name" placeholder="Enter First Name">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_last_name">Security Last Name</label>
                                    <input type="text" name="company_security_last_name[]" class="form-control" placeholder="Enter Last Name">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_phone">Security Mobile Telephone</label>
                                    <input type="text" name="company_security_phone[]" class="form-control company_security_phone" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_security_email">Security Email Address</label>
                                    <input type="email" name="company_security_email[]" class="form-control company_security_email" placeholder="Enter Email Address">
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                        </div>
                        @endif */ ?>
                    </div>
                    {{-- <button type="button" id="add-security-details" class="btn btn-primary">Add Security Details</button> --}}
                <!-- security Details end-->
                    <!-- Other Details start-->
                <p>Other Details</p>
                    <hr>
                 <div class="row">
                     
                      <div class="col-sm-4">
                        <div class="form-group">
                            <label for="company_client_loan_amount">Loan Amount</label>
                            <input type="text" name='company_client_loan_amount' id="company_client_loan_amount" value='{{  ($isEditable && $enquiry->client_loan_amount ) ? ( $enquiry->client_loan_amount ?? '' ) : ''}}'  class="form-control" placeholder="Enter Loan Amount" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                   
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_type">Property Value</label>
                            <input type="text" name='company_client_propertity_value' value="{{ ($isEditable && $enquiry->client_propertity_value ) ? (  $enquiry->client_propertity_value ?? '' ) : ''}}" id="company_client_propertity_value"   class="form-control" placeholder="Enter Property Value" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_type">Any Other Notes?</label>
                            <input type="text" name='company_client_extra_comment' id="company_client_extra_comment" value="{{ ($isEditable && $enquiry->client_extra_comment ) ? ( $enquiry->client_extra_comment ?? '' ) : ''}}"  class="form-control">
                            <!--<textarea class="form-control" rows="1"></textarea>-->
                         <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    {{--
                    <div class="col-sm-12">
                        <div class="form-check">
                            <input class="form-check-input" name='comapny_agreement' @if($isEditable) checked @endif type="checkbox" valu='yes' id="comapny_agreement">
                            <label class="form-check-label" for="defaultCheck1">
                            I Agree to Terms and Conditions
                            </label>
                             <span class="error-message">This field is required.</span>
                            
                        </div>
                    </div> 
                    --}}
                </div>
                 </div>
                 {{--
                <!-- Other Details End-->
            <div class="card-footer text-right">
                <button type="button" class="btn btn-secondary" id="back_to_choose_div2">Back</button>
                <button type="button" class="btn btn-success" id="submit2">Submit</button>
            </div> --}}
                 
                 
        </div>
        
        
     <div class="card-footer text-right" id='button_hide' @if(!$isEditable) style='display:none;' @endif>
         @if($isEditable)
            <button type="button" class="btn btn-danger" id="Cancel">Cancel Enquiry</button>
         @endif
     
            <button type="button" class="btn btn-success" id="submit"> @if($isEditable && $roleTitle == 1) Move to Case Create @elseif($isEditable && $roleTitle != 1) Updated Enquiry @else Create Enquiry   @endif  </button>
        </div>

</form>



@endsection
@section('scripts')

<script>

$(document).ready(function(){
    let loading = $('.loading-overlay');

    $(document).on('change', '#loan_category', function() {
        var selectedRole = $(this).val();
        console.log("Selected role: " + selectedRole);
         

        if (selectedRole === 'broker') {
            
             var selectedStatus = $('#mortgage_status').val();
             
             if(selectedStatus == ''){
                 $('#broker_form').show();
                 $('#mortage_question_no_result,#introducer_form,#introducer_company_form,#mortage_question_yes_result,#button_hide').hide();
                 
             }else if(selectedStatus == 'yes'){
                 $('#mortage_question_yes_result,#broker_form').show();
                 $('#mortage_question_no_result,#introducer_form,#introducer_company_form,#button_hide').hide();
                 $('#loan_category option').prop('selected', false);
                $('#mortgage_status').find('option').prop('selected', false);
             }else if(selectedStatus == 'no'){
                 $('#mortage_question_no_result,#button_hide,#broker_form').show();
                 $('#mortage_question_yes_result').hide();
                 var application_made = $('#application_made').val();
                 if(application_made == 'Personal Name'){
                     
                    $('#introducer_form,#button_hide').show();
                    $('#introducer_company_form').hide();
                     
                 }else if (application_made == ''){
                      $('#introducer_form,#introducer_company_form,#button_hide').hide();
                 }else{
                     $('#introducer_form').hide();
                      $('#introducer_company_form,#button_hide').show();
                 }
                 
             }
            //  if(selectedStatus
            // $('#mortage_question_no_result').show();
            // $('#button_hide').show();
            
        } else if (selectedRole === 'introducer') {
            
            $('#mortage_question_no_result,#mortage_question_yes_result').hide();
            $('#introducer_company_form').hide();
            $('#button_hide').show();
            $('#broker_form').hide();
            $('#introducer_form').show();
        } else {
            $('#broker_form, #introducer_form').hide();
            $('#choose_your_div').show();
            $('#mortage_question_no_result').hide();
            $('#button_hide').hide();
            $('#broker_form').hide();
            $('#introducer_company_form').hide();
        }
    });
    
    $('#mortgage_status').change(function() {
        var selectedStatus = $(this).val();
        // console.log("Selected status: " + selectedStatus);

        if (selectedStatus === 'yes') {
            $("#broker_form,#introducer_form,#button_hide,#mortage_question_no_result,#introducer_form,#introducer_company_form").hide();
            $('#mortage_question_yes_result').show();
            $('#loan_category option').prop('selected', false);
            $(this).find('option').prop('selected', false);
            
        } else if (selectedStatus === 'no') {
            
            
            $("#broker_form").show();
            $('#mortage_question_no_result,#button_hide').show();
            
             var application_made = $('#application_made').val() || "{{ $isEditable &&  $enquiry->application_made ?? ''}}" ;
            
                if(application_made == 'Personal Name'){
                     
                    $('#introducer_form,#button_hide').show();
                    $('#introducer_company_form').hide();
                     
                 }else if (application_made == ''){
                      $('#introducer_form,#introducer_company_form,#button_hide').hide();
                 }else{
                     $('#introducer_form').hide();
                      $('#introducer_company_form,#button_hide').show();
                 }
            
            $('#mortage_question_yes_result').hide();
            // $('').hide();
        } else {
            $('#broker_form,#button_hide').show();
            $('#mortage_question_yes_result, #mortage_question_no_result, #introducer_form, #broker_form').hide();

        }
    });

    $('#go_back, #back_to_choose, #back_to_choose_div').click(function(e){
        e.preventDefault();
        $('#choose_your_div').show();
        $('#broker_form, #mortage_question_yes_result, #mortage_question_no_result, #introducer_form').hide();
        $('#loan_category').val('');
        $('#mortgage_status').val('');
    });
    
    {{--
    $(document).on('click','#add-security-details', function() {
        var $securityDetailsContainer = $('#security-details-container');
        var $originalSection = $securityDetailsContainer.find('.security-details').first();
        
        // Clone the original section
        var $clonedSection = $originalSection.clone();

        // Clear the values in the cloned inputs
        $clonedSection.find('input').val('');
        $clonedSection.find('.remove-btn').remove();
        $clonedSection.find('.text-danger').remove();

        // Append the cloned section to the container
        $securityDetailsContainer.append($clonedSection);
        $clonedSection.append(
                '<div class="col-sm-12"><button type="button" class="col-sm-2 mb-3 remove-btn btn btn-danger mt-3">Remove</button></div>'
            );
        
        
    
        
    });
    --}}
    
    
    $(document).on('change', "#application_made", function(){
        
       var $data = $(this).val()
        if($data){
            
            if($data != 'Personal Name'){
                $('#introducer_company_form,#button_hide').show();
                $('#introducer_form').hide()
            }else{
                $('#introducer_form,#button_hide').show()
                $('#introducer_company_form').hide();
            }
            
            
        }else{
             $('#introducer_form,#button_hide,#introducer_company_form').hide()
                
        }
        
    })
    
    

    // Common function to validate fields
    function validateFields(fieldArray) {
        var isValid = true;
        fieldArray.forEach(function(fieldId) {
            var field = $('#' + fieldId);
            if (!field.val() || field.val() === 'Select') {
                field.addClass('error');
                field.next('.error-message').show();
                isValid = false;
            } else {
                field.removeClass('error');
                field.next('.error-message').hide();
            }
        });
        return isValid;
    }

    // Validate co-applicant fields
    function validateSecurityDetails() {
        var isValid = true;
        $('.co_security_error').remove();
        $('.security-details').each(function() {
            var $this = $(this);
            var firstName = $this.find('.company_security_first_name').val().trim();
            var phone = $this.find('.company_security_phone').val().trim();
            var email = $this.find('.company_security_email').val().trim();

            if (!firstName) {
                $this.find('.company_security_first_name').after('<p class="text-danger co_security_error">Security name is required.</p>');
                isValid = false;
            }
            if (!phone) {
                $this.find('.company_security_phone').after('<p class="text-danger co_security_error">Security Phone is required.</p>');
                isValid = false;
            }
            if (!email) {
                $this.find('.company_security_email').after('<p class="text-danger co_security_error">Security Email is required.</p>');
                isValid = false;
            }
        });
        return isValid;
    }

    // Main click event handler
    $('#submit').click(function(e) {
        e.preventDefault();

        var selectedRole = $('#loan_category').val();
        if (selectedRole !== 'broker' && selectedRole !== 'introducer') {
            $('#choose_your_div').show();
            return;
        }

        var isValid = true;

        if (selectedRole === 'broker') {
            var selectedStatus = $('#mortgage_status').val();
            if (selectedStatus !== 'yes' && selectedStatus !== 'no') {
                $('#choose_your_div').show();
                return;
            }

            var brokerFieldArray = ['purpose_loan', 'application_made', 'loan_amount', 'term_year', 'term_month'];
            isValid = validateFields(brokerFieldArray) && isValid;

            if (!$('input[name="live_or_intent_property"]:checked').val()) {
                $('input[name="live_or_intent_property"]').addClass('error');
                $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').show();
                isValid = false;
            } else {
                $('input[name="live_or_intent_property"]').removeClass('error');
                $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').hide();
            }

            if ($('#application_made').val() === 'Personal Name') {
                var personalFieldArray = ['loan_category_type', 'client_first_name', 'client_last_name', 'client_phone', 'client_email', 'client_loan_amount', 'client_propertity_value'];
                isValid = validateFields(personalFieldArray) && isValid;
            } else {
                var companyFieldArray = ['loan_category_type2', 'company_name', 'company_no', 'company_address_1', 'company_client_first_name', 'company_client_phone', 'company_client_email','company_security_address_line1','company_client_loan_amount'];
                isValid = validateFields(companyFieldArray) && isValid;
               // isValid = validateSecurityDetails() && isValid;

                
            }
        } else if (selectedRole === 'introducer') {
            var introducerFieldArray = ['loan_category_type', 'client_first_name', 'client_last_name', 'client_phone', 'client_email', 'client_loan_amount', 'client_propertity_value'];
            isValid = validateFields(introducerFieldArray) && isValid;
        }

        if (isValid) {
            submitForm(loading);
        }else{
            Swal.fire("Error", "Fill the Requirment", "error");
        }
    });

    // Function to submit the form
    function submitForm(loading) {
        // Assuming you have a loading element
        loading.show();

        var formData = $('#enquiry_list').serialize();
        var $url = @if($isEditable && $enquiry->id) "{{ route('admin.enquiry_list.update') }}" @else  "{{ route('admin.enquiry_list.store') }}" @endif ;
        
        formData += '&' + $.param({ id: '{{ ($isEditable && $enquiry->id) ? ( $enquiry->id ?? '') : ''}}' });
        var id_ = "{{ $isEditable && $enquiry->id ? $enquiry->id : '' }}";
        $.ajax({
            url: $url,
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(response) {
                if (response.status) {
                     
                     if(!id_){
                        Swal.fire("success", 'Enquiry Created Successfully', "success");
                     }else{
                         
                        Swal.fire("success", 'Enquiry Updated Successfully', "success");
                     }
                    // Swal.fire({
                    //     position: 'top-end',
                    //     icon: 'success',
                    //     title: 'Enquiry List!',
                    //     text: 'Case Update Successfully',
                    //     showConfirmButton: false,
                    //     timer: 2000,
                    // });

                    $('#enquiry_list')[0].reset();
                    $('input[name="loan_category"], input[name="mortgage_status"]').prop('checked', false);
                    $('#introducer_form').hide();
                    $(".card,#button_hide").hide();
                    $('#choose_your_div').show();
                    $('input, select').removeClass('error');
                    $('.error-message').hide();
                    if (response.data) {
                        window.location = response.data;
                    }
                } else {
                    Swal.fire("Error", response.message || "Case List created Failed", "error");
                }
                loading.hide();
            },
            error: function() {
                Swal.fire("Error", "An unexpected error occurred.", "error");
                loading.hide();
            }
        });
    }
    
    
     $(document).on('click', '.remove-btn', function() {
            $(this).closest('.security-details').remove();
        });
        
        $('#Cancel').click(function(e) {
                e.preventDefault(); 
                var id_ = "{{ $isEditable && $enquiry->id ? $enquiry->id : '' }}";
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You  Want to remove Enquiry ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(frameby) {
                    if (frameby.value) {
                        loading.show()
                        $.ajax({
                            method: 'POST',
                            url: "{{ route('admin.enquiry_list.delete')}}",
                                headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                            data: {
                                id: id_,
                              
                            },
                            success: function(response) {

                                if (response.status && response.message) {

                                    Swal.fire('', response.message, 'success');

                                } else {
                                    Swal.fire('', response.message, 'error');
                                }
                                
                                if (response.data) {
                                     window.location = response.data;
                                 }

                                loading.hide()

                               

                            },
                            error: function(data) {
                                console.log('Error:', data);
                                loading.hide()
                            }
                        })

                    }
                })
                
            });
});

    
     function validateInput(input) {
        input.value = input.value
            .replace(/[^0-9.]/g, '')  // Remove any non-numeric and non-period characters
            .replace(/(\..*)\./g, '$1');  // Allow only one period
    }

</script>

@endsection