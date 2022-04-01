@include("layouts.header")

@if(auth()->user()->role == "employee" || auth()->user()->role == "manager")
	{{-- გვერდზე წვდომის განსაზღვრა--}}
	@php
		/* თუ ავტორიზირებული მომხმარებელი არის employee ან manager როლის მაშინ ის გადამისამართდება 
		მთავარ გვერდზე, რათა წვდომა არ მოხდეს მიმდინარე გვერდზე */
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<script type="text/javascript">
	document.title = "თანამშრომლები";

	$(document).ready(function() {
		$("#search-employee").keyup(function() {
			$.get("/search_employees", { value : $(this).val() }, function(response) {
				$("#all-employee").html(response);
			});
		});

		$(document).on("click", ".pagination a", function() {
			event.preventDefault(); 
			var page = $(this).attr("href").split("page=")[1];

			$.get("/fetch_employees?page=" + page, function(response) {
				$("#all-employee").html(response);
			});
		});
	});

	$(window).on("load", function() {
		$.get("/load_employees", function(response) {
			$("#all-employee").html(response);
		});
	});
</script>

<div class="main-block" id="employees">
	<div style="width: 100%;display:flex;justify-content:flex-end;align-items:center;align-content:flex-end;padding: 10px">
		<input type="search" name="search_employee" placeholder="ძებნა..." style="background-color: #fff; !important; border: 2px solid #fff !important;" id="search-employee">
		<button type="button" id="filter"><img src="{{ asset('../images/icons/filter.png') }}"></button>
	</div>
	<br>
	<div id="all-employee" style="display: flex;flex-direction: row;flex-wrap: wrap"></div>
	<div class="filter-block">
		<a href="javascript:void(0)" style="font-size: 20px;color:#202020;font-weight: bold" id="popup_close"><strong>&times;</strong></a>
		<form method="GET" action="/filter_employee" id="filter_employee">
			{{ csrf_field() }}
			<select name="department" id="department">
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
			<select name="service" id="service">
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
			<button type="submit" name="filter_emp" id="filter_emp">გაფილტვრა</button>
		</form>
	</div>
</div>

<div class="modal-action" style="display: none !important">
	<div class="inner-modal">
		<center>
			<p><b>ნამდვილათ გსურთ წაშლა?</b></p>
			<hr>
			<a href="#" class="agree">დიახ</a>
			<a href="javascript:void(0)" class="closemodal">არა</a>
		</center>
	</div>
</div>

<div class="edit-modal" style="display: none !important">
	<div class="success-alert" style="display: none !important;position: absolute;top: 0;right: 0;margin: 30px">
		<p><strong></strong></p>
	</div>
	<a href="javascript:void(0)" class="closemodal1">&times;</a>
	<div class="inner-modal">
		<center>
			<div class="edit_employee_block">
				<h4><b>თანამშრომლის რედაქტირება</b></h4>
				<hr>
				<form method="post" action="" id="edit_form">
					{{ csrf_field() }}
					<input type="hidden" id="id" name="id">
					<input type="text" placeholder="სახელი" name="firstname" id="firstname1" required>
					<input type="text" placeholder="გვარი" name="lastname" id="lastname1" required>
					<input type="email" placeholder="იმეილი" name="email" id="email1" required style="font-family: verdana !important">
					<input type="text" placeholder="პირადი ნომერი" name="pid" id="pid1" required style="font-family: verdana !important">
					<input type="text" placeholder="ტელეფონი" name="phone" id="phone1" required style="font-family: verdana !important">
					<select name="department" id="department1" required>
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
					<select name="service" id="service1"></select>
					<select name="position" id="position1">
						<option value="დირექტორი">დირექტორი</option>
						<option value="დირექტორის პირველი მოადგილე">დირექტორის პირველი მოადგილე</option>
						<option value="დირექტორის მოადგილე">დირექტორის მოადგილე</option>
						<option value="დირექტორის მრჩეველი">დირექტორის მრჩეველი</option>
						<option value="დირექტორის თანაშემწე">დირექტორის თანაშემწე</option>
						<option value="დირექტორის პირველი მოადგილის თანაშემწე">დირექტორის პირველი მოადგილის თანაშემწე</option>
						<option value="დირექტორის მოადგილის თანაშემწე">დირექტორის მოადგილის თანაშემწე</option>
						<option value="შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი">შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი</option>
						<option value="პროექტების კოორდინატორი">პროექტების კოორდინატორი</option>
						<optgroup label="შტატგარეშე" id="position_freelance1"></optgroup>
						<optgroup label="შტატიანი" id="position_staff1"></optgroup>
						<optgroup label="რეგიონები" id="reg_positions1" hidden></optgroup>
					</select>
					<select name="role" id="role1" required>
						<optgroup label="აირჩიეთ ახალი როლი">
							<option value="hr">HR</option>
							<option value="employee">თანამშრომელი</option>
							<option value="manager">მენეჯრი</option>
						</optgroup>
					</select>
					<button type="submit" id="eemp" class="primary-btn">თანამშრომლის რედაქტირება</button>
				</form>
				<br>
			</div>
		</center>
	</div>
</div>

@include("layouts.sidebar")