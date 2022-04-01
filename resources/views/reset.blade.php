<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>პაროლის&nbsp;აღდგენა</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="David Tchetchelashvili">
		<link rel="icon" type="image/png" href="{{ asset('images/rda.png') }}">
		<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('js/script.js') }}"></script>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
		<style type="text/css">
			@font-face {
				font-family: "noto_bold";
				src: url("{{ asset('fonts/NotoBold.ttf') }}");
			}

			@font-face {
				font-family: "noto_regular";
				src: url("{{ asset('fonts/NotoRegular.ttf') }}");
			}

			h1, h2, h3, h4, h5, h6, p, strong, b, input, a, span {
				font-family: "noto_regular" !important;
			}

		</style>
	</head>
	<body>
		<div class="left-block">
			<div class="layer">
				<center>
					<div class="circle-image">
						<img src="{{ asset("images/rda.png") }}">
					</div>
					<br>
					<h1 style="color: #fff">სოფლის&nbsp;განვითარების&nbsp;სააგენტო</h1>
					<div class="social-icons">
						<a href="http://facebook.com/277709595710849"><img src="{{ asset('images/fb.png') }}" style="width: 20px;height: 15px;"></a>
						<a href="https://ge.linkedin.com/company/rural-development-agency"><img src="{{ asset('images/linkedin.png') }}" style="width: 20px;height: 15px;"></a>
						<a href="https://www.youtube.com/channel/UCultyl-yGIGph9SwqZhKFbw"><img src="{{ asset('images/yt.png') }}" style="width: 20px;height: 15px;"></a>
					</div>
				</center>
			</div>
		</div>
		<div class="right-block">
			<center>
				<form method="post" action="">
					{{ csrf_field() }}
					<div class="login-block" style="margin-top: 150px;">
						<table>
							<tr>
								<td>
									<input type="text" placeholder="შეიყვანეთ კოდი" name="random_code" id="random_code" class="input">
								</td>
							</tr>
							<tr>
								<td>
									<input type="password" placeholder="შეიყვანეთ ახალი პაროლი" name="password" id="password" class="input">
								</td>
							</tr>
							<tr>
								<td>
									<input type="submit" name="reset" value="გაგზავნა" class="primary-btn">
								</td>
							</tr>
						</table>
					</div>
				</form>
				@if(count($errors) > 0)
					<div class="danger-alert" style="margin-left: 180px !important">
						@foreach($errors->all() as $error)
							<p><strong>{{ $error }}</strong></p>
						@endforeach
					</div>
				@endif
				@if(Session::has("error") && $error_message = Session::get("error"))
					<div class="danger-alert" style="margin-left: 180px !important">
						<p><strong>{{ $error_message }}</strong></p>
					</div>
				@endif
				@if(Session::has("status") && $success = Session::get("status"))
					<div class="success-alert" style="margin-left: 180px !important">
						<p><strong>{{ $success }}</strong></p>
					</div>
				@endif
			</center>
		</div>
	</body>
</html>