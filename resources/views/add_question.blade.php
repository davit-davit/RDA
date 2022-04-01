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

<!-- წარმატებული ვალიდაციის შეტყობინების კოდი -->
<div class="success-alert" style="display: none !important;position: fixed;top: 0;right: 0;margin: 30px">
	<p><strong></strong></p>
</div>

<div class="main-block-add">
	<div class="question-block">
		<center>
			<button class="switch">დახურული</button>
			<button class="switch">ღია</button>
			<button class="switch">ასარჩევი</button>
		</center>
		<div class="single" id="single" style="margin-top: 20px">
			<form method="post" action="" id="one_correct">
				{{ csrf_field() }}
				<textarea name="question" id="question" placeholder="შეიყვანეთ კითხვა"></textarea>
				<table>
					<tr>
						<td>
							<br><label for="a" style="margin-left: 16px">ვარიანტი (ა)</label><br>
							<input type="text" placeholder="ვარიანტი (ა)" name="a" id="a" autocomplete="off">
							<span class="set_answer" id="a">
								<img src="{{ asset('images/icons/checkk.png') }}">
							</span>
						</td>
						<td>
							<br><label for="b" style="margin-left: 16px">ვარიანტი (ბ)</label><br>
							<input type="text" placeholder="ვარიანტი (ბ)" name="b" id="b" autocomplete="off">
							<span class="set_answer" id="b">
								<img src="{{ asset('images/icons/checkk.png') }}">
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label for="c" style="margin-left: 16px">ვარიანტი (გ)</label><br>
							<input type="text" placeholder="ვარიანტი (გ)" name="c" id="c" autocomplete="off">
							<span class="set_answer" id="c">
								<img src="{{ asset('images/icons/checkk.png') }}">
							</span>
						</td>
						<td>
							<label for="d" style="margin-left: 16px">ვარიანტი (დ)</label><br>
							<input type="text" placeholder="ვარიანტი (დ)" name="d" id="d" autocomplete="off">
							<span class="set_answer" id="d">
								<img src="{{ asset('images/icons/checkk.png') }}">
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label for="score" style="margin-left: 16px">ქულის მინიჭება</label><br>
							<input type="number" min="1" max="10" id="score" name="score" placeholder="ქულის მინიჭება">
						</td>
						<td>
							<label for="duration" style="margin-left: 16px">ხანგრძლივობა</label><br>
							<input type="number" placeholder="ხანგრძლივობა" name="duration" id="duration" min="0" step="0.5">
						</td>
					</tr>
					<tr>
						<td>
							<label for="subject" style="margin-left: 16px;">კითხვის თემატიკა</label><br>
							<input type="text" name="subject" id="subject" placeholder="კითხვის თემატიკა" autocomplete="off"><span id="open_subjects" style="cursor: pointer"><img src="{{ asset('images/icons/plus.png')}}"></span>
							<div class="subjects_dropdown">
								<table class="datas"></table>
							</div>
							<input type="hidden" name="correct" id="correct">
						</td>
						<td>
							<button type="submit" name="add_single_choice" class="p" style="margin-top: 37px">დამატება</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="single" id="freeq" style="display: none !important;margin-top: 20px !important">
			<form method="post" action="" id="add_question_free">
				{{ csrf_field() }}
				<textarea name="question1" id="question1" placeholder="შეიყვანეთ კითხვა"></textarea>
				<table>
					<tr>
						<td>
							<br><label style="margin-left: 16px" for="correct_answer_free">სწორი პასუხი</label><br>
							<input type="text" id="correct_answer_free" name="correct_answer_free" placeholder="სწორი პასუხი" autocomplete="off">
						</td>
						<td>
							<br><label style="margin-left: 16px" for="score1">ქულის მინიჭება</label><br>
							<input type="number" min="1" max="10" id="score1" name="score1" placeholder="ქულის მინიჭება">
						</td>
					</tr>
					<tr>
						<td>
							<br><label style="margin-left: 16px" for="subject1">კითხვის თემატიკა</label><br>
							<input type="text" name="subject1" id="subject1" placeholder="კითხვის თემატიკა" autocomplete="off"><span id="open_subjects1" style="cursor: pointer"><img src="{{ asset('images/icons/plus.png')}}"></span>
							<div class="subjects_dropdown">
								<table class="datas"></table>
							</div>
						</td>
						<td>
							<br><label style="margin-left: 16px" for="duration1">ხანგრძლივობა</label><br>
							<input type="number" placeholder="ხანგრძლივობა" name="duration1" id="duration1" min="0" step="0.5">
						</td>
					</tr>
					<tr>
						<td>
							<button type="submit" name="add_free" class="p">დამატება</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="single" id="multiple" style="display: none !important;margin-top: 20px !important">
			<!-- <div class="layer"><h1>Under Construction</h1></div> -->
			<form method="post" action="/add_multiple" id="add_multiple_form">
				{{ csrf_field() }}
				<textarea name="question2" id="question2" placeholder="შეიყვანეთ კითხვა"></textarea>
				<table>
					<tr>
						<td>
							<br><label style="margin-left: 16px" for="score2">ქულის მინიჭება</label><br>
							<input type="number" min="1" max="10" id="score2" name="score2" placeholder="ქულის მინიჭება">
						</td>
						<td>
							<br><label style="margin-left: 16px" for="duration2">ხანგრძლივობა</label><br>
							<input type="number" placeholder="ხანგრძლივობა" name="duration2" id="duration2" min="0" step="0.5">
						</td>
					</tr>
					<tr>
						<td>
							<br><label style="margin-left: 16px" for="subject2">კითხვის თემატიკა</label><br>
							<input type="text" name="subject2" id="subject2" placeholder="კითხვის თემატიკა" autocomplete="off"><span id="open_subjects1" style="cursor: pointer"><img src="{{ asset('images/icons/plus.png')}}"></span>
							<div class="subjects_dropdown">
								<table class="datas"></table>
							</div>
						</td>
						<td>
							<button type="button" class="p" id="new_answer" style="margin-top: 57px">ახალი პასუხი&nbsp;<img src="{{ asset('images/icons/add.png')}}"></button>
						</td>
					</tr>
					<tr id="multiple_answer"></tr>
					<tr>
						<td>
							<button type="submit" name="add_multiple" class="p">დამატება</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<img src="{{ asset("images/chevron-left.png") }}" style="width: 12px;height:12px">&nbsp;<span><strong id="back" style="cursor: pointer">უკან</strong></span>
	</div>
	<div class="table-block" style="overflow-x: scroll !important; max-width: 100% !important;">
		<div>
			<div style="display:flex;justify-content:flex-start;align-items:center;">
				<p style="margin: 10px !important" id="open-disabled"><a href="javascript:void(0)">არააქტიური კითხვები&nbsp;&nbsp;|</a></p>
				<p style="margin: 10px !important" id="open-deleted"><a href="javascript:void(0)"><img src="{{ asset('images/icons/trash.png') }}" style="width: 20px;height:20px;">&nbsp;&nbsp;წაშლილი კითხვები&nbsp;&nbsp;|</a></p>
				<p style="margin: 10px !important" id="make-inactive-all"><a href="javascript:void(0)">არააქტიურზე გადაყვანა</a></p>
				<input type="search" name="search_question" id="search_question" placeholder="ძებნა...">
				<button type="button" id="filter"><img src="{{ asset('../images/icons/filter.png') }}"></button>
			</div>
		</div>
		<div id="all"></div>
	</div>
	<div class="filter-block">
		<a href="javascript:void(0)" style="font-size: 20px;color:#202020;font-weight: bold" id="popup_close"><strong>&times;</strong></a>
		<form method="GET" action="/filter_questions" id="question_filter_form">
			{{ csrf_field() }}
			<input type="text" name="subject" id="subject" placeholder="კითხვის თემატიკა" autocomplete="off">
			<div class="subjects_dropdown" style="width: 80% !important">
				<table class="datas"></table>
			</div>
			<!-- მოცემულ დროფდაუნ ელემენტში მოცემულია კითხვის ტიპები -->
			<select name="qtype" id="qtype">
				<option value="">აირჩიეთ კითხვის ტიპი</option>
				<option value="free">ღია</option>
				<option value="single">დახურული</option>
				<option value="multiple">ასარჩევი</option>
			</select>
			<!-- მოცემულ დროფდაუნ ელემენტში მოცემულია ქულები თუ რამდენით ფასდება არასწორი პასუხი -->
			<select name="wscore" id="wscore">
				<option value="">არასწორი პასუხის ქულა</option>
				<option value="0">0</option>
				<option value="-0.2">-0.2</option>
				<option value="-1">-1</option>
			</select>
			<input type="number" min="1" max="10" id="f_score" name="f_score" placeholder="კითხვის ქულა (წონა)">
			<input type="number" placeholder="ხანგრძლივობა" name="f_duration" id="f_duration" min="0">
			<button type="submit" name="filter_question" id="filter_question">გაფილტვრა</button>
		</form>
	</div>
