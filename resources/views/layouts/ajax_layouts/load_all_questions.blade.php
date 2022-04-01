<table>
	<thead>
		<tr>
			<th>თემა</th>
			<th>კითხვის ტიპი</th>
			<th>კითხვა</th>
			<th class="th">ა</th>
			<th class="th">ბ</th>
			<th class="th">გ</th>
			<th class="th">დ</th>
			<th>სწორი პასუხი</th>
			<th>სავარაუდო პასუხები</th>
			<th>სწორი პასუხები</th>
			<th>სწორი პასუხის ქულა</th>
		</tr>
	</thead>
	<tbody>
		@foreach($question_s as $question)
			<tr class="r">
				<td data-question-subject="subject">{{ $question->question_subject }}</td>
				<td>{{ $question->type }}</td>
				<td>{!! $question->question !!}</td>
				<td class="td">{{ $question->A }}</td>
				<td class="td">{{ $question->B }}</td>
				<td class="td">{{ $question->C }}</td>
				<td class="td">{{ $question->D }}</td>
				<td class="td">{{ $question->correct }}</td>
				<td>
					@if(!is_null($question->answers))
						@foreach ($question->answers as $answers)
							{{ $answers }}
						@endforeach
					@endif
				</td>
				<td>
					@if(!is_null($question->corrects))
						@foreach ($question->corrects as $corrects)
							{{ $corrects }}
						@endforeach
					@endif
				</td>
				<td>{{ $question->score }}</td>
				<td style="display: flex;justify-content: center;align-content:center;">
					<a href="/edit_question/{{ $question->id }}/{{ $question->type }}" class="info-btn" style="padding: 4px;margin:4px" title="კითხვის რედაქტირება">
						<img src="{{ asset('images/icons/white-edit.png') }}">
					</a>
					<a href="javascript:void(0)" class="danger-btn" data-question-id="{{ $question->id }}" style="padding: 4px;margin:4px" title="კითხვის წაშლა">
						<img src="{{ asset('images/icons/white-trash.png') }}">
					</a>
					<a href="javascript:void(0)" title="რედაქტირების ისტორია" data-questionid="{{ $question->id }}" class="download-btn" style="padding: 4px;margin:4px" id="open_history">
						<img src="{{ asset('images/icons/history-white.png') }}">
					</a>
					@if($question->status == 1)
						<div class="toggle" style="background-color: #1ca35e !important;" data-question-id="{{ $question->id }}" data-switch="1">
							<div class="slider" style="margin-left:30px !important"></div>
						</div>
					@endif
					@if($question->status == 0)
						<div class="toggle1" style="background-color: #lightgray !important;" data-question-id="{{ $question->id }}" data-switch="2">
							<div class="slider1" style="margin-left:5px !important"></div>
						</div>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td>{{ $question_s->links("pagination::bootstrap-4") }}</td>
		</tr>
	</tfoot>
</table>

<script type="text/javascript" language="javascript">
	// ================= სწორი პასუხისთვის ვარიანტის დასახელების მიწერის ალგორითმი ================= //
	/* მოცემული კოდის საშუალებით ხდება ცხრილში მოცემული მონაცემების გაფილტვრა. სწორ პასუხებს 
	გვერდით მიეწერება ვარიანტის დასახელება, მაგ: ("ა"), ("ბ") და ა.შ */ 
	$(".r").each(function(i, data) {
		for(let i = 0; i < 4; i++) {
			if($(this).find(".td").eq(4).text().toString() == null || $(this).find(".td").eq(4).text().toString() == "") continue;
			if( $(this).find(".td").eq(4).text().toString() == $(this).find(".td").eq(i).text().toString() ) {
				$(this).find(".td").eq(4).html( $(this).find(".td").eq(4).text().toString() + " <b>(" + $(".th").eq(i).text().toString() + ")</b>");
			}
		}
	});
	// ================= სწორი პასუხისთვის ვარიანტის დასახელების მიწერის ალგორითმი ================= //

	// გადამრთველის ფუნქციონალი კითხვების სტატუსის განსასაზღვრად
	$(".toggle").click(function() {
		let questionId = $(this).attr("data-question-id"); // კონრეტული კითხვის id ნომერი
		let switcher = Number.parseInt($(this).attr("data-switch"));
		if(switcher % 2 == 1) {
			$(this).find(".slider").css("margin-left", "5px");
			$(this).css("background-color", "lightgray");
			$.get("/set_inactive", {id : questionId}, function(response) {
				console.log(response);
			});
		}
		if(switcher % 2 == 0) {
			$(this).find(".slider").css("margin-left", "30px");
			$(this).css("background-color", "#1ca35e");
			$.get("/set_active", {id : questionId}, function(response) {
				console.log(response);
			});
		}
		$(this).attr("data-switch", switcher += 1);
	});
	
	// გადამრთველის ფუნქციონალი კითხვების სტატუსის განსასაზღვრად
	$(".toggle1").click(function() {
		let questionId = $(this).attr("data-question-id"); // კონრეტული კითხვის id ნომერი
		let switcher = Number.parseInt($(this).attr("data-switch"));
		if(switcher % 2 == 1) {
			$(this).find(".slider1").css("margin-left", "5px");
			$(this).css("background-color", "lightgray");
			$.get("/set_inactive", {id : questionId}, function(response) {
				console.log(response);
			});
		}
		if(switcher % 2 == 0) {
			$(this).find(".slider1").css("margin-left", "30px");
			$(this).css("background-color", "#1ca35e");
			$.get("/set_active", {id : questionId}, function(response) {
				console.log(response);
			});
		}
		$(this).attr("data-switch", switcher += 1);
	});

	// კითხვის წაშლის ღილაკზე დაკლიკებით გამოიტანს შეტყობინებას ნამდვილად სურს თუ არა წაშლა
	$(".danger-btn").on("click", function() {
		$(".agree").attr("href", "/delete_question/" + $(this).attr("data-question-id"));
		$(".modal-action").fadeIn("fast");
	});

	// კითხვის ისტორიის გასხნის ფუნქციონალი
	$("[data-questionid]").click(function() {
		let questionId = $(this).attr("data-questionid");
		$.ajax({
			method : "GET",
			url : "/load_history",
			data : {
				questionId : questionId
			},
			success : function(data) {
				$("#history-data").html(data);
			}
		});
		$(".history-modal").fadeIn("fast");
	});
	
	// არააქტიური კითხვების ფანჯრის გახსნის ფუნქციონალი
	$("#open-disabled").click(function() {
		$(".disabled-questions-modal").fadeIn("fast");
		$.get("/load_inactives", function(data) {
			$("#inactives").html(data);
		});
	});

	// წაშლილი კითხვების ფანჯრის გახსნის ფუნქციონალი
	$("#open-deleted").click(function() {
		$(".deleted-questions-modal").fadeIn("fast");
		$.get("/load_deleteds", function(data) {
			$("#deleteds").html(data);
		});
	});

	// კითხვების გვერდებად დანომვრა. გვერდზე დაკლიკებისას გვერდის გადატვირთვის გარეშე ჩაიტვირთება
	// ახალ გვერდზე არსებული კითხვები 
	$(document).on("click", ".pagination a", function(event){
		event.preventDefault(); 
		var page = $(this).attr("href").split("page=")[1];
		$.ajax({
			url : "/fetch_data?page=" + page,
			success : function(data) {
				$("#all").html(data);
			}
		});
	});
</script>