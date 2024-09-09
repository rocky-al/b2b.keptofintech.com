@php
use App\Models\CompanySetting;

$basic_data = CompanySetting::where('id', 1)->first();
$logo = $basic_data->company_logo;

$logo_url = URL::asset('company_logo/'.$logo);

@endphp



<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('dashboard')}}">
            <div class="logo-img">
                <img height="50" src="{{$logo_url}}" class="header-brand-img">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);
    @endphp
    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}"><i class="ik ik-bar-chart-2"></i><span>{{ __('Dashboard')}}</span></a>
                </div>
                <div class="nav-item {{ ($segment1 == 'users') ? 'active' : '' }}">
                    <a href="{{url('/users')}}"><i class="ik ik-user"></i><span>{{ __('Users Management')}}</span></a>
                </div>

                <div class="nav-item {{ ($segment1 == 'page') ? 'active' : '' }}">
                    <a href="{{route('page.list')}}"><i class="ik ik-file-text"></i><span>{{ __('Page Manager')}}</span></a>
                </div>
                <div class="nav-item {{ ($segment1 == 'subscription') ? 'active' : '' }}">
                    <a href="{{url('/subscription')}}"><i class="ik ik-file-text"></i><span>{{ __('Payment Management')}}</span></a>
                </div> 
                <div class="nav-item {{ ($segment1 == 'contact_support') ? 'active' : '' }}">
                    <a href="{{url('contact_support')}}"><i class="fa fa-bell"></i>&nbsp;<span>{{ __('Contact Support')}}</span></a>
                </div>

                <div class="nav-item {{ ($segment1 == 'ads_category'||$segment1 == 'product_category' || $segment2 == 'emailtemplate') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-map"></i><span>{{ __('Master Management')}}</span></a>
                    <div class="submenu-content">
                        <a href="{{url('masters/emailtemplate')}}" class="menu-item {{ ($segment2 == 'emailtemplate') ? 'active' : '' }}">{{ __('Email Template')}}</a>
                    </div>
                </div>

                <div class="nav-item {{ ($segment2 == 'setting') ? 'active' : '' }}">
                    <a href="{{url('/company/setting')}}"><i class="fa fa-cogs" aria-hidden="true"></i><span>{{ __('Setting')}}</span></a>
                </div>
        </div>
    </div>
</div>