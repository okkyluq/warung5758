<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>KASIR | WARUNG MAKAN 5758</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" href="{{ asset('icon.ico') }}">
	{{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> --}}
	<link href="{{asset('back/global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/core.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('back/global_assets/js/plugins/sweetalert2/src/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('back/jquery-wait/waitMe.min.css')}}" rel="stylesheet" type="text/css">
	<script src="{{asset('back/global_assets/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/core/libraries/bootstrap.min.js')}}"></script>

	<script src="{{ asset('back/assets/js/app.js') }}"></script>
    @yield('style')
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">

			<div class="text-center" style="text-align: center;">
				<a class="navbar-brand" href="http://warung.test">
					<b>WARUNG MAKAN JAWA 5758</b>
				</a>
			</div>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<p class="navbar-text"><span class="label bg-success-400">Online</span></p>

			<ul class="nav navbar-nav navbar-right">
                <p class="navbar-text" id="info-recta"><span class="label bg-danger"><i class="icon-printer2"></i>  Print Not Connected</span></p>
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ asset('user.jpg') }}" alt="">
						<span>{{Auth::user()->name}}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="{{ route('logout') }}"
								onclick="event.preventDefault();
								document.getElementById('logout-form').submit();"
							><i class="icon-exit2"></i> Keluar</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>


	<div class="navbar navbar-default" id="navbar-second">
		<ul class="nav navbar-nav no-border visible-xs-block">
			<li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
		</ul>

		<div class="navbar-collapse collapse" id="navbar-second-toggle">
			@include('kasir.partials.menu-top')
		</div>
	</div>


	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper" style="padding: 10px;">
                @yield('content')
			</div>
		</div>
	</div>

</body>
<script src="{{ asset('back/global_assets/js/plugins/sweetalert2/src/sweetalert2.min.js') }}"></script>
<script src="{{ asset('back/jquery-wait/waitMe.min.js') }}"></script>
@yield('script')
<script>
	$("input").attr('autocomplete', 'off');
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
</script>
</html>
