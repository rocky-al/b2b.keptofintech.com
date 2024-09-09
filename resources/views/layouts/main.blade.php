<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
	<title>@yield('title','') | KEPTO FINTECH</title>
	<!-- initiate head with meta tags, css and script -->
	@include('include.head')

</head>
<body id="app" class="">
    <div class="wrapper">
    	<!-- initiate header-->
    	@include('include.header')
    	<div class="page-wrap">
	    	<!-- initiate sidebar-->
	    	@include('include.sidebar')

	    	<div class="main-content p-3">
			<div class="card d-block">
            
			</div>
			<div class="card d-block">
            
        </div>
	    		<!-- yeild contents here -->
	    		@yield('content')
	    	</div>

	    	<!-- initiate chat section-->
	    	<!-- @include('include.chat') -->


	    	<!-- initiate footer section-->
<!--	    	@include('include.footer')-->

    	</div>
    </div>
    
	<!-- initiate modal menu section-->
	@include('include.modalmenu')

	<!-- initiate scripts-->
	@include('include.script')	
	@stack('app-script')
</body>
</html>
