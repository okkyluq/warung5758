<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>
		@if (View::hasSection('title'))
			@yield('title')
		@else
        	TITLE NOT FOUND
		@endif
	</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" href="{{ asset('icon.ico') }}">
	{{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> --}}
	<link href="{{asset('back/global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/core.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('back/jquery-wait/waitMe.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/global_assets/js/plugins/sweetalert2/src/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

    {{-- selectize --}}
    <link href="{{asset('back/selectize.js-master/css/selectize.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('back/selectize.js-master/css/selectize.bootstrap3.css')}}" rel="stylesheet" type="text/css">


	<!-- Core JS files -->
	<script src="{{asset('back/global_assets/js/plugins/loaders/pace.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/core/libraries/bootstrap.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('back/global_assets/js/plugins/shortcut.js') }}"></script>

	<!-- /core JS files -->
	<script src="{{ asset('back/assets/js/app.js') }}"></script>
	<!-- /theme JS files -->
	<script src="{{ asset('back/global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
	@livewireStyles
	@livewireScripts
	{{-- <link rel="stylesheet" href="{{mix('css/app.css')}}"> --}}
	@yield('style')
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			{{-- <a class="navbar-brand" href="index.html"><img src="{{ asset('back/global_assets/images/logo_light.png') }}" alt=""></a> --}}
			<div class="text-center" style="text-align: center;">
				<a class="navbar-brand" href="{{ url('/') }}">
					<b>{{ env('SET_TOKO', 'NOT SET') }}</b>
				</a>
			</div>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			@include('backoffice.partials.header')
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				@include('backoffice.partials.sidebar')
			</div>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				@yield('content')

			</div>
		</div>
	</div>
</body>
<script src="{{ asset('back/assets/js/recta.js') }}"></script>
<script src="{{ asset('back/assets/js/recta_kasir.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/sweetalert2/src/sweetalert2.min.js') }}"></script>
<script src="{{ asset('back/jquery-wait/waitMe.min.js') }}"></script>
<script src="{{ asset('back/additional/loader.js') }}"></script>
<script src="{{ asset('back/additional/function.js') }}"></script>
<script src="{{ asset('back/script_recta.js') }}"></script>


<script src="{{ asset('back/selectize.js-master/js/standalone/selectize.js') }}"></script>

@yield('script')
<script>
    $("input").attr('autocomplete', 'off');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function(){
        // $.checkConnectionRecta({RECTA_API_KEY: '123456789', RECTA_PORT: '5555'});
        $.checkConnectionRecta({
            RECTA_API_KEY: '123456789',
            RECTA_PORT: '1811'
        });
    });






</script>
</html>
