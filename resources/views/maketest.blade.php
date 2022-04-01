@include("layouts.header")


@if(auth()->user()->role == "employee" || auth()->user()->role == "manager")
	@php
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<script type="text/javascript" language="javascript">
	document.title = "ტესტის შედგენა";
</script>

<div class="main-block-add" style="justify-content: flex-start !important; align-items: flex-start !important">
	<div class="maketest-block">
		<input type="hidden" name="ts" id="ts" value="{{ $test_subject }}">
		<input type="hidden" name="ws" id="ws" value="{{ $w }}">
		<form method="post" action="" id="load_question">
			{{ csrf_field() }}
			<table>
				<tr>
					<td>
						<input type="text" name="subject" id="subject" placeholder="კითხვის თემატიკა">
					</td>
				</tr>
				<tr>
					<td>
						<input type="number" placeholder="კითხვის ქულა" min="1" max="10" name="score" id="score">
					</td>
				</tr>
				<tr>
					<td>
						<input type="number" name="quantity" id="quantity" placeholder="რაოდენობა" min="0">
					</td>
				</tr>
				<tr>
					<td>
						<select name="type_question" id="type_question">
							<option value="free">ღია</option>
							<option value="single">დახურული</option>
							<option value="multiple">ასარჩევი</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<button type="submit" name="load_questions" id="lq" style="margin-top: 4px;">კითხვების ჩვენება</button>
					</td>
				</tr>
			</table>
		</form>
		@if(Session::has("done_question"))
			<div class="success-alert">
				<strong>{{ Session::get("done_question") }}</strong>
			</div>
		@endif
	</div>

	<div class="sidepanel">
		<a href="javascript:void(0)" style="font-size: 20px;color:#202020;font-weight: bold" id="popup_close"><strong>&times;</strong></a>
		<center>
			<a href="javascript:void(0)" id="checkall">ყველას მონიშვნა</a>
		</center>
		<hr>
		<div id="qs"></div>
	</div>

	<div class="maketest-block">
		<form method="post" action="" id="load_employees">
			{{ csrf_field() }}
			<select name="depart_ment" id="depart_ment">
				<option value="">აირჩიეთ დეპარტამენტი</option>
				<option value="შიდა აუდიტისა და კონტროლის დეპარტამენტი">შიდა აუდიტისა და კონტროლის დეპარტამენტი</option>
				<option value="საზოგადოებასთან ურთიერთობისა და მარკეტინგის დეპარტამენტი">საზოგადოებასთან ურთიერთობისა და მარკეტინგის დეპარტამენტი</option>
				<option value="ადამიანური რესურსების მართვის დეპარტამენტი">ადამიანური რესურსების მართვის დეპარტამენტი</option>
				<option value="საფინანსო დეპარტამენტი">საფინანსო დეპარტამენტი</option>
				<option value="ადმინისტრაციული დეპარტამენტი">ადმინისტრაციული დეპარტამენტი</option>
				<option value="საინფორმაციო დეპარტამენტი">საინფორმაციო დეპარტამენტი</option>
				<option value="პროექტების საოპერაციო დეპარტამენტი">პროექტების საოპერაციო დეპარტამენტი</option>
				<option value="პროექტების მართვისა და ტექნიკური დახმარების დეპარტამენტი">პროექტების მართვისა და ტექნიკური დახმარების დეპარტამენტი</option>
				<option value="პროექტების განვითარების დეპარტამენტი">პროექტების განვითარების დეპარტამენტი</option>
				<option value="კოოპერატივების განვითარების დეპარტამენტი">კოოპერატივების განვითარების დეპარტამენტი</option>
				<option value="რეგიონებთან ურთიერთობის დეპარტამენტი">რეგიონებთან ურთიერთობის დეპარტამენტი</option>
			</select>
			<select name="services" id="services">
				<option value="">აირჩიეთ სამსახური</option>
			</select>
			<select name="position" id="position">
				<option value="">აირჩიეთ პოზიცია</option>
				<option value="დირექტორი">დირექტორი</option>
				<option value="დირექტორის პირველი მოადგილე">დირექტორის პირველი მოადგილე</option>
				<option value="დირექტორის მოადგილე">დირექტორის მოადგილე</option>
				<option value="დირექტორის მრჩეველი">დირექტორის მრჩეველი</option>
				<option value="დირექტორის თანაშემწე">დირექტორის თანაშემწე</option>
				<option value="დირექტორის პირველი მოადგილის თანაშემწე">დირექტორის პირველი მოადგილის თანაშემწე</option>
				<option value="დირექტორის მოადგილის თანაშემწე">დირექტორის მოადგილის თანაშემწე</option>
				<option value="შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი">შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი</option>
				<option value="პროექტების კოორდინატორი">პროექტების კოორდინატორი</option>
				<optgroup label="შტატგარეშე" id="position_freelance"></optgroup>
				<optgroup label="შტატიანი" id="position_staff"></optgroup>
				<optgroup label="რეგიონები" id="reg_positions" hidden></optgroup>
			</select>
			<button type="submit" name="lemp" style="margin-top: 4px;" id="lemp">თანამშრომლების ჩვენება</button>
		</form>
		<center>
			@if(Session::has("done_test"))
				<div class="success-alert">
					<strong>{{ Session::get("done_test") }}</strong>
				</div>
				<br>
			@endif
			@if(Session::has("error_test"))
				<div class="danger-alert">
					<strong>{{ Session::get("error_test") }}</strong>
				</div>
				<br>
			@endif
		</center>
	</div>
</div>

@include("layouts.sidebar")