</div>
<!-- === დაბლარული ბლოკი, რომელიც გამოჩნდება ფილტრაციის გამოჩენისას === -->
<div class="blur-block"></div>
<!-- === დაბლარული ბლოკი, რომელიც გამოჩნდება ფილტრაციის გამოჩენისას === -->
<!-- =============== კითხვის წაშლისას შეტყობინების ჩვენების კოდი ============================== -->
<div class="modal-action" style="display: none !important;z-index: 2000 !important">
	<div class="inner-modal">
		<center>
			<p><b>ნამდვილათ გსურთ წაშლა?</b></p>
			<hr>
			<a href="" class="agree">დიახ</a>
			<a href="javascript:void(0)" class="closemodal">არა</a>
		</center>
	</div>
</div>
<!-- ============================== არააქტიური კითხვების ფანჯრის კოდი ============================== -->
<div class="disabled-questions-modal" style="display: none !important">
	<a href="javascript:void(0)" class="closemodal1">&times;</a>
	<div class="inner-modal" style="overflow-x: scroll !important; max-width: 80% !important;" id="inactives"></div>
</div>
<!-- ============================== წაშლილი კითხვების ფანჯრის კოდი ============================== -->
<div class="deleted-questions-modal" style="display: none !important">
	<a href="javascript:void(0)" class="closemodal1">&times;</a>
	<div class="inner-modal" style="overflow-x: scroll !important; max-width: 80% !important;" id="deleteds"></div>
