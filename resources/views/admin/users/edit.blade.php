@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        {{-- Form is handled via AJAX --}}
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="required" for="roles">Roles</label>
            <select class="form-control" name="roles" id="roles" required>
                <option value="">Select Role</option>
                @foreach ($roles as $id => $role)
                    <option value="{{ $id }}" {{ $user->roles[0]->id == $id ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="fname" value="{{ old('name', $user->name) }}" required>
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
            <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
        </div>

        <div class="form-group">
            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
        </div>

        <div id="partner_div" style="{{ $user->roles[0]->id == 2 ? 'display:block' : 'display:none;' }}">
            <div class="form-group required">
                <label class="required" for="phone">Phone</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="number" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>

            <div class="form-group required">
                <label class="required" for="company_address_1">Company Address Line 1</label>
                <input class="form-control {{ $errors->has('company_address_1') ? 'is-invalid' : '' }}" type="text" name="company_address_1" id="company_address_1" value="{{ old('company_address_1', $user->company_address_1) }}">
                @if ($errors->has('company_address_1'))
                    <span class="text-danger">{{ $errors->first('company_address_1') }}</span>
                @endif
            </div>

            <div class="form-group required">
                <label for="company_address_2">Company Address Line 2</label>
                <input class="form-control {{ $errors->has('company_address_2') ? 'is-invalid' : '' }}" type="text" name="company_address_2" id="company_address_2" value="{{ old('company_address_2', $user->company_address_2) }}">
                @if ($errors->has('company_address_2'))
                    <span class="text-danger">{{ $errors->first('company_address_2') }}</span>
                @endif
            </div>

            <div class="form-group required">
                <label class="required" for="company_phone">Company Phone</label>
                <input class="form-control {{ $errors->has('company_phone') ? 'is-invalid' : '' }}" type="number" name="company_phone" id="company_phone" value="{{ old('company_phone', $user->company_phone) }}">
                @if ($errors->has('company_phone'))
                    <span class="text-danger">{{ $errors->first('company_phone') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="file_path">Upload Partner Image</label>
                <input type="file" class="form-control {{ $errors->has('file_path') ? 'is-invalid' : '' }}" name="file_path" id="file_path" accept=".png, .jpg, .jpeg">
                @if ($errors->has('file_path'))
                    <span class="text-danger">{{ $errors->first('file_path') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="required" for="password">Change Password</label>
            <input class="form-control" type="password" name="password" id="password">
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
            <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
        </div>

        <div class="form-group">
            <button class="btn btn-outline-danger" id="btnsave">{{ trans('global.save') }}</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
var $loading = $('.loading-overlay')
$(document).ready(function() {
    $('#roles').change(function() {
        let roleValue = $(this).val();
        let phoneField = $('#phone');

        if (roleValue == 2) {
            $('#partner_div').show();
            phoneField.attr('required', true);
        } else {
            $('#partner_div').hide();
            phoneField.removeAttr('required');
        }
    });

    $('#btnsave').click(function(e) {
        e.preventDefault();

        let role = $('#roles').val();
        let fname = $('#fname').val().toUpperCase();
        let email = $('#email').val();
        let password = $('#password').val();
        let phone = $('#phone').val();
        let company_address_1 = $('#company_address_1').val();
        let company_address_2 = $('#company_address_2').val();
        let company_phone = $('#company_phone').val();

        let hasError = false;
        $('.form-group').removeClass('has-error');
        $('.error-message').remove();

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

        if (!role) {
            $('#roles').parent().addClass('has-error');
            $('#roles').after('<span class="error-message text-danger">Role is required.</span>');
            hasError = true;
        }

        if (role == 2) {
            if (!phone) {
                $('#phone').parent().addClass('has-error');
                $('#phone').after('<span class="error-message text-danger">Phone number is required for this role.</span>');
                hasError = true;
            }
            if (!company_address_1) {
                $('#company_address_1').parent().addClass('has-error');
                $('#company_address_1').after('<span class="error-message text-danger">Company Address is required for this role.</span>');
                hasError = true;
            }
            if (!company_phone) {
                $('#company_phone').parent().addClass('has-error');
                $('#company_phone').after('<span class="error-message text-danger">Company phone is required for this role.</span>');
                hasError = true;
            }
        }

        if (hasError) {
            Swal.fire('', 'Please correct the errors and try again.', 'error');
            return;
        }

        let formData = new FormData();
        formData.append('_method', 'PUT'); 
        formData.append('name', fname);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('role', role);

        if (role == 2) {
            formData.append('phone', phone);
            formData.append('company_address_1', company_address_1);
            formData.append('company_address_2', company_address_2);
            formData.append('company_phone', company_phone);
            if ($('#file_path').get(0).files.length > 0) {
                formData.append('file_path', $('#file_path').get(0).files[0]);
            }
        }

        $loading.show();

        $.ajax({
            url: "{{ route('admin.users.update', $user->id) }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $loading.hide();
                if (response.status) {
                    Swal.fire('', 'User Update successfully!', 'success').then(() => {
                        window.location.href = "{{ route('admin.users.index') }}";
                    });
                } else {
                    Swal.fire('', response.data || 'An error occurred.', 'error');
                }
            },
             error: function(jqXHR) {
                if (jqXHR.status === 422) {
                    // Display validation errors
                    let errors = jqXHR.responseJSON.errors;
                    let errorMessage = '';
        
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                        // Optionally, you can also highlight the invalid fields
                        $('input[name="' + key + '"], select[name="' + key + '"]').addClass('error');
                        $('input[name="' + key + '"], select[name="' + key + '"]').after('<span class="error-message" style="color:red;">' + value[0] + '</span>');
                    });
        
                    Swal.fire("Validation Error", errorMessage, "error");
                } else {
                    Swal.fire("Error", "An unexpected error occurred.", "error");
                }
                
                $loading.hide();
            }
        });
    });
});
</script>
@endsection
