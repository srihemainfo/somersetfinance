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
            <div class="card-body d-flex justify-content-center" style="gap:50px;">
                 <div class="col-sm-4 col-md-12 col-xl-4 col-md-4">
                    <select class="form-control" name="loan_category" id="loan_category">
                        <option selected value="">Select</option>
                        <option value="broker" {{ $enquiry->loan_category == 'broker' ?'selected' : '' }}>Purchase</option>
                        <option value="introducer" {{ $enquiry->loan_category == 'introducer' ? 'selected' : '' }}>Remortage</option>
                    </select>
            </div>
                     
                     
                {{--
                <input type="radio" name="loan_category" value="broker" id="broker_radio" {{ $enquiry->loan_category == 'broker' ? 'checked' : '' }}>
                <input type="radio" name="loan_category" value="introducer" id="introducer_radio" {{ $enquiry->loan_category == 'introducer' ? 'checked' : '' }}>
                <label for="broker_radio" class="declineButton" id="broker_btn">Broker</label>
                <label for="introducer_radio" class="declineButton" id="introducer_btn">Introducer</label>
                
                --}}
            </div>
        </div>

        <!-- Broker Form -->
        <div class="card" id="broker_form" style="{{ $enquiry->loan_category == 'broker' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Is the mortgage more than 80%?</h5>
                <div class="d-flex justify-content-center" style="gap:50px;">
                    <input type="radio" name="mortgage_status" value="yes" id="mortage_question_yes_radio" {{ $enquiry->mortgage_status == 'yes' ? 'checked' : '' }}>
                    <input type="radio" name="mortgage_status" value="no" id="mortage_question_no_radio" {{ $enquiry->mortgage_status == 'no' ? 'checked' : '' }}>
                    <label for="mortage_question_yes_radio" class="declineButton" id="mortage_question_yes">Yes</label>
                    <label for="mortage_question_no_radio" class="declineButton" id="mortage_question_no">No</label>
                </div>
            </div>
        </div>

        <!-- Mortgage Question Yes Result -->
        <div class="card" id="mortage_question_yes_result" style="{{ $enquiry->mortgage_status == 'yes' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Answer the few Questions</p>
            </div>
            <div class="card-body">
                <h5 class="text-info">Sorry, we can't process further. Kindly contact the admin.</h5>
                <div class="d-flex justify-content-center">
                    <button class="declineButton" id="go_back">Go Back</button>
                </div>
            </div>
        </div>

        <!-- Mortgage Question No Result -->
        <div class="card" id="mortage_question_no_result" style="{{ $enquiry->mortgage_status == 'no' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Loan Requirements</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_type">Choose the Loan Type</label>
                            <select class="form-control" name="loan_type" id="loan_type">
                                <option selected value="">Select</option>
                                <option value="Purchase" {{ $enquiry->loan_type == 'Purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="Remortage" {{ $enquiry->loan_type == 'Remortage' ? 'selected' : '' }}>Remortage</option>
                                <option value="Second Charge" {{ $enquiry->loan_type == 'Second Charge' ? 'selected' : '' }}>Second Charge</option>
                            </select>
                            <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    <!-- Other fields... -->
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-secondary" id="back_to_choose">Back</button>
                <button type="submit" class="btn btn-success" id="proceed_to_next">Update</button>
            </div>
        </div>

        <!-- Introducer Form -->
        <div class="card" id="introducer_form" style="{{ $enquiry->loan_category == 'introducer' ? 'display:block;' : 'display:none;' }}">
            <div class="card-header">
                <p>Please Provide Contact Details for Your Client</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="loan_category_type">Title</label>
                            <select class="form-control" name="loan_category_type" id="loan_category_type">
                                <option selected value="">Select</option>
                                <option value="Purchase" {{ $enquiry->loan_category_type == 'Purchase' ? 'selected' : '' }}>Purchase</option>
                                <option value="Remortage" {{ $enquiry->loan_category_type == 'Remortage' ? 'selected' : '' }}>Remortage</option>
                                <option value="Second Charge" {{ $enquiry->loan_category_type == 'Second Charge' ? 'selected' : '' }}>Second Charge</option>
                            </select>
                            <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    <!-- Other fields... -->
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-secondary" id="back_to_choose_div">Back</button>
                <button type="submit" class="btn btn-success" id="submit">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanCategoryRadios = document.querySelectorAll('input[name="loan_category"]');
    const brokerForm = document.getElementById('broker_form');
    const introducerForm = document.getElementById('introducer_form');
    const mortgageStatusYesResult = document.getElementById('mortage_question_yes_result');
    const mortgageStatusNoResult = document.getElementById('mortage_question_no_result');
    
    loanCategoryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'broker') {
                brokerForm.style.display = 'block';
                introducerForm.style.display = 'none';
            } else {
                brokerForm.style.display = 'none';
                introducerForm.style.display = 'block';
            }
        });
    });

    const mortgageStatusRadios = document.querySelectorAll('input[name="mortgage_status"]');
    mortgageStatusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes') {
                mortgageStatusYesResult.style.display = 'block';
                mortgageStatusNoResult.style.display = 'none';
            } else {
                mortgageStatusYesResult.style.display = 'none';
                mortgageStatusNoResult.style.display = 'block';
            }
        });
    });

    document.getElementById('back_to_choose').addEventListener('click', function() {
        brokerForm.style.display = 'none';
        introducerForm.style.display = 'none';
        document.getElementById('choose_your_div').style.display = 'block';
    });

    document.getElementById('back_to_choose_div').addEventListener('click', function() {
        introducerForm.style.display = 'none';
        document.getElementById('choose_your_div').style.display = 'block';
    });

    document.getElementById('go_back').addEventListener('click', function() {
        mortgageStatusYesResult.style.display = 'none';
        document.getElementById('choose_your_div').style.display = 'block';
    });
});
</script>


