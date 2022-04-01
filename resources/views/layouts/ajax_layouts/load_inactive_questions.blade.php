<div style="display: flex;justify-content:flex-start;align-items:center;">
	<input type="search" name="search_question1" id="search_question1" placeholder="ძებნა...">
	<label>&nbsp;&nbsp;<span name="make-all-active" style="cursor: pointer">ყველას გააქტიურება</span></label>
</div>
<br>
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
	<tbody id="inactive-questions">
		@foreach($inactive_questions as $qd)
			<tr class="r">
				<td>{{ $qd->question_subject }}</td>
				<td>{{ $qd->type }}</td>
				<td>{!! $qd->question !!}</td>
				<td class="td">{{ $qd->A }}</td>
				<td class="td">{{ $qd->B }}</td>
				<td class="td">{{ $qd->C }}</td>
				<td class="td">{{ $qd->D }}</td>
				<td class="td">{{ $qd->correct }}</td>
				<td>
					@if(!is_null($qd->answers))
						@foreach ($qd->answers as $answers)
							{{ $answers }}
						@endforeach
					@endif
				</td>
				<td>
					@if(!is_null($qd->corrects))
						@foreach ($qd->corrects as $corrects)
							{{ $corrects }}
						@endforeach
					@endif
				</td>
				<td>{{ $qd->score }}</td>
				<td style="display: flex;justify-content: center;align-center:center;">
					<a href="/edit_question/{{ $qd->id }}/{{ $qd->type }}" class="info-btn" style="padding: 4px;margin:4px" title="კითხვის რედაქტირება">
						<img src="{{ asset('images/icons/white-edit.png') }}">
					</a>
					<a href="/delete_question/{{ $qd->id }}" class="danger-btn" style="padding: 4px;margin:4px" title="კითხვის წაშლა">
						<img src="{{ asset('images/icons/white-trash.png') }}">
					</a>
					<a href="javascript:void(0)" title="რედაქტირების ისტორია" data-questionid="{{ $qd->id }}" class="download-btn" style="padding: 4px;margin:4px" id="open_history">
						<img src="{{ asset('images/icons/history-white.png') }}">
					</a>
					@if($qd->status == 0)
						<div class="toggle1" style="background-color: #lightgray !important;" data-question-id="{{ $qd->id }}" data-switch="2">
							<div class="slider1" style="margin-left:5px !important"></div>
						</div>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
<script type="text/javascript" language="javascript">
	// ================= სწორი პასუხისთვის ვარიანტის დასახელების მიწერის ალგორითმი ================= //
	/* მოცემული კოდის საშუალებით ხდება ცხრილში მოცემული მონაცემების გაფილტვრა. სწორ პასუხებს 
	გვერდით მიეწერება ვარიანტის დასახელება, მაგ: ("ა"), ("ბ") და ა.შ */ 
	$(".r").each(function(i, data) {
		for(let i = 0; i < 3; i++) {
			if($(this).find(".td").eq(4).text().toString() == null || $(this).find(".td").eq(4).text().toString() == "") continue;
			
			if( $(this).find(".td").eq(4).text() == $(this).find(".td").eq(i).text() ) {
				$(this).find(".td").eq(4).html( $(this).find(".td").eq(4).text() + " <b>(" + $(".th").eq(i).text() + ")</b>");
			}
		}
	});
	// ================= სწორი პასუხისთვის ვარიანტის დასახელების მიწერის ალგორითმი ================= //
	
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

	// კითხვების გაფილტვრის ფუნქცია
	$("#search_question1").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#inactive-questions tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});
</script>