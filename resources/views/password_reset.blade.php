<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>პაროლის&nbsp;აღდგენა</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="David Tchetchelashvili">
		<link rel="icon" type="image/png" href="{{ asset('images/rda.png') }}">
		<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('js/validations.js') }}"></script>
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
			<div class="danger-alert" style="display: none;position: absolute;top:0;right:0;margin:20px" id="danger-alert">
				<p><strong></strong></p>
			</div>
			<form method="post" action="" id="reset_form">
				{{ csrf_field() }}
				<div class="login-block">
					<center>
						<table>
							<tr>
								<td>
									<input type="email" placeholder="შეიყვანეთ იმეილი" name="email" id="email" class="input">
								</td>
							</tr>
							<tr>
								<td>
									<input type="submit" name="reset" value="გაგზავნა" class="primary-btn">
								</td>
							</tr>
						</table>
						<a href="/"><img src="{{ asset('images/chevron-left.png') }}" style="width: 12px;height: 12px">&nbsp;&nbsp;უკან</a>
						@if(count($errors) > 0)
							<div class="success-alert" style="margin-left: 180px !important">
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
					</center>
				</div>
			</form>
		</div>
	</body>
</html>