@endsection
@section('scripts')

<script>
{{--

$(document).ready(function(){
    
    let loading = $('.loading-overlay')
    
    $('input[name="loan_category"]').change(function() {
        var selectedRole = $('input[name="loan_category"]:checked').val();
        // console.log("Selected role: " + selectedRole);

        if (selectedRole === 'broker') {
            $("#choose_your_div").hide();
            $('#broker_form').show();
        } else if (selectedRole === 'introducer') {
            $("#choose_your_div").hide();
            $('#introducer_form').show();
        }
    });
    
    

    // Trigger the change event when the labels are clicked
    $('#broker_btn').click(function() {
        $('#broker_radio').prop('checked', true).trigger('change');
    });

    $('#introducer_btn').click(function() {
        $('#introducer_radio').prop('checked', true).trigger('change');
    });
    
    
    
    $('input[name="mortgage_status"').change(function() {
        var selectedRole = $('input[name="mortgage_status"]:checked').val();
        console.log(selectedRole);
        if (selectedRole === 'yes') {
            
             $("#broker_form").hide();
            $('#mortage_question_yes_result').show();
           
        } else if (selectedRole === 'no') {
                $("#broker_form").hide();
                $('#mortage_question_no_result').show();
        }else{
            $('#choose_your_div').show();
        }
    });
    
    
    
    
    
    // $("#broker_btn").click(function(){
    //     $("#choose_your_div").hide();
    //     $('#broker_form').show();
    // });
    
    // $("#mortage_question_yes").click(function(){
    //     $("#broker_form").hide();
    //     $('#mortage_question_yes_result').show();
    // });
    
    // $("#introducer_btn").click(function(){
    //     $("#choose_your_div").hide();
    //     $('#introducer_form').show();
    // });
    
    // $("#mortage_question_no").click(function(){
    //     $("#broker_form").hide();
    //     $('#mortage_question_no_result').show();
    // });
    
    $("#proceed_to_next").click(function(e){
        
        e.preventDefault();
        
        var selectedRole = $('input[name="loan_category"]:checked').val();
        console.log(selectedRole);
        
        if (selectedRole != 'broker' && selectedRole != 'introducer') {
            $('#choose_your_div').show();
            return;
        } 
        
        
        var selectedRole = $('input[name="mortgage_status"]:checked').val();
          if (selectedRole != 'yes' && selectedRole != 'no') {
            $('#choose_your_div').show();
            return;
        }
        
       var isValid = true;

        // Array of field IDs to validate
        var fieldArray = ['loan_type', 'purpose_loan', 'application_made', 'loan_amount', 'term_year', 'term_month'];

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
        
        
        // Validate radio buttons
        if (!$('input[name="live_or_intent_property"]:checked').val()) {
            $('input[name="live_or_intent_property"]').addClass('error');
            $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').show();
            isValid = false;
        } else {
            $('input[name="live_or_intent_property"]').removeClass('error');
            $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').hide();
        }
        
        

        // If the form is valid, proceed with the next steps
        if (isValid) {
           $('#introducer_form').show();
           $('#mortage_question_no_result').hide();
        } else {
             $('#introducer_form').hide();
             $('#mortage_question_no_result').show();
            
        }
        
       
        
        
        
        
    });
    
    $("#go_back").click(function(e){
        e.preventDefault();
        
        $('input[name="loan_category"],input[name="mortgage_status"] ').prop('checked', false);

        $("#mortage_question_yes_result").hide();
        $('#choose_your_div').show();
    });
    
    $("#back_to_choose").click(function(e){
        e.preventDefault()
        
        $('input[name="loan_category"],input[name="mortgage_status"] ').prop('checked', false);
        
        $("#mortage_question_no_result").hide();
        $('#choose_your_div').show();
        
    });
    
    $("#back_to_choose_div").click(function(e){
         e.preventDefault()
        $("#introducer_form").hide();
        $('#choose_your_div').show();
    });
    
     
    
     $("#submit").click(function(e){
         
          e.preventDefault();
        
        var selectedRole = $('input[name="loan_category"]:checked').val();
        console.log(selectedRole);
        
        if (selectedRole != 'broker' && selectedRole != 'introducer') {
            $('#choose_your_div').show();
            return;
        } 
        
           var isValid = true;
        if(selectedRole == 'broker'){
            
            
            var selectedRole = $('input[name="mortgage_status"]:checked').val();
             if (selectedRole != 'yes' && selectedRole != 'no') {
                $('#choose_your_div').show();
                return;
            }
        
    
            // Array of field IDs to validate
            var fieldArray = ['loan_type', 'purpose_loan', 'application_made', 'loan_amount', 'term_year', 'term_month'];
    
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
        
        
        // Validate radio buttons
        if (!$('input[name="live_or_intent_property"]:checked').val()) {
            $('input[name="live_or_intent_property"]').addClass('error');
            $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').show();
            isValid = false;
        } else {
            $('input[name="live_or_intent_property"]').removeClass('error');
            $('input[name="live_or_intent_property"]').closest('.form-check-inline').parent().find('.error-message').hide();
        }
        
        if(!isValid){
             $('#mortage_question_no_result').show();
              $('#introducer_form').hide();
             
        }
        
        }
        
        
            
            // var isValid = true;

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
        
        
        // Validate the checkbox
        var checkbox = $('#agreement');
        if (!checkbox.is(':checked')) {
            checkbox.addClass('error');
            checkbox.closest('.form-check').find('.error-message').show();
            isValid = false;
        } else {
            checkbox.removeClass('error');
            checkbox.closest('.form-check').find('.error-message').hide();
        }
        
        
            
    
        
        
        // If the form is valid, proceed with the next steps
        if (isValid) {
            
            loading.show();
            
            
             var formData = $('#enquiry_list').serialize();
             
                $.ajax({
                    url: "{{ route('admin.enquiry_list.store')}}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    // processData: false,
                    // contentType: false,
                    success: function(response) {
                        if (response.status) {
                            
                            
                
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'New Case Create Successfully',
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            
                             $('#enquiry_list')[0].reset();
                             $('input[name="loan_category"], input[name="mortgage_status"]').prop('checked', false);
                             $('#introducer_form').hide();
                            $(".card").hide();
                            $('#choose_your_div').show();
                            $('input, select').removeClass('error');
                            $('.error-message').hide();
                            
                              loading.hide();
                            
                          
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
             
             
             
        } else {
             $('#introducer_form').show();
            //  $('#mortage_question_no_result').show();
            
        }
         
         
     });
     
     // Go back to choose your route
    $("#go_back, #back_to_choose, #back_to_choose_div").click(function(e) {
        e.preventDefault();
        $('input[name="loan_category"], input[name="mortgage_status"]').prop('checked', false);
        $(".card").hide();
        $('#choose_your_div').show();
        $('input, select').removeClass('error');
        $('.error-message').hide();
    });
    
});

     function validateInput(input) {
        input.value = input.value
            .replace(/[^0-9.]/g, '')  // Remove any non-numeric and non-period characters
            .replace(/(\..*)\./g, '$1');  // Allow only one period
    }
    --}}

</script>

@endsection