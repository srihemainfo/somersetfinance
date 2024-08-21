@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>
        <div class="card-body" id="one">
            <div>

                <div class="form-group required">
                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <select id="myDropdown" class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}"
                        name="roles" required>
                        <option value="">Select Role</option>

                        @foreach ($working_as as $i => $type)
                        <option value="{{ $i }}">{{ $type }}</option>
                    @endforeach
                    </select>

                </div>
              

                <div class="form-group"  id="namefull">
                    <label class="required" for="name">Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        style="text-transform:uppercase;" type="text" name="fullname" id="fname"
                        value="{{ old('fullname', '') }}">
                    @if ($errors->has('fullname'))
                        <span class="text-danger">{{ $errors->first('fullname') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                
                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                </div>
                
                 <div id='partner_div' style='display:none;'>
                    
                     <div class="form-group required">
                    <label class="required" for="Phone">Phone</label>
                     <input class="form-control {{ $errors->has('Phone') ? 'is-invalid' : '' }}"
                        type="number" name="Phone" id="Phone"
                        value="{{ old('Phone', '') }}">
                    @if ($errors->has('Phone'))
                        <span class="text-danger">{{ $errors->first('Phone') }}</span>
                    @endif
                    

                </div>
                
                 <div class="form-group required">
                    <label class="required" for="company_address_1">Company Address Line 1</label>
                     <input class="form-control {{ $errors->has('company_address_1') ? 'is-invalid' : '' }}"
                         type="company_address_1" name="company_address_1" id="company_address_1"
                        value="{{ old('company_address_1', '') }}">
                    @if ($errors->has('company_address_1'))
                        <span class="text-danger">{{ $errors->first('company_address_1') }}</span>
                    @endif
                    

                </div>
                
                 <div class="form-group required">
                    <label for="company_address_2">Company Address Line 2</label>
                     <input class="form-control {{ $errors->has('company_address_2') ? 'is-invalid' : '' }}"
                         type="company_address_2" name="company_address_2" id="company_address_2"
                        value="{{ old('company_address_2', '') }}">
                    @if ($errors->has('company_address_2'))
                        <span class="text-danger">{{ $errors->first('company_address_2') }}</span>
                    @endif
                    

                </div>
                
                 <div class="form-group required">
                    <label class="required" for="company_phone">Company Phone</label>
                     <input class="form-control {{ $errors->has('company_phone') ? 'is-invalid' : '' }}"
                         type="number" name="company_address_2" id="company_phone"
                        value="{{ old('company_phone', '') }}">
                    @if ($errors->has('company_phone'))
                        <span class="text-danger">{{ $errors->first('company_phone') }}</span>
                    @endif
                    

                </div>
                
                
                <div class="form-group">
                    <label for="file_path">Upload partner Image</label>
                    <input type="file" class="form-control {{ $errors->has('file_path') ? 'is-invalid' : '' }}" name="file_path" id="file_path" accept=".png, .jpg, .jpeg">
                    @if ($errors->has('file_path'))
                        <span class="text-danger">{{ $errors->first('file_path') }}</span>
                    @endif
                </div>
                    
                </div>
                
                

                <div class="form-group main">
                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                        name="password" id="password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>
                
               
                
                

               

                <div class="form-group">
                    <button class="btn btn-outline-danger" id="btnsave">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>

        let $loading = $('.loading-overlay')
        const depart = [];
        // window.onload = function() {
        //     $("select").select2()

        //     let dept = $("#Dept");
        //     for (let i = 0; i < dept.find('option').length; i++) {
        //         let option = {
        //             text: $.trim($(dept.find('option')[i]).text()),
        //             value: $(dept.find('option')[i]).val()
        //         };
        //         depart.push(option);
        //     }
        // }
        
           $('#myDropdown').change(function() {
               
            let roleValue = $(this).val()
            let phoneField = $('#Phone');
             
            if(roleValue == 2){
                $('#partner_div').show();
                phoneField.attr('required', true);
                console.log('test');
                
            }else{
                  $('#partner_div').hide();
                  phoneField.removeAttr('required');
            }
               
               
               
               
           });

//         $('#myDropdown').change(function() {
//             let roleType = $('#role_type').val()
//             let role = $(this).find('option:selected').text()
//             let roleValue = $(this).val()

//             if (roleType == 1 || roleType == 3) {

//                 $("#Designation").val(role);
//                 $(".staff").show();
//                 // $(".dept").show();
//               //  $(".hostel").hide();
//                 $(".doj").show();
//                 // let dept = $('#Dept')
//                 // dept.empty()
//                 // $(depart).each(function(index, data) {
//                 //     if (data.text != 'CIVIL' && data.text != 'ADMIN') {
//                 //         dept.append(`<option value="${data.value}">${data.text}</option>`)

//                 //     }
//                 // })
//                 $('.main').hide();
//                 $('.phone').show();
//                 $('#namefull').hide();
//                // $('.studentDiv').hide();


//             } else if (roleType == 2 || roleType == 4 || roleType == 5) {
//                 // console.log(roleValue, roleType);
//                 if (roleValue == 9) {

//                     $("#Designation").val(role);
//                     $(".staff").show();
//                     // $(".dept").hide();
//                   //  $(".hostel").hide();
//                     $(".doj").show();
//                     $('.main').hide();
//                     $('.phone').show();
//                     $('#namefull').hide();
//                   //  $('.studentDiv').hide();
//                 } else if (roleType == 4) {
//                     $("#Designation").val(role);
//                     $(".staff").show();
//                     // $(".dept").show();
//                   //  $(".hostel").hide();
//                     $(".doj").show();
//                     // let dept2 = $('#Dept')
//                     // dept2.empty()
//                     // $(depart).each(function(index, data) {
//                     //     if (data.text == 'ADMIN') {
//                     //         dept2.prepend(`<option value="">Please Select</option>`)
//                     //         dept2.append(`<option value="${data.value}">${data.text}</option>`)


//                     //     }
//                     // })
//                     $('.main').hide();
//                     $('.phone').show();
//                     $('#namefull').hide();
//                   //  $('.studentDiv').hide();
//                 } else if (roleType == 5) {
//                     $("#Designation").val(role);
//                     $(".staff").show();
//                     // $(".dept").show();
//                    // $(".hostel").hide();
//                     $(".doj").show();
//                     // let dept1 = $('#Dept')
//                     // dept1.empty()
//                     // $(depart).each(function(index, data) {
//                     //     if (data.text == 'CIVIL') {
//                     //         dept1.prepend(`<option value="">Please Select</option>`)
//                     //         dept1.append(`<option value="${data.value}">${data.text}</option>`)


//                     //     }
//                     // })
//                     $('.main').hide();
//                     $('.phone').show();
//                     $('#namefull').hide();
// //$('.studentDiv').hide();

//                 } else {
//                     $("#Designation").val(role);
//                     $(".staff").show();
//                     // $(".dept").show();
//                 //    $(".hostel").hide();
//                     $(".doj").show();
//                     // let dept3 = $('#Dept')
//                     // dept3.empty()
//                     // $(depart).each(function(index, data) {
//                     //     if (data.text != 'CIVIL' && data.text != 'ADMIN') {
//                     //         dept3.append(`<option value="${data.value}">${data.text}</option>`)

//                     //     }
//                     // })
//                     $('.main').hide();
//                     $('.phone').show();
//                     $('#namefull').hide();
//                    // $('.studentDiv').hide();
//                 }
//             } else {
//                 // console.log(roleValue);
//                 if (roleValue == 5) {
//                     $(".staff").hide();
//                     $('.main').hide();
//                     // $(".dept").show();
//                    // $(".hostel").hide();
//                     $(".doj").hide();
//                     $('.phone').show();
//                     $('#namefull').hide();
//                   //  $('.studentDiv').show();
//                 } else if (roleValue == 20) {
//                     $('#namefull').show();
//                     $('.main').show();
//                     $(".staff").hide();
//                     $('.phone').hide();
//                     // $(".dept").hide();
//                   //  $(".hostel").show();
//                     $(".doj").hide();
//                   //  $('.studentDiv').hide();
//                 } else {
//                     $('#namefull').show();
//                     $('.main').show();
//                     $(".staff").hide();
//                     $('.phone').hide();
//                     // $(".dept").hide();
//                    // $(".hostel").hide();
//                     $(".doj").hide();
//                  //  $('.studentDiv').hide();
//                 }

//             }
//         })

        $(document).ready(function() {

            @if ($errors->count() > 0)
                $(window).on('load', function() {
                    $("#myDropdown").trigger("change");

                });
            @endif

        });

        // $('#role_type').change(function() {
        //     $('#namefull').hide();
        //     $('.main').hide();
        //     $(".staff").hide();
        //     $('.phone').hide();
        //     // $(".dept").hide();
        //     $(".doj").hide();

        //    // $('.studentDiv').hide();
        //     let type = $(this).val()
        //     let roles = $("#myDropdown")
        //     roles.empty()
        //     roles.html(`<option value="">Loading...</option>`)
        //     resetinputs()

        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('admin.users.fetch_role') }}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             'type': type
        //         },

        //         success: function(data) {
        //             let roles = $("#myDropdown")
        //             roles.empty()
        //             roles.prepend(`<option value="">Please Select</option>`)
        //             $.each(data, function(index, role) {
        //                 roles.append(`<option value="${index}">${role}</option>`)
        //             })
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             if (jqXHR.status) {
        //                 if (jqXHR.status == 500) {
        //                     Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
        //                 } else {
        //                     Swal.fire('', jqXHR.status, 'error');
        //                 }
        //             } else if (textStatus) {
        //                 Swal.fire('', textStatus, 'error');
        //             } else {
        //                 Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                     "error");
        //             }
        //         }
        //     });
        // })

        function resetinputs() {

            $('#name').val('')
            $('#fname').val('')
            // $('#firstname').val('')
            // $('#last_name').val('')
            // $('#Dept').val('')
            // $('#Designation').val('')
            // $('#StaffCode').val('')
            $('#email').val('')
            $('#password').val('')
            // $('#register_no').val('')
            // $('#enroll_master_id').val('')
            // $('#rollNumber').val('')
            // $('#phone').val('')
        }

        // $('#btnsave').click(function() {
        //     // let role_type = $('#role_type').val()
        //     let role = $('#myDropdown').val()
        //     // let name = $('#name').val().toUpperCase()
        //     let fname = $('#fname').val().toUpperCase()
        //     // let firstname = $('#firstname').val().toUpperCase()
        //     // let last_name = $('#last_name').val().toUpperCase()
        //     // let Dept = $('#Dept option:selected').text()
        //     let Designation = $('#Designation').val()
        //     let StaffCode = $('#StaffCode').val()
        //     let email = $('#email').val()
        //   //  let hostel = $('#hostel').val()
        //     let doj = $('#doj').val()
        //     // console.log(Dept);
        //     let password = $('#password').val()
        //     let register_no = $('#register_no').val()
        //     let enroll_master_id = $('#enroll_master_id').val()
        //     let rollNumber = $('#rollNumber').val()
        //     let phone = $('#phone').val()



        //     var data = ''


        //         if (fname != '' && email != '' && password != '' && role != '' ) {
        //             data = {
        //                 'fname': fname,
        //                 'email': email,
        //                 // 'Dept': Dept,
        //                 'password': password,
        //                 'role': role,
        //                 //  'hostel': hostel

        //             }
        //         }
                    
        //             if(role ==2){
        //                 $data = '';
                        
        //                 if (phone!= '') {
        //                 data = {
        //                     'fname': fname,
        //                     'email': email,
        //                     // 'Dept': Dept,
        //                     'password': password,
        //                     'role': role,
        //                     phone : phone
        //                     //  'hostel': hostel
    
        //                 }
        //             }
                    
                    
                    
                    
        //         }



        //         if (data != '') {

        //             $loading.show();
        //             $.ajax({
        //                 url: "{{ route('admin.users.store') }}",
        //                 method: "POST",
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 data: data,
        //                 success: function(response) {

        //                     let status = response.status;
        //                     let data = response.data;
        //                     if (status == true) {
        //                         Swal.fire('', data, 'success');
        //                         window.location.href = "{{ route('admin.users.index') }}";
        //                     } else {
        //                         Swal.fire('', data, 'error');
        //                     }

        //                     $loading.hide();
        //                 },
        //                 error: function(jqXHR, textStatus, errorThrown) {
        //                     if (jqXHR.status) {
        //                         if (jqXHR.status == 500) {
        //                             Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
        //                         } else {
        //                             Swal.fire('', jqXHR.status, 'error');
        //                         }
        //                     } else if (textStatus) {
        //                         Swal.fire('', textStatus, 'error');
        //                     } else {
        //                         Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
        //                             "error");
        //                     }

        //                     $loading.hide();
        //                 }
        //             })
        //         } else {
        //             Swal.fire('', "Enter all Details", 'error');
        //         }
        //     });
        
        $('#btnsave').click(function(e) {
        e.preventDefault();

        // Get form values
        let role = $('#myDropdown').val();
        let fname = $('#fname').val().toUpperCase();
        let email = $('#email').val();
        let password = $('#password').val();
        let phone = $('#Phone').val(); // Corrected id from phone to Phone
        let company_address_1 = $('#company_address_1').val();
        let company_address_2 = $('#company_address_2').val();
        let company_phone = $('#company_phone').val();

        // Initialize error flag
        let hasError = false;

        // Clear previous errors
        $('.form-group').removeClass('has-error');
        $('.error-message').remove();

        // Validate form fields
        if (!fname) {
            $('#fname').parent().addClass('has-error');
            $('#fname').after('<span class="error-message text-danger">Name is required.</span>');
            hasError = true;
        }

        if (!email) {
            $('#email').parent().addClass('has-error');
            $('#email').after('<span class="error-message text-danger">Email is required.</span>');
            hasError = true;
        }

        if (!password) {
            $('#password').parent().addClass('has-error');
            $('#password').after('<span class="error-message text-danger">Password is required.</span>');
            hasError = true;
        }

        if (!role) {
            $('#myDropdown').parent().addClass('has-error');
            $('#myDropdown').after('<span class="error-message text-danger">Role is required.</span>');
            hasError = true;
        }

        if (role == 2 && !phone) {
            $('#Phone').parent().addClass('has-error');
            $('#Phone').after('<span class="error-message text-danger">Phone number is required for this role.</span>');
            hasError = true;
        }
        
        if (role == 2 && !company_address_1) {
            $('#company_address_1').parent().addClass('has-error');
            $('#company_address_1').after('<span class="error-message text-danger">Company Address is required for this role.</span>');
            hasError = true;
        }
        
         if (role == 2 && !company_phone) {
            $('#company_phone').parent().addClass('has-error');
            $('#company_phone').after('<span class="error-message text-danger">Company phone is required for this role.</span>');
            hasError = true;
        }
        
        //company_address_1

        if (hasError) {
            Swal.fire('', 'Please correct the errors and try again.', 'error');
            return;
        }

        // // Data to send
        // let data = {
        //     'fname': fname,
        //     'email': email,
        //     'password': password,
        //     'role': role
        // };
        
        let formData = new FormData();
        formData.append('fname', fname);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('role', role);
        

        if (role == 2) {
            
            formData.append('phone', phone);
            formData.append('company_address_1', company_address_1);
            formData.append('company_address_2', company_address_2);
            formData.append('company_phone', company_phone);
            formData.append('file_path', $('#file_path').get(0).files[0]);
            // data.phone = phone;
            // data.company_address_1 = company_address_1;
            // data.company_address_2 = company_address_2;
            // data.company_phone = company_phone;
        }

        // Show loading indicator
        $loading.show();

        $.ajax({
            url: "{{ route('admin.users.store') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
             processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    Swal.fire('', 'User saved successfully!', 'success');
                    window.location.href = "{{ route('admin.users.index') }}";
                } else {
                    Swal.fire('', response.data || 'An error occurred.', 'error');
                }
                $loading.hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire('', 'An error occurred: ' + textStatus, 'error');
                $loading.hide();
            }
        });
    });




    </script>
@endsection
