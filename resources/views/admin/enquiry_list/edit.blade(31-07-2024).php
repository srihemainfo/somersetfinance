@extends('layouts.admin')
@section('content')

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
<form id="enquiry_list" action="{{ route('admin.enquiry_list.update', $enquiry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Route Selection -->
        <div class="card" id="choose_your_div">
            <div class="card-header">
                <p>Choose Your Route</p>
            </div>
            <div class="card-body row" style="gap:50px;">
                
                <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="loan_category" id="loan_category">
                        <option selected value="">Select</option>
                        <option  value="broker" {{ $enquiry->loan_category == 'broker' ?'selected' : '' }}>Broker</option>
                        <option  value="introducer" {{ $enquiry->loan_category == 'introducer' ? 'selected' : '' }}>Introducer</option>
                    </select>
                    {{--
                    <input type="radio" name="loan_category" value="broker" id="broker_radio" {{ $enquiry->loan_category == 'broker' ? 'checked' : '' }}>
                    <input type="radio" name="loan_category" value="introducer" id="introducer_radio" {{ $enquiry->loan_category == 'introducer' ? 'checked' : '' }}>
                    <label for="broker_radio" class="declineButton" id="broker_btn">Broker</label>
                    <label for="introducer_radio" class="declineButton" id="introducer_btn">Introducer</label> --}}
               
                </div>
            </div>
        </div>

        <!-- Broker Form -->
        <div class="card" id="broker_form" style="{{ $enquiry->loan_category == 'broker' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Is the mortgage more than 80%?</h5>
                
                 <div class="form-group col-sm-4 col-md-4 col-xl-4 col-12">
                   
                    <select class="form-control" name="mortgage_status" id="mortgage_status">
                        <option selected value="">Select</option>
                        <option value="yes" {{ $enquiry->mortgage_status == 'yes' ?'selected' : '' }}>Yes</option>
                        <option value="no" {{ $enquiry->mortgage_status == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                
                {{--
                <div class="d-flex justify-content-center" style="gap:50px;">
                    <input type="radio" name="mortgage_status" value="yes" id="mortage_question_yes_radio" {{ $enquiry->mortgage_status == 'yes' ? 'checked' : '' }}>
                    <input type="radio" name="mortgage_status" value="no" id="mortage_question_no_radio" {{ $enquiry->mortgage_status == 'no' ? 'checked' : '' }}>
                    <label for="mortage_question_yes_radio" class="declineButton" id="mortage_question_yes">Yes</label>
                    <label for="mortage_question_no_radio" class="declineButton" id="mortage_question_no">No</label>
                </div>
                --}}
                
            </div>
        </div>

        <!-- Mortgage Question Yes Result -->
        <div class="card" id="mortage_question_yes_result" style="{{ $enquiry->mortgage_status == 'yes' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Sorry, we can't process further. Kindly contact the admin.</h5>
              
            </div>
        </div>

        <!-- Mortgage Question No Result -->
        
<div class="card" id="mortage_question_no_result" style="{{  $enquiry->mortgage_status == 'no' ? 'display:block;' : 'display:none;' }}">
    <div class="card-header">
        <p>Loan Requirements</p>
    </div>
    <div class="card-body">

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Choose the Loan Type</label>
                        <select class="form-control" name='loan_type' id="loan_type">
                            @foreach($loan_types as $id => $value)
                            <option  value="$value" {{ $enquiry->loan_category_type == $value ?'selected' : '' }}>{{$value}}</option>
                            @endforeach
                            <!--<option selected value=''>Select</option>-->
                            <!--<option value='Purchase'>Purchase</option>-->
                            <!--<option value='Remortage'>Remortage</option>-->
                            <!--<option value='Second Charge'>Second Charge</option>-->
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="purpose_loan">Purpose of Loan</label>
                        <select class="form-control" name='purpose_loan' id="purpose_loan">
                            <option selected value='' >Select</option>
                            <option value='Purchase Property' {{ $enquiry->purpose_loan == 'Purchase Property' ?'selected' : '' }} >Purchase Property</option>
                            <option value='Purchase the Other' {{ $enquiry->purpose_loan == 'Purchase the Other' ?'selected' : '' }}>Purchase the Other</option>
                            <option value='Debit Consolidation'{{ $enquiry->purpose_loan == 'Debit Consolidation' ?'selected' : '' }}>Debit Consolidation</option>
                            <option value='Home Improvement' {{ $enquiry->purpose_loan == 'Home Improvement' ?'selected' : '' }}>Home Improvement</option>
                            <option value='Capital Raise' {{ $enquiry->purpose_loan == 'Capital Raise' ?'selected' : '' }} >Capital Raise ( No an additional loan)</option>
                            <option value='Transfer of Equit' {{ $enquiry->purpose_loan == 'Transfer of Equit' ?'selected' : '' }}>Transfer of Equity</option>
                            <option value='Other' {{ $enquiry->purpose_loan == 'Other'?'selected' : '' }}>Other</option>
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="application_made">How is the application being made?</label>
                        <select class="form-control" name='application_made' id="application_made">
                            <option selected value=''>Select</option>
                            <option value='Personal Name' {{ $enquiry->application_made == 'Personal Name' ?'selected' : '' }}  >Personal Name</option>
                            <option value='Ltd. Company' {{ $enquiry->application_made == 'Ltd. Company' ?'selected' : '' }} >Ltd. Company</option>
                            <option value='LLP' {{ $enquiry->application_made == 'LLP' ?'selected' : '' }}  >LLP</option>
                            <option value='Trust' {{ $enquiry->application_made == 'Trust' ?'selected' : '' }} >Trust</option>
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
                            <input type="text" name='loan_amount' id ='loan_amount' value="{{$enquiry->loan_amount ??  ''}}" class="form-control" oninput="validateInput(this)">
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
                                    <input type="text" name='term_year' id ='term_year' value="{{$enquiry->term_year ?? ''}}"  class="form-control" oninput="validateInput(this)">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Years</div>
                                    </div>
                                    <span class="error-message">This field is required.</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <input type="text" name='term_month' id ='term_month' value="{{$enquiry->term_month ??  ''}}" class="form-control" oninput="validateInput(this)">
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
                        <input class="form-check-input" {{ $enquiry->live_or_intent_property == 'yes' ?'checked' : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property" value="yes">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                        
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" {{ $enquiry->live_or_intent_property == 'no' ?'checked' : '' }}  type="radio" name="live_or_intent_property" id="live_or_intent_property" value="no">
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                <span class="error-message">This field is required.</span>
                </div>
            </div>
    </div>
 
</div>

<div class="card" id="introducer_form" >
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
                            <option  value="$value" {{ $enquiry->loan_category_type == $value ?'selected' : '' }}>{{$value}}</option>
                            @endforeach
                        </select>
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client First Name</label>
                        <input type="text" name='client_first_name' id="client_first_name"  value="{{$enquiry->client_first_name ?? ''}}" class="form-control" placeholder="Enter First Name">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Last Name</label>
                        <input type="text" name='client_last_name' id="client_last_name" value="{{$enquiry->client_last_name ?? ''}}"  class="form-control" placeholder="Enter Last Name">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Mobile Telephone</label>
                        <input type="text" name='client_phone' id="client_phone"  value="{{$enquiry->client_phone ?? ''}}" class="form-control" placeholder="Enter Mobile Telephone" oninput="validateInput(this)">
                         <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Client Email Address</label>
                        <input type="email" name='client_email' id="client_email" value="{{$enquiry->client_email ?? ''}}"  class="form-control" placeholder="Enter Email Address">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Loan Amount</label>
                        <input type="text"  name='client_loan_amount' id="client_loan_amount"  value="{{$enquiry->client_loan_amount ?? ''}}" class="form-control" placeholder="Enter Loan Amount" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Property Value</label>
                        <input type="text" name='client_propertity_value' id="client_propertity_value" value="{{$enquiry->client_propertity_value ?? ''}}"    class="form-control" placeholder="Enter Property Value" oninput="validateInput(this)">
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="loan_type">Any Other Notes?</label>
                        <input type="text" name='client_extra_comment' id="client_extra_comment" value="{{$enquiry->client_extra_comment ?? ''}}"   class="form-control">
                        <!--<textarea class="form-control" rows="1"></textarea>-->
                     <span class="error-message">This field is required.</span>
                    </div>
                </div>
                
            </div>
    
    </div>
    <div class="card-footer text-right" id='button_hide'>
     
        <button type="button" class="btn btn-success" id="submit">Submit</button>
    </div>
</div>

  
    </form>



@endsection
@section('scripts')

<script>

$(document).ready(function(){
    let loading = $('.loading-overlay');
    //  $('#button_hide').hide();
    
    $(document).on('change', '#loan_category', function() {
        var selectedRole = $(this).val();
        console.log("Selected role: " + selectedRole);
         

        if (selectedRole === 'broker') {
            
            $('#broker_form').show();
            $('#mortage_question_no_result').show();
            $('#button_hide').show();
        } else if (selectedRole === 'introducer') {
            
            $('#mortage_question_no_result').hide();
            $('#introducer_form').show();
             $('#button_hide').show();
             $('#broker_form').hide();
        } else {
            $('#broker_form, #introducer_form').hide();
            $('#choose_your_div').show();
            $('#mortage_question_no_result').hide();
            $('#button_hide').show();
            $('#broker_form').show();
        }
    });
    
    $('#mortgage_status').change(function() {
        var selectedStatus = $(this).val();
        console.log("Selected status: " + selectedStatus);

        if (selectedStatus === 'yes') {
            $("#broker_form,#introducer_form,#button_hide,#mortage_question_no_result,#introducer_form").hide();
            $('#mortage_question_yes_result').show();
            $('#loan_category option').prop('selected', false);
            $(this).find('option').prop('selected', false);
        } else if (selectedStatus === 'no') {
            $("#broker_form").show();
            $('#mortage_question_no_result,#introducer_form,#button_hide').show();
            $('#mortage_question_yes_result').hide();
            $('').hide();
        } else {
            $('#broker_form,#button_hide').show();
            $('#mortage_question_yes_result, #mortage_question_no_result, #introducer_form, #broker_form').hide();

        }
    });

    $('#submit').click(function(e){
        e.preventDefault();

        var selectedRole = $('#loan_category').val();
        console.log("Selected role: " + selectedRole);

        if (selectedRole !== 'broker' && selectedRole !== 'introducer') {
            $('#choose_your_div').show();
            return;
        } 

        var isValid = true;

        if(selectedRole === 'broker'){
            var selectedStatus = $('#mortgage_status').val();
            if (selectedStatus !== 'yes' && selectedStatus !== 'no') {
                $('#choose_your_div').show();
                return;
            }

            var fieldArray = ['loan_type', 'purpose_loan', 'application_made', 'loan_amount', 'term_year', 'term_month'];
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

            if (!$('input[name="live_or_intent_property"]:checked').val()) {
                $('input[name="live_or_intent_property"]').addClass('error');
                $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').show();
                isValid = false;
            } else {
                $('input[name="live_or_intent_property"]').removeClass('error');
                $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').hide();
            }
        }
        
         // Array of field IDs to validate
        var fieldArray = ['loan_category_type', 'client_first_name', 'client_last_name', 'client_phone', 'client_email', 'client_loan_amount','client_propertity_value'];

        // Validate each field
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
        
        
        

        if (isValid) {
            
             var formData = $('#enquiry_list').serialize();
            console.log(formData);
            // $('#enquiry_list').submit();
        }
    });

    $('#go_back, #back_to_choose, #back_to_choose_div').click(function(e){
        e.preventDefault();
        $('#choose_your_div').show();
        $('#broker_form, #mortage_question_yes_result, #mortage_question_no_result, #introducer_form').hide();
        $('#loan_category').val('');
        $('#mortgage_status').val('');
    });
});

    
     function validateInput(input) {
        input.value = input.value
            .replace(/[^0-9.]/g, '')  // Remove any non-numeric and non-period characters
            .replace(/(\..*)\./g, '$1');  // Allow only one period
    }

</script>

@endsection