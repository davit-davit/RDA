<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>მთავარი</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="David Tchetchelashvili">
		<link rel="icon" type="image/png" href="{{ asset('images/rda.png') }}">
		<link rel="stylesheet" href="{{ asset('css/libraries/bootstrap.min.css') }}">
		<script type="text/javascript" language="javascript" src="{{ asset('js/libraries/jquery.min.js') }}"></script>
		<script src="{{ asset('js/libraries/angular.min.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('js/libraries/ckeditor.js') }}"></script>
		<script src="{{ asset('js/libraries/moment.min.js') }}"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
		<script type="text/javascript" src="{{ asset('js/script.js?v=0.5.5') }}"></script>
		<script type="text/javascript" src="{{ asset('js/ajax.js?v=0.0.9') }}"></script>
		<script type="text/javascript" src="{{ asset('js/staff.js?v=0.0.1') }}"></script>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css?v=0.2.7') }}">
		<script>
			$(document).ready(function() {
				// დალოგინებული იუზერი საკუთარი სახელსა და გვარზე დაჭერისას ჩამოშლის მენიუს
				// სადაც იქნება პარამეტრები და სისტემიდან გამოსვლა
				$("#dropdown_user").click(function() {
					$(".dropdown-content").slideToggle("fast");
				});
				//შეტყობინებების გახსნის ფუნქცია
				$("#seen").click(function() {
					$(".dropdown-notif").slideToggle("fast");
				});
				// სისტემიდან გამოსვლის ღილაკზე დაჭერისას გამოვა შეტყობინება სადაც იქნება შეკითხვა სურს თუ არა
				// მომხმარებელს სისტემიდან გამოსვლა
				$("#logout").on("click", function() {
					$(".modal-logout").fadeIn("fast");
				});
				// სისტემიდან გამოსვლის შეტყობინების ფანჯრის დახურვა
				$(".closemodal").click(function() {
					$(".modal-logout").fadeOut("fast");
				});
			});
			</script>
		<style type="text/css">
			@font-face {
				font-family: "noto_bold";
				src: url("{{ asset('fonts/NotoBold.ttf') }}");
			}

			@font-face {
				font-family: "noto_regular";
				src: url("{{ asset('fonts/NotoRegular.ttf') }}");
			}

			h1, h2, h3, h4, h5, h6, p, input, a, em, tr, td, table, thead, tbody, tfoot, label, button, select, textarea {
				font-family: "noto_regular" !important;
			}

			strong, b {
				font-family: "noto_bold" !important;
			}

			.error {
				font-family: "noto_regular" !important;
				color: red;
			}

			a {text-decoration: none !important;}
		</style>
	</head>
	<body>
		<input type="hidden" id="logged_in_id" value="{{ auth()->user()->id }}">
		<div class="navigation">
			<img src="https://rda.gov.ge/style/img/rda-ge.png" style="margin-left: 20px; width: 140px;height:40px;margin-top:5px">
			@auth
				<a href="javascript:void(0)" id="seen">
					<img src="{{ asset('images/icons/notif.png') }}" style="height:25px;width:25px">
					<label class="badge" id="count_notif" style="position: absolute;top: 0;right: 0;margin-top: 12px;display: none;"></label>
				</a>
				<div class="header-user">
					<a href="javascript:void(0)" id="dropdown_user">
						<img src="{{ asset(auth()->user()->avatar) }}" style="width: 35px; height: 35px;border-radius: 10px">&nbsp;&nbsp;<strong>{{ auth()->user()->name . " " . auth()->user()->lastname }}&nbsp;&nbsp;<span class="caret"></span></strong>
					</a>
				</div>
			@endauth
		</div>
		<div class="dropdown-notif" id="notif"></div>
		<div class="dropdown-content">
			<li>
				<a href="/edit_profile"><img src="{{ asset('images/icons/gear.png') }}" style="width: 20px;height:20px">&nbsp;&nbsp;პარამეტრები</a>
			</li>
			<li>
				<a href="javascript:void(0)" id="logout"><img src="{{ asset('images/icons/logout.png') }}" style="width: 20px;height:20px">&nbsp;&nbsp;გამოსვლა</a>
			</li>
		</div>
		@if(Hash::check("1234", auth()->user()->password))
			<div class="password_alert">
				<div class="alert_inner">
					<a href="javascript:void(0)" class="close_alert"><img src="{{ asset('images/icons/cl.png') }}"></a>
					<br>
					<p>გთხოვთ&nbsp;<a href="/edit_profile"><b>შეცვალოთ</b></a> მიმდინარე პაროლი უსაფრთხოების მიზნით!</p>
				</div>
			</div>
		@endif
		<div class="modal-logout" style="display: none !important">
			<div class="inner-modal">
				<center>
					<p><b>ნამდვილათ გსურთ სისტემიდან გამოსვლა?</b></p>
					<hr>
					<form method="post" action="{{ route("logout") }}">
						{{ csrf_field() }}
						<button type="submit" class="agree"><span>დიახ</span></button>
					</form>
					<a href="javascript:void(0)" class="closemodal">არა</a>
				</center>
			</div>
		</div>
		@guest
			@php
		        header("Location: " . URL::to('/'), true, 302);
		        exit();
		    @endphp
		@endguest