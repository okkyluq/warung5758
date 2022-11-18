<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LOGIN | ADMIN</title>
	<link rel="icon" type="image/x-icon" href="{{ asset("icon.ico") }}">
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('back/global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('back/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('back/assets/css/core.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('back/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('back/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{asset('back/global_assets/js/plugins/loaders/pace.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/core/libraries/bootstrap.min.js')}}"></script>
	<script src="{{asset('back/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<!-- /core JS files -->
	<style>
        .login-cover{
            background: url({{url('background-4.jpg')}}) no-repeat;
            background-size: cover;
        }
    </style>
	<script src="{{ asset('back/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

	<script src="{{ asset('back/assets/js/app.js') }}"></script>
	<script src="{{ asset('back/global_assets/js/demo_pages/login.js') }}"></script>

</head>

<body class="login-container login-cover" style="background-color: #f2f4f7">

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content pb-20">

					<!-- Advanced login -->
					<form action="{{ url('login') }}" method="POST">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="">
									<img src="{{ asset('logo2.png') }}" alt="" width="220">
								</div>
							</div>
							<div class="text-center">
								<h3 class="content-group-lg text-bold">WARUNG JAWA 5758 <small class="display-block">Login Untuk Menggunakan Aplikasi</small></h3>
							</div>

							<div class="form-group has-feedback has-feedback-left{{ $errors->has('username') ? ' has-error' : '' }}">
								<input type="text" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
								@if ($errors->has('username'))
								<span class="help-block">{{ $errors->first('username') }}</span>
								@endif
							</div>

							<div class="form-group has-feedback has-feedback-left{{ $errors->has('password') ? ' has-error' : '' }}">
								<input type="password" class="form-control" placeholder="Password" name="password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
								@if ($errors->has('password'))
								<span class="help-block">{{ $errors->first('password') }}</span>
								@endif
							</div>

							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="form-group">
								<button type="submit" class="btn bg-blue btn-block">Login <i class="icon-arrow-right14 position-right"></i></button>
							</div>
						</div>
					</form>
					<!-- /advanced login -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
