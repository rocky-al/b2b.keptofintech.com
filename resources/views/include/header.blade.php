<header class="header-top pl-3 pr-3" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <div class="logo-img">
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

                <div class='pagehead_title pt-1'>@yield('name', '')</div>
            </div>

            <div class="top-menu d-flex align-items-center">
                @if(Auth::check())
                    <div class="dropdown">
                        <!-- storage/employee_images/61ngRudCxDj8OYanoBz5jzBXgsIQaahiv00EI1Na.jpg -->
                        <a href="#" id="userDropdown" role="button" aria-haspopup="true" aria-expanded="false"
                            data-toggle="dropdown">
                            <img class="avatar"
                                src="{{ Auth::user()->avatar ? asset('storage/employee_images/' . Auth::user()->avatar) : asset('img/default_user.png') }}"
                                alt="">
                            <span>{{ Auth::user()->first_name ?? '' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            @if(Auth::user()->user_type == 1)
                                <a class="dropdown-item" href="{{ url('/employee/profile/' . Auth::user()->id) }}">
                                    <i class="ik ik-user dropdown-icon"></i> {{ __('Profile') }}
                                </a>
                            @endif
                            <a class="dropdown-item" href="{{ url('/change/password/' . Auth::user()->id) }}">
                                <i class="ik ik-lock dropdown-icon"></i> {{ __('Change Password') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('logout') }}">
                                <i class="ik ik-power dropdown-icon"></i> {{ __('Logout') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>




            <!-- <div class="top-menu d-flex align-items-center">
               
                <div class="dropdown">
                    <a href="#" id="userDropdown" role="button" aria-haspopup="true" aria-expanded="false" data-toggle='dropdown' ><img class="avatar" src="{{asset('img/default_user.png') }}" alt=""> <span>{{(isset(Auth::user()->first_name) && !empty(Auth::user()->first_name) ? Auth::user()->first_name:'')}}</span></a>                    
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        @if(Auth::user()->user_type == 1)
                        <a class="dropdown-item" href="{{ url('/employee/profile').'/'.Auth::user()->id }}"><i class="ik ik-user dropdown-icon"></i> {{ __('Profile')}}</a>
                        @endif
                        <a class="dropdown-item" href="{{ url('/change/password').'/'.Auth::user()->id }}"><i class="ik ik-lock dropdown-icon"></i> {{ __('Change Password')}}</a>
                        <a href="{{ url('logout') }}" class="dropdown-item"><i class="ik ik-power dropdown-icon"></i> {{ __('Logout')}}</a>
                       
                    </div>
                </div> 

            </div> -->
        </div>
    </div>
</header>