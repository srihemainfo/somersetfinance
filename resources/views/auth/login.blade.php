@extends('layouts.app')
@section('content')
    <style>
        /* * * * * General CSS * * * * */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #666666;
            background: #eaeff4;
        }

        .wrapper {
            margin: 0 auto;
            width: 100%;
            /* max-width: 1140px; */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .container {
            position: relative;
            border-radius: 4px;
            width: 100%;
            max-width: 600px;
            height: auto;
            display: flex;
            /* background: #ffffff; */
            background-image: linear-gradient(87deg, #0f5381, white);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        .credit {
            position: relative;
            margin: 25px auto 0 auto;
            width: 100%;
            text-align: center;
            color: #666666;
            font-size: 16px;
            font-weight: 400;
        }

        .credit a {
            color: #222222;
            font-size: 16px;
            font-weight: 600;
        }

        /* * * * * Login Form CSS * * * * */
        h2 {
            /* margin: 0 0 15px 0; */
            font-size: 30px;
            font-weight: 700;
        }

        h2 img {
            width: 120px;
        }

        p {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 500;
            line-height: 22px;
        }

        .loginBtn {
            display: inline-block;
            padding: 7px 20px;
            font-size: 16px;
            letter-spacing: 1px;
            text-decoration: none;
            border-radius: 4px;
            color: #ffffff;
            background-color: #006cdf;
            outline: none;
            border: 1px solid #ffffff;
            transition: .3s;
            -webkit-transition: .3s;
        }

        .loginBtn:hover {
            color: #006cdf;
            background: #ffffff;
        }

        .col-left,
        .col-right {
            width: 50%;
            padding: 45px 35px;
        }

        .col-left {
            width: 50%;
            background-color: white;
            /* -webkit-clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0);
                                                                                                clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0); */
            /* filter: drop-shadow(-1px 6px 3px rgba(50, 50, 0, 0.5)); */
            /* box-shadow: -1px 6px 3px rgba(50, 50, 0, 0.5); */


        }


        @media(max-width: 575.98px) {
            .container {
                flex-direction: column;
                box-shadow: none;
            }

            .col-left,
            .col-right {
                width: 100%;
                margin: 0;
                padding: 60px;
                -webkit-clip-path: none;
                clip-path: none;
            }

            .wrapper {
                position: relative;
                min-height: 100vh;

            }
        }

        .login-text {
            position: relative;
            width: 100%;
            color: #006cdf;
            text-align: center;
        }

        .login-form {
            position: relative;
            width: 100%;
            color: #666666;
        }

        .login-form p:last-child {
            margin: 0;
        }

        .login-form p a {
            color: #006cdf;
            font-size: 14px;
            text-decoration: none;
        }

        .login-form p:last-child a:last-child {
            float: right;
        }

        .login-form label {
            display: block;
            width: 100%;
            margin-bottom: 2px;
            letter-spacing: .5px;
        }

        .login-form p:last-child label {
            width: 60%;
            float: left;
        }

        .login-form label span {
            color: #006cdf;
            padding-left: 2px;
        }

        .login-form input {
            display: block;
            width: 100%;
            height: 40px;
            padding: 0 10px;
            font-size: 16px;
            letter-spacing: 1px;
            outline: none;
            border: none;
            color: #006cdf;
            border-radius: 4px;
        }

        #parent {
            position: relative;
        }

        #togglePassword {
            position: absolute;
            right: 5px;
            top: 13px;
        }

        #errorModel {
            width: 40%;
            position: absolute;
            top: 7%;
            background-color: #006cdf;
            color: white;
            border-radius: 3px;
            box-shadow: 2px 2px 5px rgb(176, 208, 255);
        }
    </style>
    <div class="wrapper" style="background-color: white;">

        <div id="errorModel" style="{{ $errors->has('email') || $errors->has('password') ? '' : 'display:none;' }}">
            <div class="row pl-3 pt-2 pr-3 pb-2">
                <div class="col-10" id="errorDisplayer">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @endif
                    @if ($errors->has('password'))
                        {{ $errors->first('password') }}
                    @endif
                </div>
                <div class="col-2">
                    <button type="button" style="outline: none;" class="close" onclick="closeErrorModel()">&times;</button>
                </div>
            </div>
        </div>

        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger" style="position:absolute;left:30%;top:-20%;z-index:99;">
                    {{ session('error') }}
                </div>
            @endif
            <div class="col-left d-flex align-items-center">
                <div class="login-text -MB2">
                    <a href="{{ route('admin.home') }}">
                        <img src="{{ asset('adminlogo/favicon.png') }}" alt="" width="100%">
                    </a>
                    {{-- <p style="color: #0f5381;"><br><b>Kalvi ERP<br>Shape Yourself</b></p> --}}
                    {{-- <p style="color: #0f5381;"><b><br><span
                                style="font-size: 20px;">Education Management System</span></b></p> --}}
                </div>
            </div>
            <div class="col-right" style="background-color: #0f5381;">
                <div class="login-form text-center">
                    <h2 style="color:#ffffff;">Login</h2>
                    <div class="card-body login-card-body">
                        <form action="{{ route('login') }}" method="POST" id="loginForm">
                            @csrf
                            <div class="form-group">
                                <input id="email" type="text" class="" autocomplete="off"
                                    placeholder=" User ID" name="email" value="{{ old('email', null) }}" autofocus>
                            </div>
                            <div class="form-group" id="parent">
                                <input id="password" type="password" class="" name="password"
                                    placeholder=" Password">
                                <span style="color: #0f5381;">
                                    <i class="fa fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="button" class="loginBtn"
                                    style="background-color: #0f5381; width:100%;outline:none;" onclick="checkInputs()">
                                    Login </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            let passwordField = $('#password');
            let togglePassword = $('#togglePassword');

            togglePassword.click(function() {
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    togglePassword.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    togglePassword.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });

        function closeErrorModel() {
            $("#errorDisplayer").html(``);
            $("#errorModel").hide()
        }

        function checkInputs() {
            $("#errorModel").hide();
            $("#errorDisplayer").html(``);
            if ($("#email").val() == '') {
                $("#errorDisplayer").html(`Enter The User ID.`);
                $("#errorModel").show();
            } else if ($("#password").val() == '') {
                $("#errorDisplayer").html(`Enter The Password.`);
                $("#errorModel").show();
            } else {
                $("#errorModel").hide();
                $("#errorDisplayer").html(``);
                $("#loginForm").submit();
            }
        }
    </script>
@endsection
