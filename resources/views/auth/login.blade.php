<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login |
        {{(isset($company_detail->company_name) && !empty($company_detail->company_name) ? $company_detail->company_name : '')}}
    </title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon"
        href="{{ asset('company_logo/') }}/{{(isset($company_detail->favi_icon) && !empty($company_detail->favi_icon) ? $company_detail->favi_icon : '')}}"
        type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/theme-image.css')}}">
    <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js')}}"></script>



    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .custom-heading {
            font-size: 16px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: darkgrey;
        }
    </style>

</head>

<body style="height:100vh">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="top-menu d-flex align-items-center">
                <div class="logo-img mr-3">
                    @php 
                        $logo_url = URL::asset('company_logo/20230916042110.png');
                    @endphp
                    <a class="header-brand" href="{{route('users')}}">
                        <img height="50" src="{{$logo_url}}" class="header-brand-img rounded-circle">
                    </a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/terms-and-conditions">Terms and Conditions</a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/services">Services</a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/privacy-policy">Privacy Policy</a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/cancellation-policy">Refund Policy</a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/AboutUs">About Us</a>
                </div>
                <div class="menu mr-3" style="font-size: 15px;">
                    <a href="https://b2b.keptofintech.com/ContactUs">Contact Us</a>
                </div>
            </div>
        </div>
    </div>


    <div class="auth-wrapper forgot_password_form h-20">
        <div class="container-fluid h-90">
            <div class="row flex-row h-100 bg-white p-3">

                <div class="col-xl-12 my-auto p-0">
                    <div class="authentication-form mx-auto shadow p-5 bg-white rounded" style="max-width: 500px;">
                        <div class="logo-centered text-center">
                            <a href=""><img style="max-width: 250px;height:70px;"
                                    src="{{ asset('company_logo/') }}/{{(isset($company_detail->company_logo) && !empty($company_detail->company_logo) ? $company_detail->company_logo : '')}}"
                                    alt=""></a>
                        </div>

                        <div class="logo-centered-text text-center ">
                            <h2>{{(isset($company_detail->company_name) && !empty($company_detail->company_name) ? $company_detail->company_name : '')}}
                            </h2>
                            <p>Log in to your account to continue.</p>
                        </div>



                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="d-block"><b>Email ID</b></label>
                                <input id="email" type="email" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <i class="ik ik-user"></i>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password" class="d-block"><b>Password</b></label>

                                <div class="position-relative passwordmain">
                                    <div class="input-group" id="show_hide_password">
                                        <input id="password" type="password" placeholder="Password"
                                            class=" border-end-0 form-control @error('password') is-invalid @enderror"
                                            name="password" required>
                                        <i class="ik ik-lock"></i>
                                        <a href="javascript:;" class="input-group-text  bg-transparent hiden"><i
                                                class='ik ik-eye-off'></i></a>
                                    </div>
                                </div>


                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- <div class="row">
                                    <div class="col text-right">
                                        <a class="btn text-danger" href="{{url('password/forget')}}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                </div> -->

                            <div class="sign-btn">
                                <button class="btn btn-custom btn-primary w-100">Sign In</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="footer">
        <p class="animate__animated animate__fadeInUp">&copy; 2023 KEPTO FINTECH PVT. LTD - ALL RIGHTS RESERVED.</p>
    </div>


    <script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('plugins/screenfull/dist/screenfull.js')}}"></script>

    <script>
        $(document).ready(function () {



            $(document).ready(function () {
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');

                        $('#show_hide_password .hiden i').removeClass('ik').removeClass('ik-eye');
                        $('#show_hide_password .hiden i').addClass('ik').addClass('ik-eye-off');

                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password .hiden i').removeClass('ik').removeClass('ik-eye-off');
                        $('#show_hide_password .hiden i').addClass('ik').addClass('ik-eye');
                    }
                });
            });


        });
    </script>

    <!-- <footer> -->

    <!-- </footer> -->

</body>
<!-- 
<footer>

    <div class="auth-wrapper forgot_password_form">
        <p class="animate__animated animate__fadeInUp">&copy; 2023 KEPTO FINTECH PVT. LTD - ALL RIGHTS RESERVED.</p>
    </div>
</footer> -->

</html>