</div>

<!-- კითხვის დარედაქტირების ისტორიის ფანჯარა -->
<div class="history-modal" style="display: none !important">
	<a href="javascript:void(0)" class="closemodal1">&times;</a>
	<div class="inner-modal">
		<table>
			<thead>
				<tr>
					<th>ავტორი</th>
					<th>თარიღი</th>
				</tr>
			</thead>
			<tbody id="history-data"></tbody>
		</table>
	</div>
</div>

<div class="subject-modal" id="add_subject" style="display: none !important">
	<a href="javascript:void(0)" class="closemodal1">&times;</a>
	<div class="inner-modal">
		<form method="POST" action="" id="subj_add">
			{{ csrf_field() }}
			<input type="hidden" value="<?php echo csrf_token(); ?>" id="tok_en">
			<table>
				<tr>
					<td>
						<input type="text" placeholder="შეიყვანეთ თემის დასახელება" name="subject_name" id="subject_name" autocomplete="off">
					</td>
				</tr>
				<tr>
					<td>
						<button type="submit" name="add_subj">დამატება</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript">
	document.title = "კითხვარის დამატება"; // მიმდინარე გვერდის სათაური

	$(document).ready(function() {
		$(".closemodal1").click(function() {
			$(this).parent().fadeOut("fast");
		});
		// ამ ajax მოთხოვნის საშუალებით მოხდება ბაზაში ატვირთული ყველა კითხვის ჩატვირთვა მიმდინარე
		// გვერდზე
		$.get("/load_all_question", function(data) {
			$("#all").html(data);
		});

		$("#search_question").keyup(function() {
			let search_value = $(this).val();
			$.get("/questions_live_search", { value : search_value }, function(response) {
				$("#all").html(response);
			});
		});
	});
	
	// ==================== სწორი პასუხები ====================== //
	let correct_count = 1;

	$(document).on("click", ".mark", function() {
		let switcher = Number.parseInt($(this).attr("data-id"));
		if($(this).attr("data-id") % 2 == 1) {
			var correct_value = $(this).parent().parent().find(":text").val();
			$(this).attr("src", "http://edu.rda.gov.ge/images/icons/checked.png");
			$("#multiple_answer").append(`<input type="hidden" name="corrects[]" value="${correct_value}" class="corr" data-id-s="${correct_count}">`);
		}else {
			$(this).attr("src", "http://edu.rda.gov.ge/images/icons/checkk.png");
			$("#multiple_answer").find(`[data-id-s="${correct_count - 1}"]`).remove();
		}
		correct_count++;
		$(this).attr("data-id", switcher += 1);
	});
	// ==================== სწორი პასუხები ====================== //
</script>

@include("layouts.sidebar")