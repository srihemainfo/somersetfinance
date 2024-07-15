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
                            <div class="row">
                                <div class="col-lg-4 col-6">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $sellvehiclecount }}</h3>
                                            <p>Enquiry</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.sellvehicle.index') }}" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-6">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $sellerEnquirecount }}<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Underwriting</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.seller_enquire.index') }}" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-6">

                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3 class="counter-value">{{ $buyerEnquirecount }}</h3>
                                            <p>Processing</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person-add"></i>
                                        </div>
                                        <a href="{{ route('admin.buyer_enquire.index') }}" class="small-box-footer">More
                                            info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-6">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3 class="counter-value">50</h3>
                                            <p>Completions</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.users.block_list') }}" class="small-box-footer">More info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-6">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3 class="counter-value">50</h3>
                                            <p>Completed</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person"></i>
                                        </div>
                                        <a href="{{ route('admin.users.block_list') }}" class="small-box-footer">More info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-2 col-6">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3 class="counter-value">0<sup
                                                    style="font-size: 20px"></sup></h3>
                                            <p>Fees Collection</p>
                                        </div>
                                        <div class="icon">
                                            <!--<i class="ion ion-person"></i>-->
                                            <i class="fas fa-rupee-sign"></i>
                                        </div>
                                        <a href="#"
                                            class="small-box-footer">More info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-lg-2 col-6">

                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3 class="counter-value"><pre> </pre></h3>
                                            <p>Events and Announcement</p>
                                        </div>
                                        <div class="icon">
                                            <!--<i class="ion ion-person"></i>-->
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More
                                            info
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div> --}}
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
