@include("layouts.header")

<script type="text/javascript">
	document.title = "პასუხების შემოწმება"; // მიმდინარე გვერდის სათაური
</script>

@if(auth()->user()->role == "employee")
	{{-- გვერდზე წვდომის განსაზღვრა--}}
	@php
		/* თუ ავტორიზირებული მომხმარებელი არის employee როლის მაშინ ის გადამისამართდება 
		მთავარ გვერდზე, რათა წვდომა არ მოხდეს მიმდინარე გვერდზე */
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<div class="main-block-add">
	<div class="table-block" style="overflow-x: scroll;max-width: 90% !important">
		<table>
			<thead>
				<tr>
					<th>კითხვა</th>
					<th>სწორი პასუხი</th>
					<th>გაცემული პასუხი</th>
					<th>სწორია</th>
					<th>არასწორია</th>
				</tr>
			</thead>
			<tbody>
				@foreach($answers as $ans)
					@if($ans->type == "single" || $ans->type == "multiple")
						@continue
					@endif
					<tr>
						<td>{!! str_replace("_", " ", $ans->question) !!}</td>
						<td>{{ $ans->correct}}</td>
						<td>{{ $ans->answer }}</td>
						<td>
							<form method="post" action="/checkfinal/yes/{{ $ans->user_id }}" class="make_correct">
								{{ csrf_field() }}
								<input type="hidden" name="qquestion" value="{!! $ans->question !!}" id="cqq">
								<input type="hidden" name="aanswer" value="{{ $ans->answer}}" id="caa">
								<button type="submit" class="d"><img src="{{ asset('images/icons/check.png') }}">&nbsp;სწორია</button>
							</form>
						</td>
						<td>
							<form method="post" action="/checkfinal/not/{{ $ans->user_id }}" class="make_wrong">
								{{ csrf_field() }}
								<input type="hidden" name="qquestion" value="{!! $ans->question !!}" id="cqq">
								<input type="hidden" name="aanswer" value="{{ $ans->answer}}" id="caa">
								<button type="submit" class="d1"><img src="{{ asset('images/icons/close-white.png') }}">&nbsp;არასწორია</button>
							</form>
						</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td>
						<img src="{{ asset("images/chevron-left.png") }}" style="width: 12px;height:12px">&nbsp;<span><strong id="back" style="cursor: pointer">უკან</strong></span><br>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$(".make_correct").each(function(i, d) {
			$(this).submit(function() {
				$child_que = $(this).find("#cqq").val();
				$child_ans = $(this).find("#caa").val();
				$.ajax({
					method : "POST",
					url : "/checkfinal/yes/{{ $ans->user_id }}",
					data : {
						qquestion : $child_que,//სწორ პასუხად მონიშვნის კითხვის ველის მნიშვნელობა
						aanswer : $child_ans, //სწორ პასუხად მონიშვნის პასუხის ველის მნიშვნელობა
						_token : $('[name="_token"]').val()
					},

					success : function(data) {
						if(data.status_corr == "1") window.alert("შეფასდა");
						else if(data.status_corr == "0") window.alert("ვერ შეფასდა");
					},

					error : function(err) {
						console.log(err);
						window.alert("დაფიქსირდა სერვერული შეცდომა!");
					}
				});

				return false;
			});
		});

		$(".make_wrong").each(function(i, d) {
			$(this).submit(function() {
				$child_que = $(this).find("#cqq").val();
				$child_ans = $(this).find("#caa").val();
				$.ajax({
					method : "POST",
					url : "/checkfinal/not/{{ $ans->user_id }}",
					data : {
						qquestion : $child_que,//არასწორ პასუხად მონიშვნის კითხვის ველის მნიშვნელობა
						aanswer : $child_ans, //არასწორ პასუხად მონიშვნის პასუხის ველის მნიშვნელობა
						_token : $('[name="_token"]').val()
					},

					success : function(data) {
						if(data.status_wrong == "1") window.alert("შეფასდა");
						else if(data.status_wrong == "0") window.alert("ვერ შეფასდა");
					},

					error : function(err) {
						console.log(err);
						window.alert("დაფიქსირდა სერვერული შეცდომა!");
					}
				});

				return false;
			});
		});
	});
</script>
@include("layouts.sidebar")
