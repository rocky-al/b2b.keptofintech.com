<!-- @php
use App\Models\CompanySetting;

$basic_data = CompanySetting::where('id', 1)->first();
$logo = $basic_data->company_logo;

$logo_url = URL::asset('company_logo/'.$logo);

@endphp



<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('users')}}">
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
                
                <div class="nav-item {{ ($segment1 == 'users') ? 'active' : '' }}">
                    <a href="{{url('/users')}}"><i class="ik ik-user"></i><span>{{ __('Users Management')}}</span></a>
                </div>
        </div>
    </div>
</div> -->