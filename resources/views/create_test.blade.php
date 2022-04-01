@include("layouts.header")

@if(auth()->user()->role == "employee" || auth()->user()->role == "manager")
	{{-- გვერდზე წვდომის განსაზღვრა --}}
	@php
		/* თუ ავტორიზირებული მომხმარებელი არის employee ან manager როლის მაშინ ის გადამისამართდება 
		მთავარ გვერდზე, რათა წვდომა არ მოხდეს მიმდინარე გვერდზე */
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<!-- წარმატებული ვალიდაციის შეტყობინების კოდი -->
<div class="success-alert" style="display: none !important;position: fixed;top: 0;right: 0;margin: 30px">
	<p><strong></strong></p>
</div>

<div class="main-block-add">
	<!-- წარუმატებელი ვალიდაციის შეტყობინების კოდი -->
	<div class="danger-alert" style="display: none;position: fixed;top:0;right:0;margin:20px" id="danger-alert">
		<p><strong></strong></p>
	</div>
	<div class="test_form">
		<div style="margin: 10px !important;">
			<h3 style="text-align: center"><b>ტესტის შექმნა</b></h3>
			<hr>
			<form method="post" action="" id="createtest">
				{{ csrf_field() }}
				<table>
					<tr>
						<td>
							<label for="subject" style="margin-left: 15px">ტესტის თემატიკა</label><br>
							<input type="text" name="subject" id="subject" placeholder="ტესტის თემატიკა">
						</td>
						<td>
							<label for="test_date" style="margin-left: 15px">ჩატარების თარიღი</label><br>
							<input type="date" name="test_date" id="test_date" style="font-family: verdana !important">
						</td>
					</tr>
					<tr>
						<td>
							<label for="test_start_time" style="margin-left: 15px">დაწყების დრო</label><br>
							<input type="time" name="test_start_time" id="test_start_time" style="font-family: verdana !important">
						</td>
						<td>
							<label for="test_type" style="margin-left: 15px">ტესტის ტიპი</label><br>
							<select name="test_type" id="test_type">
								<option value="test">ტესტირება</option>
								<option value="quiz">გამოკითხვა</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="wrong_score" style="margin-left: 15px">არასწორი პასუხის ქულა</label><br>
							<select id="wrong_score" name="wrong_score">
								<option value="0">0</option>
								<option value="-0.2">-0.2</option>
								<option value="-1">-1</option>
							</select>
						</td>
						<td>
							<button type="submit" name="create_test" class="primary-btn" style="margin-top: 35px !important">შექმნა</button>
						</td>
					</tr>
				</table>
				<br>
			</form>
			{{-- აქ გამოვა შეცდომები, რომლებიც ვალიდაციისას დაფიქსირდება --}}
			@if(count($errors) > 0)
				<div class="danger-alert">
					@foreach($errors->all() as $error)
						<p><strong>{{ $error }}</strong></p>
					@endforeach
				</div>
			@endif
		</div>
	</div>
	<div>
		<div class="table-block">
			<div>
				<input type="search" name="search_test" id="search_test" placeholder="ძებნა...">
			</div>
			<div id="alltest"></div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript">
	document.title = "ტესტის შექმნა";

	// გვერდების ნომრებზე დაკლიკებისას ახალი გვერდის ავტომატურად ჩატვირთვის ფუნქიონალი
	$(document).on("click", ".pagination a", function() {
		event.preventDefault(); 
		var page = $(this).attr("href").split("page=")[1];

		$.get("/fetch_tests?page=" + page, function(response) {
			$("#alltest").html(response);
		});
	});

	// ტესტის ძებნის ფუნქციონალი
	$("#search_test").keyup(function() {
		let value = $(this).val(); // ძებნის ველში ჩაწერილი მნიშვნელობა
		$.get("/search_test", { value : value }, function(response) {
			$("#alltest").html(response);
		});
	});

	// =============== ტესტების გამოჩენის ajax ფუნქციოალი =================
	window.onload =  function () {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if(this.readyState == 4 && this.status == 200) {
				document.getElementById("alltest").innerHTML = this.responseText;
			}
		}

		xhttp.open("GET", "/showtests", true); // /showtests მარშუტი არის გაწერილი routes/web.php ფაილში 36-ე ხაზზე
		xhttp.send();
	}
	// =============== ტესტების გამოჩენის ajax ფუნქციოალი =================
</script>

@include("layouts.sidebar")