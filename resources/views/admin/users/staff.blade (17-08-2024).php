@php
    $type_id = auth()->user()->roles[0]->type_id;
    $key = ($type_id == 6) ? 'layouts.admin' : 'layouts.admin';
@endphp

@extends($key)

@section('content')
    @if (isset($user) && $user != '')
        <div class="pb-3" style="overflow:hidden;">
            <div class="bg-primary text-light student_label">
                <div style="padding-right:2%;">
                    <span style="margin-right: 10px;"> STAFF NAME: {{ $user->name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile">
                                    <div class="user-avatar">
                                        @if ($user->filePath ?? '' != '')
                                            <img class="uploaded_img" src="{{ asset($user->filePath) }}" alt="image">
                                        @else
                                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="">
                                        @endif
                                    </div>
                                    <h5 class="user-name">{{ $user->name ?? 'N/A' }}</h5>
                                    
                                    <h6 class="user-email">{{ $user->roles[0]->title ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="padding-left:0;">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mb-2 text-primary">Staff Info</h5>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" value="{{ $user->name ?? 'N/A'  }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="staffCode">Staff Code</label>
                                        <input type="text" class="form-control" id="staffCode" value="{{ $user->StaffCode ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                      
                                        <input type="text" class="form-control" id="role" value="{{ $user->roles[0]->title ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" value="{{ $user->email ?? 'N/A'  }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" value="{{ $user->phone ??  'N/A'  }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('partner_full_view_access')
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card" style="margin-top: 16px;">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Personal Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body view_more" style="display:none;">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gutters">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="name">First Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $user->name ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="staffCode">Staff Code</label>
                                                <input type="text" class="form-control" id="staffCode" name="staffCode" value="{{ $user->StaffCode ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="mobile_number">Phone</label>
                                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ $user->mobile_number ??  'N/A'  }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Business Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body view_more" style="display:none;">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="staffCode">StaffCode Code</label>
                                            <input type="text" class="form-control" id="staffCode" name="staffCode" value="{{ $user->StaffCode ??  'N/A'  }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endif
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const staff = [];

        let loader = document.getElementById("loader");

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");


<?php /*
        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.staff-edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // console.log(data);
                    let details = data.staff;
                    let staff = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        staff[details[i]] = null;
                    }
                    // console.log(staff)
                    $('input.autocomplete').autocomplete({
                        data: staff,
                    });
                    $('#loading').hide();

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    $('#loading').hide();
                }
            });

        } */ ?>

        function run(element) {
            if (/[0-9]/.test($(element).val()) && /[a-zA-Z]/.test($(element).val())) {
                var a = $(element).val();
                window.location.href = "{{ url('admin/teaching-staff-edge') }}/" + a;
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.add_plus').each(function(index) {
                $(this).click(function() {
                    $(this).toggleClass('rotated');
                    $('.view_more').eq(index).toggle();
                });
            });
        });
    </script>
@endsection
