@extends('layouts.non_techStaffHome')
@section('content')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

    <style>
        .firstnav {
            height: 300px;
            background: rgb(177, 180, 181);
            background: linear-gradient(0deg, rgba(177, 180, 181, 0.7203256302521008) 0%, rgba(255, 255, 255, 1) 100%);
            margin: -90px;
            /* z-index: -9999; */
        }

        .first-color {
            background: #387ADF;
            color: white;
        }

        .second-color {
            background: #FBA834;
            color: white;
        }

        .third-color {
            background: #333A73;
            color: white;
        }

        .fourth-color {
            background: #50C4ED;
            color: white;
        }
    </style>
    @if (auth()->user()->roles[0]->id == 9)
        <div class="row gutters">
            <div class=" col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner"style="height: 114px;">
                                        <h3 class="counter-value">00</h3>
                                        <p>Current Year Leader Board Score</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-warning">
                                    <div class="inner"style="height: 114px;">
                                        <h3 class="counter-value">44</h3>
                                        <p>Life Time Leader Board Score</p>
                                    </div>
                                    <div class="icon"style="height: 114px;">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner"style="height: 114px;">
                                        <h3 class="counter-value">150</h3>
                                        <p>Life Time Cafe Score</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-danger">
                                    <div class="inner"style="height: 114px;">
                                        <h3 class="counter-value">15</h3>
                                        <p>Taken Leave</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body" style="height: 351px;">
                        <div class="d-flex flex-column align-items-center text-center">
                            @if (
                                (isset($document->filePath) ? $document->filePath : '') != '' ||
                                    (isset($document->filePath) ? $document->filePath : '') != null)
                                <img class="uploaded_img rounded-circle" src="{{ asset($document->filePath) }}"
                                    alt="image">
                            @else
                                @if ($personal_details->gender == 'MALE' || $personal_details->gender == 'Male')
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                        class="rounded-circle" width="150">
                                @elseif($personal_details->gender == 'FEMALE' || $personal_details->gender == 'Female')
                                    <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="Admin"
                                        class="rounded-circle" width="150">
                                @else
                                    <img src="{{ asset('adminlogo/user-image.png') }}" alt="Admin" class="rounded-circle"
                                        width="150">
                                @endif
                            @endif

                            <div class="mt-3">
                                <h4>{{ isset($personal_details->name) ? $personal_details->name : '' }}
                                    {{ isset($personal_details->last_name) ? $personal_details->last_name : '' }}
                                </h4>
                                <p class="text-secondary mb-1">
                                    {{ isset($teaching_staffs->Designation) ? $teaching_staffs->Designation : '' }}
                                </p>
                                <p class="text-secondary mb-1">Address :</p>
                                <div class="text-muted font-size-sm">
                                    {{ isset($Address->room_no_and_street) ? $Address->room_no_and_street : '' }},
                                    {{ isset($Address->area_name) ? $Address->area_name : '' }},
                                    {{ isset($Address->district) ? $Address->district : '' }},
                                    {{ isset($Address->state) ? $Address->state : '' }},
                                    {{ isset($Address->country) ? $Address->country : '' }}</div>
                                <div class="text-muted font-size-sm"> PIN Code :
                                    {{ isset($Address->pincode) ? $Address->pincode : '' }}</div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <div class="card ">
                    <div class="card-body" style="">
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media">
                                    <label>Birthday :&nbsp;</label>
                                    <p>{{ isset($personal_details->dob) ? date('jS F Y', strtotime($personal_details->dob)) : '' }}
                                    </p>

                                </div>
                                <div class="media">
                                    <label>Age :&nbsp;</label>
                                    <p>{{ isset($personal_details->age) ? $personal_details->age : '' }}</p>
                                </div>
                                <div class="media">
                                    <label>Staff Code :&nbsp;</label>
                                    <p>{{ isset($personal_details->StaffCode) ? $personal_details->StaffCode : '' }}
                                    </p>
                                </div>
                                <div class="media">
                                    <label>Department :&nbsp;</label>
                                    <p>{{ isset($personal_details->Dept) ? $personal_details->Dept : '' }}</p>
                                </div>
                                <div class="media">
                                    <label>Date Of Joining :&nbsp;</label>
                                    <p>{{ isset($personal_details->DOJ) ? $personal_details->DOJ : '' }}</< /p>
                                </div>
                                <div class="media">
                                    <label>Total Experience :&nbsp;</label>
                                    <p>{{ isset($personal_details->TotalExperience) ? $personal_details->TotalExperience : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="media">
                                    <label>E-mail :&nbsp;</label>
                                    <p>{{ isset($personal_details->email) ? $personal_details->email : '' }}</p>
                                </div>
                                <div class="media">
                                    <label>Phone :&nbsp;</label>
                                    <p>{{ isset($personal_details->mobile_number) ? $personal_details->mobile_number : '' }}
                                    </p>
                                </div>
                                <div class="media">
                                    <label>Religion :&nbsp;</label>
                                    <p>{{ isset($personal_details->Religion) ? $personal_details->Religion->name : '' }}
                                    </p>
                                </div>
                                <div class="media">
                                    <label>Highest Degree :&nbsp;</label>
                                    <p>{{ isset($personal_details->HighestDegree) ? $personal_details->HighestDegree : '' }}
                                    </p>
                                </div>
                                <div class="media">
                                    <label>BiometricID :&nbsp;</label>
                                    <p>{{ isset($personal_details->BiometricID) ? $personal_details->BiometricID : '' }}
                                    </p>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>




            </div>
        </div>

    @endif

@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

    <script>
        $(document).ready(function() {
            $('.counter-value').each(function() {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 3500,
                    easing: 'swing',
                    step: function(now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
        });
    </script>
@endsection
