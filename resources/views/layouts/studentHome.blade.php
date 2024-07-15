<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SOMERSET FINANCIAL</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('adminlogo/favicon.png') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
    <style>
        .bell-item a {
            width: 287px;
            text-wrap: wrap;
        }

        .bell-item {
            border-bottom: 1px solid #dfdfdf;
        }

        .rollDiv {
            height: 333px;
            overflow-y: scroll;
        }


        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, 0), rgba(0, 0, 0, 0));
            background: -webkit-radial-gradient(rgba(20, 20, 20, 0), rgba(0, 0, 0, 0));
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 2s infinite linear;
            -moz-animation: spinner 2s infinite linear;
            -ms-animation: spinner 2s infinite linear;
            -o-animation: spinner 2s infinite linear;
            animation: spinner 2s infinite linear;
            border-radius: 0.5em;
            border: 2px solid lightblue;
            /* Change the border color to light blue */
            box-shadow: lightblue 1.5em 0 0 0, lightblue 1.1em 1.1em 0 0, lightblue 0 1.5em 0 0, lightblue -1.1em 1.1em 0 0, lightblue -1.5em 0 0 0, lightblue -1.1em -1.1em 0 0, lightblue 0 -1.5em 0 0, lightblue 1.1em -1.1em 0 0;
            /* Change the box-shadow color to light blue */
        }


        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -moz-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -moz-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            @if (count(config('panel.available_languages', [])) > 1)
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach (config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item"
                                    href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }}
                                    ({{ $langName }})
                                </a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            @endif
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown notifications-menu">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        <i class="far fa-bell"></i>
                        @php
                            $alertsCount = \Auth::user()
                                ->userUserAlerts()
                                ->where('read', false)
                                ->count();
                        @endphp
                        @if ($alertsCount > 0)
                            <span class="badge badge-warning navbar-badge">
                                {{ $alertsCount }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu rollDiv dropdown-menu-lg dropdown-menu-right">
                        @if (count(
                                $alerts = \Auth::user()->userUserAlerts()->withPivot('read')->orderBy('created_at', 'ASC')->get()->reverse()) > 0)
                            @foreach ($alerts as $alert)
                                <div class="dropdown-item  bell-item">
                                    <a href="{{ $alert->alert_link ? $alert->alert_link : '#' }}" target="_blank"
                                        rel="noopener noreferrer">
                                        @if ($alert->pivot->read === 0)
                                            <strong>
                                        @endif
                                        {{ $alert->alert_text }}
                                        @if ($alert->pivot->read === 0)
                                            </strong>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                {{ trans('global.no_alerts') }}
                            </div>
                        @endif
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown notifications-menu">
                    <a href="#" class="nav-link nav_prof_label" data-toggle="dropdown" style="color:white; background-color: #0d5181;">
                        <i class="fa fa-user"></i>
                        <span style="margin-left:0.75rem;">{{ auth()->user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <ul style="list-style-type:none;padding:0;">
                            <li> <a href="{{ url('admin/students/' . auth()->user()->id . '/Profile-view') }}"
                                    class="dropdown-item"> My Profile </a></li>
                            <li> <a href="#" class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                    Logout </a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
            <!-- Brand Logo -->
            <a href="#" class="brand-link" style="background-color: rgb(255, 255, 255)">
                <span class="brand-text font-weight-light">
                    <img src="{{ asset('adminlogo/logo.png') }}" alt="" width="100%">
                </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <style>
                        .search-input-container {
                            position: relative;
                            overflow: auto;
                        }

                        .search-input-container input[type="search"] {
                            padding: 7px 7px 7px 47px;
                            width: 100%;
                            background: #ededed url(https://static.tumblr.com/ftv85bp/MIXmud4tx/search-icon.png) no-repeat 9px center;
                            border: solid 1px #ccc;
                            border-bottom-left-radius: 25px;
                            border-top-left-radius: 25px;
                            transition: all .5s;
                        }

                        .search-input-container input[type="search"]:focus {
                            width: 100%;
                            background-color: #fff;
                            border-color: #007bff;
                            box-shadow: 0 0 5px rgba(109, 207, 246, .5);
                            outline: none;
                        }
                    </style>

                    <div class="input-field" id="demo-2">
                        <div class="search-input-container">
                            <input type="search" id="searchInput" placeholder="Search..." class="menu_searcher "
                                autocomplete="off" value="">
                        </div>
                    </div>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false" id="list" style="padding-bottom:50px;">
                        <li class="nav-item" style="margin-top:0.5rem;">
                            <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                                href="{{ route('admin.home') }}">
                                <i class="fas fa-fw fa-tachometer-alt nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.dashboard') }}
                                </p>
                            </a>
                        </li>
                        {{-- @can('student_access')
                            <li class="nav-item">

                                <a href="{{ url('admin/students/' .auth()->user()->id . '/Profile-view') }}"
                                    class="nav-link {{ request()->is('admin/students') || request()->is('admin/students/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-cogs">

                                    </i>
                                    <p>
                                        My Profile
                                    </p>
                                </a>
                            </li>
                        @endcan --}}
                        <li class="nav-item">

                            <a href="{{ url('admin/student-time-table/' . auth()->user()->id) }}"
                                class="nav-link {{ request()->is('admin/student-time-table') || request()->is('admin/student-time-table/*') ? 'active' : '' }}">
                                <i class="fa-fw nav-icon fas fa-calendar-alt">

                                </i>
                                <p>
                                    My Time Table
                                </p>
                            </a>
                        </li>
                        @can('my_subject_registration_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.subject-registration.student') }}"
                                    class="nav-link {{ request()->is('admin/subject-registration/student') || request()->is('admin/subject-registration*') ? 'active' : '' }}">

                                    <i class="fa-fw nav-icon fas fa-book-open">

                                    </i>
                                    <p>
                                        My Subjects
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @can('leave_access_student')
                            <li class="nav-item">
                                <a href="{{ route('admin.student-request-leaves.index', ['user_name_id' => auth()->user()->id, 'name' => auth()->user()->name]) }}"
                                    class="nav-link {{ request()->is('admin/student-request-leaves') || request()->is('admin/student-request-leaves/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-file-alt">

                                    </i>
                                    <p>
                                        Apply Leave / OD
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @can('student_personal_attendance')
                            <li class="nav-item">
                                <a href="{{ route('admin.student-personal-attendance.report') }}"
                                    class="nav-link {{ request()->is('admin/student-personal-attendance') || request()->is('admin/student-personal-attendance/*') || request()->is('admin/subject-attendance-report/show/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-id-badge">
                                    </i>
                                    <p>
                                        Attendance
                                    </p>
                                </a>
                            </li>
                        @endcan


                        <li class="nav-item">
                            <a href="{{ route('admin.student-apply-certificate.stu_index') }}"
                                class="nav-link {{ request()->is('admin/student-apply-certificate/stu_index') || request()->is('admin/student-apply-certificate/*') ? 'active' : '' }}">
                                <i class="fa-fw nav-icon fas fa-address-card">
                                </i>
                                <p>
                                    Apply Certificates
                                </p>
                            </a>
                        </li>
                        @can('student_cat_mark_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.student-cat-mark.statement') }}"
                                    class="nav-link {{ request()->is('admin/student-cat-mark') || request()->is('admin/student-cat-mark/*') || request()->is('admin/student-cat-mark/show/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-clipboard-check">
                                    </i>
                                    <p>
                                        CAT Mark
                                    </p>
                                </a>
                            </li>
                        @endcan

                        @can('student_lab_mark_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.student_lab_mark.statement') }}"
                                    class="nav-link {{ request()->is('admin/student_lab_mark') || request()->is('admin/student_lab_mark/*') || request()->is('admin/student_lab_mark/show/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-clipboard-check">
                                    </i>
                                    <p>
                                        LAB Mark
                                    </p>
                                </a>
                            </li>
                        @endcan

                        @can('student_assignment_mark_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.student_assignment_mark.statement') }}"
                                class="nav-link {{ request()->is('admin/assignment/student/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-clipboard-check">
                                    </i>
                                    <p>
                                        Assignment Mark
                                    </p>
                                </a>
                            </li>
                        @endcan

                        @can('student_grade_mark_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.student_grade_mark.statement') }}"
                                class="nav-link {{ request()->is('admin/grade/student/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-clipboard-check">
                                    </i>
                                    <p>
                                       Grade Book
                                    </p>
                                </a>
                            </li>
                        @endcan


                        <li class="nav-item">
                            <a href="{{ route('admin.fee.stu_index') }}"
                                class="nav-link {{ request()->is('admin/fee*') || request()->is('admin/collect-fee*') ? 'active' : '' }}">
                                <i class="fa-fw nav-icon fas fa-money-check-alt">
                                </i>
                                <p>
                                    Academic Fee
                                </p>
                            </a>
                        </li>
                        @php($unread = \App\Models\QaTopic::unreadCount())
                        <li class="nav-item">
                            <a href="{{ route('admin.messenger.index') }}"
                                class="{{ request()->is('admin/messenger') || request()->is('admin/messenger/*') ? 'active' : '' }} nav-link">
                                <i class="fa-fw fa fa-envelope nav-icon">

                                </i>
                                <p>{{ trans('global.messages') }}</p>
                                @if ($unread > 0)
                                    <strong>( {{ $unread }} )</strong>
                                @endif

                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                <p>
                                    <i class="fas fa-fw fa-sign-out-alt nav-icon">

                                    </i>
                                    <p>{{ trans('global.logout') }}</p>
                                </p>
                            </a>
                        </li> --}}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper" style="min-height: 917px;">
            <!-- Main content -->

            <section class="content" style="padding-top: 20px;padding-bottom:20px;">
                @if (session('message'))
                    <div class="row mb-2" id="message_shower">
                        <div class="col-lg-12">
                            <div class="alert alert-success" role="alert"
                                style="display:flex;justify-content:space-between">
                                <div>{{ session('message') }}</div>
                                <div><i style="cursor: pointer;"class="fa-fw fas fa-times"
                                        onclick="message_shower()">
                                    </i></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session('errors'))
                    <div class="row mb-2" id="error_shower">
                        <div class="col-lg-12">
                            <div class="alert alert-danger" role="alert"
                                style="display:flex;justify-content:space-between">
                                <div>{{ session('errors') }}</div>
                                <div><i style="cursor: pointer;"class="fa-fw fas fa-times" onclick="error_shower()">
                                    </i></div>
                            </div>
                        </div>
                    </div>
                @endif


                @yield('content')

            </section>
            <!-- /.content -->
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Kalvi ERP</b> 2.0
            </div>
            <strong> &copy;</strong> {{ trans('global.allRightsReserved') }}
        </footer>
        <form id="logoutform" action="{{ route('admin.logout-rit') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.3.4/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(function() {
            let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
            let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
            let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
            let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
            let printButtonTrans = '{{ trans('global.datatables.print') }}'
            let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
            let selectAllButtonTrans = '{{ trans('global.select_all') }}'
            let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

            let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
            };

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
                className: 'btn'
            })
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [{
                        extend: 'selectAll',
                        className: 'btn-outline-primary',
                        text: selectAllButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        },
                        action: function(e, dt) {
                            e.preventDefault()
                            dt.rows().deselect();
                            dt.rows({
                                search: 'applied'
                            }).select();
                        }
                    },
                    {
                        extend: 'selectNone',
                        className: 'btn-outline-primary',
                        text: selectNoneButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'copy',
                        className: 'btn-default',
                        text: copyButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-default',
                        text: csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-default',
                        text: excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-default',
                        text: pdfButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-default',
                        text: printButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-default',
                        text: colvisButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            $.fn.dataTable.ext.classes.sPageButton = '';
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".notifications-menu").on('click', function() {
                if (!$(this).hasClass('open')) {
                    $('.notifications-menu .label-warning').hide();
                    $.get('/admin/user-alerts/read');
                }
            });
        });

        function message_shower() {
            $("#message_shower").fadeOut();
        }

        function error_shower(element) {
            $("#error_shower").fadeOut();
        }
        $(".fa-times").on("click", function() {
            $(this).closest(".parent").fadeOut();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.searchable-field').select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('admin.globalSearch') }}',
                    dataType: 'json',
                    type: 'GET',
                    delay: 200,
                    data: function(term) {
                        return {
                            search: term
                        };
                    },
                    results: function(data) {
                        return {
                            data
                        };
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatItem,
                templateSelection: formatItemSelection,
                placeholder: '{{ trans('global.search') }}...',
                language: {
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        var translation = '{{ trans('global.search_input_too_short') }}';

                        return translation.replace(':count', remainingChars);
                    },
                    errorLoading: function() {
                        return '{{ trans('global.results_could_not_be_loaded') }}';
                    },
                    searching: function() {
                        return '{{ trans('global.searching') }}';
                    },
                    noResults: function() {
                        return '{{ trans('global.no_results') }}';
                    },
                }

            });

            function formatItem(item) {
                if (item.loading) {
                    return '{{ trans('global.searching') }}...';
                }
                var markup = "<div class='searchable-link' href='" + item.url + "'>";
                markup += "<div class='searchable-title'>" + item.model + "</div>";
                $.each(item.fields, function(key, field) {
                    markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " +
                        item[field] + "</div>";
                });
                markup += "</div>";

                return markup;
            }

            function formatItemSelection(item) {
                if (!item.model) {
                    return '{{ trans('global.search') }}...';
                }
                return item.model;
            }
            $(document).delegate('.searchable-link', 'click', function() {
                var url = $(this).attr('href');
                window.location = url;
            });
        });
    </script>
    <script>
        /*!
         * AdminLTE v3.0.0-alpha.2 (https://adminlte.io)
         * Copyright 2014-2018 Abdullah Almsaeed <abdullah@almsaeedstudio.com>
         * Licensed under MIT (https://github.com/almasaeed2010/AdminLTE/blob/master/LICENSE)
         */
        ! function(e, t) {
            "object" == typeof exports && "undefined" != typeof module ? t(exports) : "function" == typeof define && define
                .amd ? define(["exports"], t) : t(e.adminlte = {})
        }(this, function(e) {
            "use strict";
            var i, t, o, n, r, a, s, c, f, l, u, d, h, p, _, g, y, m, v, C, D, E, A, O, w, b, L, S, j, T, I, Q, R, P, x,
                B, M, k, H, N, Y, U, V, G, W, X, z, F, q, J, K, Z, $, ee, te, ne = "function" == typeof Symbol &&
                "symbol" == typeof Symbol.iterator ? function(e) {
                    return typeof e
                } : function(e) {
                    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ?
                        "symbol" : typeof e
                },
                ie = function(e, t) {
                    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                },
                oe = (i = jQuery, t = "ControlSidebar", o = "lte.control.sidebar", n = i.fn[t], r = ".control-sidebar",
                    a = '[data-widget="control-sidebar"]', s = ".main-header", c = "control-sidebar-open", f =
                    "control-sidebar-slide-open", l = {
                        slide: !0
                    }, u = function() {
                        function n(e, t) {
                            ie(this, n), this._element = e, this._config = this._getConfig(t)
                        }
                        return n.prototype.show = function() {
                            this._config.slide ? i("body").removeClass(f) : i("body").removeClass(c)
                        }, n.prototype.collapse = function() {
                            this._config.slide ? i("body").addClass(f) : i("body").addClass(c)
                        }, n.prototype.toggle = function() {
                            this._setMargin(), i("body").hasClass(c) || i("body").hasClass(f) ? this.show() : this
                                .collapse()
                        }, n.prototype._getConfig = function(e) {
                            return i.extend({}, l, e)
                        }, n.prototype._setMargin = function() {
                            i(r).css({
                                top: i(s).outerHeight()
                            })
                        }, n._jQueryInterface = function(t) {
                            return this.each(function() {
                                var e = i(this).data(o);
                                if (e || (e = new n(this, i(this).data()), i(this).data(o, e)),
                                    "undefined" === e[t]) throw new Error(t + " is not a function");
                                e[t]()
                            })
                        }, n
                    }(), i(document).on("click", a, function(e) {
                        e.preventDefault(), u._jQueryInterface.call(i(this), "toggle")
                    }), i.fn[t] = u._jQueryInterface, i.fn[t].Constructor = u, i.fn[t].noConflict = function() {
                        return i.fn[t] = n, u._jQueryInterface
                    }, u),
                re = (d = jQuery, h = "Layout", p = "lte.layout", _ = d.fn[h], g = ".main-sidebar", y = ".main-header",
                    m = ".content-wrapper", v = ".main-footer", C = "hold-transition", D = function() {
                        function n(e) {
                            ie(this, n), this._element = e, this._init()
                        }
                        return n.prototype.fixLayoutHeight = function() {
                            var e = {
                                    window: d(window).height(),
                                    header: d(y).outerHeight(),
                                    footer: d(v).outerHeight(),
                                    sidebar: d(g).height()
                                },
                                t = this._max(e);
                            d(m).css("min-height", e.window - e.header - e.footer), d(g).css("min-height", e
                                .window - e.header)
                        }, n.prototype._init = function() {
                            var e = this;
                            d("body").removeClass(C), this.fixLayoutHeight(), d(g).on(
                                "collapsed.lte.treeview expanded.lte.treeview collapsed.lte.pushmenu expanded.lte.pushmenu",
                                function() {
                                    e.fixLayoutHeight()
                                }), d(window).resize(function() {
                                e.fixLayoutHeight()
                            }), d("body, html").css("height", "auto")
                        }, n.prototype._max = function(t) {
                            var n = 0;
                            return Object.keys(t).forEach(function(e) {
                                t[e] > n && (n = t[e])
                            }), n
                        }, n._jQueryInterface = function(t) {
                            return this.each(function() {
                                var e = d(this).data(p);
                                e || (e = new n(this), d(this).data(p, e)), t && e[t]()
                            })
                        }, n
                    }(), d(window).on("load", function() {
                        D._jQueryInterface.call(d("body"))
                    }), d.fn[h] = D._jQueryInterface, d.fn[h].Constructor = D, d.fn[h].noConflict = function() {
                        return d.fn[h] = _, D._jQueryInterface
                    }, D),
                ae = (E = jQuery, A = "PushMenu", w = "." + (O = "lte.pushmenu"), b = E.fn[A], L = {
                    COLLAPSED: "collapsed" + w,
                    SHOWN: "shown" + w
                }, S = {
                    screenCollapseSize: 768
                }, j = {
                    TOGGLE_BUTTON: '[data-widget="pushmenu"]',
                    SIDEBAR_MINI: ".sidebar-mini",
                    SIDEBAR_COLLAPSED: ".sidebar-collapse",
                    BODY: "body",
                    OVERLAY: "#sidebar-overlay",
                    WRAPPER: ".wrapper"
                }, T = "sidebar-collapse", I = "sidebar-open", Q = function() {
                    function n(e, t) {
                        ie(this, n), this._element = e, this._options = E.extend({}, S, t), E(j.OVERLAY).length ||
                            this._addOverlay()
                    }
                    return n.prototype.show = function() {
                        E(j.BODY).addClass(I).removeClass(T);
                        var e = E.Event(L.SHOWN);
                        E(this._element).trigger(e)
                    }, n.prototype.collapse = function() {
                        E(j.BODY).removeClass(I).addClass(T);
                        var e = E.Event(L.COLLAPSED);
                        E(this._element).trigger(e)
                    }, n.prototype.toggle = function() {
                        (E(window).width() >= this._options.screenCollapseSize ? !E(j.BODY).hasClass(T) : E(j
                            .BODY).hasClass(I)) ? this.collapse(): this.show()
                    }, n.prototype._addOverlay = function() {
                        var e = this,
                            t = E("<div />", {
                                id: "sidebar-overlay"
                            });
                        t.on("click", function() {
                            e.collapse()
                        }), E(j.WRAPPER).append(t)
                    }, n._jQueryInterface = function(t) {
                        return this.each(function() {
                            var e = E(this).data(O);
                            e || (e = new n(this), E(this).data(O, e)), t && e[t]()
                        })
                    }, n
                }(), E(document).on("click", j.TOGGLE_BUTTON, function(e) {
                    e.preventDefault();
                    var t = e.currentTarget;
                    "pushmenu" !== E(t).data("widget") && (t = E(t).closest(j.TOGGLE_BUTTON)), Q
                        ._jQueryInterface.call(E(t), "toggle")
                }), E.fn[A] = Q._jQueryInterface, E.fn[A].Constructor = Q, E.fn[A].noConflict = function() {
                    return E.fn[A] = b, Q._jQueryInterface
                }, Q),
                se = (R = jQuery, P = "Treeview", B = "." + (x = "lte.treeview"), M = R.fn[P], k = {
                    SELECTED: "selected" + B,
                    EXPANDED: "expanded" + B,
                    COLLAPSED: "collapsed" + B,
                    LOAD_DATA_API: "load" + B
                }, H = ".nav-item", N = ".nav-treeview", Y = ".menu-open", V = "menu-open", G = {
                    trigger: (U = '[data-widget="treeview"]') + " " + ".nav-link",
                    animationSpeed: 300,
                    accordion: !0
                }, W = function() {
                    function i(e, t) {
                        ie(this, i), this._config = t, this._element = e
                    }
                    return i.prototype.init = function() {
                        this._setupListeners()
                    }, i.prototype.expand = function(e, t) {
                        var n = this,
                            i = R.Event(k.EXPANDED);
                        if (this._config.accordion) {
                            var o = t.siblings(Y).first(),
                                r = o.find(N).first();
                            this.collapse(r, o)
                        }
                        e.slideDown(this._config.animationSpeed, function() {
                            t.addClass(V), R(n._element).trigger(i)
                        })
                    }, i.prototype.collapse = function(e, t) {
                        var n = this,
                            i = R.Event(k.COLLAPSED);
                        e.slideUp(this._config.animationSpeed, function() {
                            t.removeClass(V), R(n._element).trigger(i), e.find(Y + " > " + N).slideUp(),
                                e.find(Y).removeClass(V)
                        })
                    }, i.prototype.toggle = function(e) {
                        var t = R(e.currentTarget),
                            n = t.next();
                        if (n.is(N)) {
                            e.preventDefault();
                            var i = t.parents(H).first();
                            i.hasClass(V) ? this.collapse(R(n), i) : this.expand(R(n), i)
                        }
                    }, i.prototype._setupListeners = function() {
                        var t = this;
                        R(document).on("click", this._config.trigger, function(e) {
                            t.toggle(e)
                        })
                    }, i._jQueryInterface = function(n) {
                        return this.each(function() {
                            var e = R(this).data(x),
                                t = R.extend({}, G, R(this).data());
                            e || (e = new i(R(this), t), R(this).data(x, e)), "init" === n && e[n]()
                        })
                    }, i
                }(), R(window).on(k.LOAD_DATA_API, function() {
                    R(U).each(function() {
                        W._jQueryInterface.call(R(this), "init")
                    })
                }), R.fn[P] = W._jQueryInterface, R.fn[P].Constructor = W, R.fn[P].noConflict = function() {
                    return R.fn[P] = M, W._jQueryInterface
                }, W),
                ce = (X = jQuery, z = "Widget", q = "." + (F = "lte.widget"), J = X.fn[z], K = {
                    EXPANDED: "expanded" + q,
                    COLLAPSED: "collapsed" + q,
                    REMOVED: "removed" + q
                }, $ = "collapsed-card", ee = {
                    animationSpeed: "normal",
                    collapseTrigger: (Z = {
                        DATA_REMOVE: '[data-widget="remove"]',
                        DATA_COLLAPSE: '[data-widget="collapse"]',
                        CARD: ".card",
                        CARD_HEADER: ".card-header",
                        CARD_BODY: ".card-body",
                        CARD_FOOTER: ".card-footer",
                        COLLAPSED: ".collapsed-card"
                    }).DATA_COLLAPSE,
                    removeTrigger: Z.DATA_REMOVE
                }, te = function() {
                    function n(e, t) {
                        ie(this, n), this._element = e, this._parent = e.parents(Z.CARD).first(), this._settings = X
                            .extend({}, ee, t)
                    }
                    return n.prototype.collapse = function() {
                        var e = this;
                        this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideUp(this._settings
                            .animationSpeed,
                            function() {
                                e._parent.addClass($)
                            });
                        var t = X.Event(K.COLLAPSED);
                        this._element.trigger(t, this._parent)
                    }, n.prototype.expand = function() {
                        var e = this;
                        this._parent.children(Z.CARD_BODY + ", " + Z.CARD_FOOTER).slideDown(this._settings
                            .animationSpeed,
                            function() {
                                e._parent.removeClass($)
                            });
                        var t = X.Event(K.EXPANDED);
                        this._element.trigger(t, this._parent)
                    }, n.prototype.remove = function() {
                        this._parent.slideUp();
                        var e = X.Event(K.REMOVED);
                        this._element.trigger(e, this._parent)
                    }, n.prototype.toggle = function() {
                        this._parent.hasClass($) ? this.expand() : this.collapse()
                    }, n.prototype._init = function(e) {
                        var t = this;
                        this._parent = e, X(this).find(this._settings.collapseTrigger).click(function() {
                            t.toggle()
                        }), X(this).find(this._settings.removeTrigger).click(function() {
                            t.remove()
                        })
                    }, n._jQueryInterface = function(t) {
                        return this.each(function() {
                            var e = X(this).data(F);
                            e || (e = new n(X(this), e), X(this).data(F, "string" == typeof t ? e : t)),
                                "string" == typeof t && t.match(/remove|toggle/) ? e[t]() : "object" ===
                                ("undefined" == typeof t ? "undefined" : ne(t)) && e._init(X(this))
                        })
                    }, n
                }(), X(document).on("click", Z.DATA_COLLAPSE, function(e) {
                    e && e.preventDefault(), te._jQueryInterface.call(X(this), "toggle")
                }), X(document).on("click", Z.DATA_REMOVE, function(e) {
                    e && e.preventDefault(), te._jQueryInterface.call(X(this), "remove")
                }), X.fn[z] = te._jQueryInterface, X.fn[z].Constructor = te, X.fn[z].noConflict = function() {
                    return X.fn[z] = J, te._jQueryInterface
                }, te);
            e.ControlSidebar = oe, e.Layout = re, e.PushMenu = ae, e.Treeview = se, e.Widget = ce, Object
                .defineProperty(e, "__esModule", {
                    value: !0
                })
        });
        //# sourceMappingURL=adminlte.min.js.map
    </script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('#list li').each(function() {
                    var listItemText = $(this).text().toLowerCase();

                    if (listItemText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
