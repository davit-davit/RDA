@include("layouts.header")

<div class="main-block">
	<div class="success-alert" style="display: none !important;position: absolute;top: 0;right: 0;margin: 30px">
		<p><strong></strong></p>
	</div>
	<div class="danger-alert" style="display: none;position: absolute;top:0;right:0;margin:20px" id="danger-alert">
		<p><strong></strong></p>
	</div>
	<div class="change_password_block">
		<form method="post" id="chngpsw_form" action="/change_pass">
			<center><h3><strong>პაროლის ცვლილება</strong></h3></center>
			{{ csrf_field() }}
			<input type="password" name="o_password" id="oldpassword" placeholder="ძველი პაროლი">
			<input type="password" name="n_password" id="newpassword1" placeholder="ახალი პაროლი">
			<input type="password" name="conf_password" id="newpassword2" placeholder="გაიმეორეთ ახალი პაროლი">
			<button type="submit" class="primary-btn" name="psw">შეცვლა</button>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript">
	document.title = "პროფილის რედაქტირება";
</script>

@include("layouts.sidebar")