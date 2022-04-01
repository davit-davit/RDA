<div class="sidebar">
	<div class="layer">
		<ul class="sidemenu">
			<li>
				<a href="/home">
					<img src="{{ asset('images/icons/home.png') }}" style="width: 20px; height:20px;">&nbsp;&nbsp;მთავარი
				</a>
			</li>
			@if(auth()->user()->role == "hr")
				<!-- END | დავალებების ქვე მენიუ -->
				<li>
					<a href="/user_test_result">
						<img src="{{ asset('images/icons/test.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;ჩემი&nbsp;ტესტირებები
					</a>
				</li>
				<li>
					<a href="/quiz">
						<img src="{{ asset('images/icons/quiz.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;გამოკითხვები
					</a>
				</li>
				<li id="sub_2">
					<a href="javascript:void(0)">
						<img src="{{ asset('images/icons/users.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;თანამშრომლები
						<span style="float: right;">
							<img src="{{ asset('images/icons/down.png') }}" style="width:18px">
						</span>
					</a>
				</li>
				<!-- START | HR-ის ქვე მენიუ -->
				<ul class="sub_menu1" id="sub2">
					<li style="padding-left: 10px;">
						<a href="/employees">
							<img src="{{ asset('images/icons/users.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;თანამშრომლები
						</a>
					</li>
					<li style="padding-left: 10px;">
						<a href="/add_emp">
							<img src="{{ asset('images/icons/useradd.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;თანამშრომლის&nbsp;დამატება
						</a>
					</li>
				</ul>
				<li id="sub_3">
					<a href="javascript:void(0)">
						<img src="{{ asset('images/icons/tests.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;ტესტირება
						<span style="float: right;">
							<img src="{{ asset('images/icons/down.png') }}" style="width:18px">
						</span>
					</a>
				</li>
					<ul class="sub_menu3" id="sub3">
						<li>
							<a href="/create_test">&nbsp;&nbsp;&mdash;&nbsp;&nbsp;ტესტის შექმნა<b>/</b>შედგენა</a>
						</li>
						<li>
							<a href="/add_question">&nbsp;&nbsp;&mdash;&nbsp;კითხვები<b>/</b>დამატება</a>
						</li>
						<li>
							<a href="/test_result">&nbsp;&nbsp;&mdash;&nbsp;&nbsp;ტესტირების&nbsp;შედეგები</a>
						</li>
					</ul>
				</li> 
			@endif
			@if(auth()->user()->role == "manager")
				<li>
					<a href="/test_result">&nbsp;&nbsp;ტესტირების&nbsp;შედეგები</a>
				</li>
			@endif
			@if(auth()->user()->role == "employee")
				<li>
					<a href="/user_test_result">
						<i class="fa fa-check-square"></i>&nbsp;&nbsp;ჩემი&nbsp;ტესტირებები
					</a>
				</li>
			@endif
			<!-- END | HR-ის ქვე მენიუ -->
			<li>
				<a href="javascript:void(0)">
					<img src="{{ asset('images/icons/archive.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;არქივი
				</a>
			</li>
			<li>
				<a href="javascript:void(0)">
					<img src="{{ asset('images/icons/sfolder.png') }}" style="width: 20px; height:20px">&nbsp;&nbsp;ზიარი დოკუმენტები
				</a>
			</li>
		</ul>
	</div>
</div>
</body>
</html>