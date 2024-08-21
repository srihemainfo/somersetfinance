@extends('layouts.admin')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    @php
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        if ($user) {
            $assignedRole = $user->roles->first();

            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            } else {
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp


    @if ($roleTitle == 1)
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Dashboard
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="row justify-content-center">
                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Enquiry }}</h3>
                                            <p>Enquiry</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                      {{--  <a href="{{ route('admin.sellvehicle.index') }}" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a> --}}
                                    </div>
                                </div>

                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Underwriting }}<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Underwriting</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                      
                                    </div>
                                </div>

                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Processing }}</h3>
                                            <p>Processing</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Completions  }}</h3>
                                            <p>Completions</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                     
                                    </div>
                                </div>

                                <div class="col-lg-2 col-4">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3 class="counter-value">{{$statusCounts->Completed }}</h3>
                                            <p>Completed</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                     
                                    </div>
                                </div>

                              
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="card">
                              <div class="card-header cursor-pointer" id="open_cases_heading" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="mb-0">
                                  <button class="btn">
                                    Open Cases
                                  </button>
                                </h5>
                              </div>
                        
                              <div id="collapseOne" class="collapse show" aria-labelledby="open_cases_heading" data-parent="#accordionFlushExample">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header cursor-pointer" id="completed_cases_heading" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h5 class="mb-0">
                                  <button class="btn collapsed">
                                    Completed Cases
                                  </button>
                                </h5>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="completed_cases_heading" data-parent="#accordionFlushExample">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header cursor-pointer" id="canceled_cases_heading" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <h5 class="mb-0">
                                  <button class="btn collapsed">
                                    Canceled Cases
                                  </button>
                                </h5>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="canceled_cases_heading" data-parent="#accordionFlushExample">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="content">
                <div class="col-lg-6 col-6">

                    <div class="card">
                        {{-- <div class="card-header">
                    Chart
                </div> --}}

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                             <div>
                        <canvas id="myChart"></canvas>
                    </div> 
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endif
    
    @if ($roleTitle == 2)
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Dashboard
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-4 col-6">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Processing  }}</h3>
                                            <p>Processing</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                    </div>
                                         {{--    <a href="{{ route('admin.sellvehicle.index') }}" class="small-box-footer">More
                                            info 
                                            <i class="fas fa-arrow-circle-right"></i></a>--}}
                                    </div>
                                </div>

                                <div class="col-lg-4 col-6">

                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $statusCounts->Completed }}<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Completed</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas nav-icon fa-vote-yea"></i>
                                        </div>
                                       {{--  <a href="{{ route('admin.seller_enquire.index') }}" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile">
                                    <div class="user-avatar d-flex justify-content-center">
                                      
                                        @if ($user->file_path ?? '')
                                            <img class="uploaded_img" src="{{ asset('partner_images/'.$user->file_path) }}" alt="image" id='image_tag'>
                                        @else
                                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="">
                                        @endif
                                    </div>
                                    
                                    <button type="button" title="Edit Profile Image" id='profile_Image' class="btn_borderless " data-user-id="{{ $user->id ?? 'N/a' }}">
                                                <i class="far fa-edit"></i>
                                            </button>
                                    
                                    
                                    <h5 class="user-name">{{ $user->name ?? 'N/A' }}</h5>
                                    
                                    <h6 class="user-email">{{ ucwords($user->roles[0]->title) ?? 'N/A' }}</h6>
                                </div>
                                <div class="user-profile text-left">
                                    <div>
                                        <div class="me-2 fw-medium my-2">
                                            <i class="far fa-envelope"></i> {{ $user->email ??  'N/A'  }}
                                        </div>
                                        <div class="me-2 fw-medium my-2">
                                            <i class="fas fa-phone"></i> {{ $user->phone ??  'N/A'  }}
                                        </div>
                                        {{--
                                        <div class="me-2 fw-medium my-2">
                                            <i class="fas fa-map-marker-alt"></i> Thirukoilure, Villupuram
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="padding-left:0;">
                    <div class="card h-100">
                        <div class="row">
                            <div class="col-sm-6 pr-0">
                                <div class="card-header">
                                    Business Details Info
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Name :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->name ?? 'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Email :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->email ?? 'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Company Phone :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->company_phone ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       
                                        <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Company Address Line1 :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->company_address_1 ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                        <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Company Address Line2 :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->company_address_2 ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                         {{--
                                         <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Company Phone:</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->company_phone ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                      
                                      
                                       
                                     
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Role :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->role ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Age :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->age ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Experience :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->experience ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       --}}
                                    </ul>
                                </div>
                            </div>
                            {{-- 
                            <div class="col-sm-6 pl-0">
                                <div class="card-header">
                                    Contact Info
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Name :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->name ?? 'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Email :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->email ?? 'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Phone :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->phone ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Role :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->role ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Age :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->age ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                       <li class="list-group-item">
                                          <div class="d-flex flex-wrap align-items-center">
                                             <div class="me-2 fw-medium">Experience :</div>
                                             <span class="fs-12 text-muted">&nbsp;{{ $user->experience ??  'N/A'  }}</span>
                                          </div>
                                       </li>
                                    </ul>
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        
        
        <div class="row gutters-sm">
        </div>
        
        @include('admin.users.partials.add_profile')
        
        @if (session('status'))
            <div class="content">
                <div class="col-lg-6 col-6">

                    <div class="card">
                        {{-- <div class="card-header">
                    Chart
                </div> --}}

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                             <div>
                        <canvas id="myChart"></canvas>
                    </div> 
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endif
    @can('calender_show_access')
        {{-- @if ($check != 'empty')
            <style>
                .color-box {
                    width: 18px;
                    height: 18px;
                }
            </style>
            <div class="col-12">
                <div class="card">
                    <div style="padding: 10px" class="d-flex flex-wrap justify-content-between align-items-center">
                        <strong class="mb-2">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</strong>
                        <strong class="mb-2">{{ $year }}</strong>
                        <div class="d-flex flex-wrap">
                            <div class="d-flex align-items-center mr-3">
                                <div class="color-box" style="background-color: #FFD5D6;"></div>
                                <div class="ml-2">Holiday</div>
                            </div>
                            <div class="d-flex align-items-center mr-3">
                                <div class="color-box" style="background-color: #007bff7a;"></div>
                                <div class="ml-2">No order Day</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="color-box" style="background-color: #17a2b8;"></div>
                                <div class="ml-2">Today</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="padding: .5rem;">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    @foreach ($weekdays as $weekday)
                                        <th class="text-center table-primary">{{ $weekday }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                        <td></td>
                                    @endfor

                                    @for ($day = 1; $day <= $numDays; $day++)
                                        @php
                                            $currentDate = DateTime::createFromFormat(
                                                'Y-m-d',
                                                $year . '-' . $month . '-' . $day,
                                            );
                                            $isCurrentDate = $currentDate->format('Y-m-d') === date('Y-m-d');
                                            $eventDayOrder = null;
                                        @endphp

                                        @foreach ($events as $event)
                                            @php
                                                $eventDate = new DateTime($event->date);

                                                if ($currentDate->format('Y-m-d') === $eventDate->format('Y-m-d')) {
                                                    $eventDayOrder = $event->dayorder;
                                                    break;
                                                }
                                            @endphp
                                        @endforeach

                                        @if (($day + $firstDayOfWeek - 1) % 7 === 0)
                                </tr>
                                <tr>
        @endif

        <td
            style="
                                        text-align: center;
                                        {{ $isCurrentDate ? 'background-color: #17a2b8;' : '' }}
                                        {{ $eventDayOrder == 0 && !$isCurrentDate ? 'background-color: ;' : '' }}
                                        {{ $eventDayOrder == 1 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 2 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 3 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 4 && !$isCurrentDate ? 'background-color: #FFD5D6;' : '' }}
                                        {{ $eventDayOrder == 5 && !$isCurrentDate ? 'background-color: #007bff7a;' : '' }}">
            @if ($eventDayOrder == 0)
                <span style="color: rgb(5, 5, 5)">{{ $day }}</span>
            @elseif ($eventDayOrder == 1 || $eventDayOrder == 2 || $eventDayOrder == 3)
                <span>{{ $day }}</span>
            @else
                {{ $day }}
            @endif
        </td>
        @endfor

        @while (($day + $firstDayOfWeek - 1) % 7 !== 0)
            <td></td>
            @php $day++; @endphp
        @endwhile
        </tr>
        </tbody>
        </table>
        </div>
        </div>
        </div>
        @endif --}}
    @endcan

@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script>
    
    var loader = $('.loading-overlay');
    @if($roleTitle == 2)
        $(document).ready(function() {
             $(document).on('click', '#profile_Image', function(){
                 
                $('.error-message').remove();
                $('input, select').removeClass('error');
                $('#form-modal').modal('show')
        
            }) 
            
            $(document).on('click', '#saveBtn', function(e) {
                e.preventDefault();
            
            var file_path = $('#file_path').get(0).files[0] == undefined ? '' : $('#file_path').get(0).files[0] ;
                let formData = new FormData();
                formData.append('file_path', file_path);
            
                loader.show();
            
                $.ajax({
                    url: "{{ route('admin.users.profile_update') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        loader.show();
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('#customerForm').trigger("reset");
                            $('#form-modal').modal('hide');
            
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Updated',
                                text: 'Your Profile Updated successfully',
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            
                            $('.error-message').remove();
                            $('input, select').removeClass('error');
                            
                            $('#image_tag').attr('src', '{{ asset('partner_images') }}/' + response.data);

                        }
            
                        loader.hide();
                    },
                    error: function(jqXHR) {
                        if (jqXHR.status === 422) {
                            // Display validation errors
                            let errors = jqXHR.responseJSON.errors;
                            let errorMessage = '';
            
                            // Remove existing error messages
                            $('.error-message').remove();
                            $('input, select').removeClass('error');
            
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
            
                        loader.hide();
                    }
                });
});

            
            
         });
         
         
    // $(document).on('click', '#saveBtn', function(e){
              
    //         e.preventDefault();
            
    //         let formData = new FormData();
    //         formData.append('file_path', $('#file_path').get(0).files[0]);

    //      loader.show();
    //         $.ajax({
    //             url: "{{ route('admin.users.profile_update') }}",
    //              method: "POST",
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 },
    //                 data: formData,
    //                 processData: false,
    //                 contentType: false,
    //             beforeSend: function() { // Corrected typo here
    //                 loader.show();
    //             },
    //             success: function(response) {
                   
    //               if (response.status == 200) {
    //                     $('#customerForm').trigger("reset");
    //                     $('#form-modal').modal('hide');
                        
    //                         Swal.fire({
    //                             position: 'top-end',
    //                             icon: 'success',
    //                             title: 'Updated',
    //                             text: 'Your Profile Updated successfully',
    //                             showConfirmButton: false,
    //                             timer: 2000,
    //                         });
                            
    //                          $('#form-modal').modal('hide')
                        
    //                 }
        
    //                 loader.hide(); // Remove duplicate
    //             },
    //                  error: function(jqXHR) {
    //                     if (jqXHR.status === 422) {
    //                         // Display validation errors
    //                         let errors = jqXHR.responseJSON.errors;
    //                         let errorMessage = '';
                
    //                         $.each(errors, function(key, value) {
    //                             errorMessage += value[0] + '<br>';
    //                             // Optionally, you can also highlight the invalid fields
    //                             $('input[name="' + key + '"], select[name="' + key + '"]').addClass('error');
    //                             $('input[name="' + key + '"], select[name="' + key + '"]').after('<span class="error-message" style="color:red;">' + value[0] + '</span>');
    //                         });
                
    //                         Swal.fire("Validation Error", errorMessage, "error");
    //                     } else {
    //                         Swal.fire("Error", "An unexpected error occurred.", "error");
    //                     }
                        
    //                     $loading.hide();
    //             });
    // });
         
         
    
        @endif
    </script>
    
    
    
    {{-- <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            events = {!! json_encode($events) !!};
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: events,
                eventBackgroundColor: '#4fc3f7'
            })
        });
    </script> --}}
    {{-- <script src="your-js-file.js"></script> --}}

     {{-- <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
    </script>  --}}
@endsection
