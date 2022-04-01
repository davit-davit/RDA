@include("layouts.header")

@if(auth()->user()->role == "employee" || auth()->user()->role == "manager")
	@php
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<div class="success-alert" style="display: none !important;position: absolute;top: 0;right: 0;margin: 30px">
	<p><strong></strong></p>
</div>

<div class="main-block-add">
	<div class="question-block">
		<center>
			<span style="cursor: pointer" id="open_history"><img src="{{ asset('images/icons/history.png') }}"></span>
		</center>
		<br>
		@if($type == "single")
			<!-- დახურული კითხვის რედაქტირების ბლოკის დასაწყისი -->
			<div class="single" style="margin-top: 20px">
				<form method="post" action="" id="edit_one_correct">
					<input type="hidden" id="qid" value="{{ $id }}">
					{{ csrf_field() }}
					<textarea name="question" id="question" placeholder="შეიყვანეთ კითხვა">
						{!! $question->question !!}
					</textarea>
					<table>
						<tr>
							<td>
								<br><label for="a" style="margin-left: 16px">ვარიანტი (ა)</label><br>
								<input type="text" placeholder="ვარიანტი A" name="a" id="a" value="{{ $question->A }}">
								<span class="set_answer" id="a">
									<img src="{{ asset('images/icons/checkk.png') }}">
								</span>
							</td>
							<td>
								<br><label for="b" style="margin-left: 16px">ვარიანტი (ბ)</label><br>
								<input type="text" placeholder="ვარიანტი B" name="b" id="b" value="{{ $question->B }}">
								<span class="set_answer" id="b">
									<img src="{{ asset('images/icons/checkk.png') }}">
								</span>
							</td>
						</tr>
						<tr>
							<td>
								<br><label for="c" style="margin-left: 16px">ვარიანტი (გ)</label><br>
								<input type="text" placeholder="ვარიანტი C" name="c" id="c" value="{{ $question->C }}">
								<span class="set_answer" id="c">
									<img src="{{ asset('images/icons/checkk.png') }}">
								</span>
							</td>
							<td>
								<br><label for="d" style="margin-left: 16px">ვარიანტი (დ)</label><br>
								<input type="text" placeholder="ვარიანტი D" name="d" id="d" value="{{ $question->D }}">
								<span class="set_answer" id="d">
									<img src="{{ asset('images/icons/checkk.png') }}">
								</span>
							</td>
						</tr>
						<tr>
							<td>
								<br><label for="score" style="margin-left: 16px">ქულის მინიჭება</label><br>
								<input type="number" min="1" max="10" id="score" name="score" placeholder="ქულის მინიჭება" value="{{ $question->score }}">
							</td>
							<td>
								<br><label for="duration" style="margin-left: 16px">ხანგრძლივობა</label><br>
								<input type="number" placeholder="ხანგრძლივობა" name="duration" id="duration" min="0" step="0.5" value="{{ $question->duration_minute }}">
							</td>
						</tr>
						<tr>
							<td>
								<br><label for="subject" style="margin-left: 16px">კითხვის თემატიკა</label><br>
								<input type="text" name="subject" id="subject" placeholder="კითხვის თემატიკა" list="subjects" value="{{ $question->question_subject }}">
								<datalist id="subjects">
									@foreach ($subjects as $s)
										<option value="{{$s->subject_name}}"></option>
									@endforeach
								</datalist>
								<input type="hidden" name="correct" id="correct">
							</td>
							<td>
								<button type="submit" name="add_single_choice" class="p" style="margin-top: 57px">რედაქტირება</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<!-- დახურული კითხვის რედაქტირების ბლოკის დასასრული -->
		@endif
		@if($type == "free")
			<!-- ღია კითხვის რედაქტირების ბლოკის დასაწყისი -->
			<div class="single">
				<form method="post" action="" id="edit_question_free">
					{{ csrf_field() }}
					<input type="hidden" id="qid" value="{{ $id }}">
					<textarea name="question1" id="question1" placeholder="შეიყვანეთ კითხვა">
						{!! $question->question !!}
					</textarea>
					<br>
					<table>
						<tr>
							<td>
								<br><label for="correct_answer_free" style="margin-left: 16px">სწორი პასუხი</label><br>
								<input type="text" id="correct_answer_free" name="correct_answer_free" placeholder="სწორი პასუხი" value="{{ $question->correct }}">
							</td>
							<td>
								<br><label for="score1" style="margin-left: 16px">ქულის მინიჭება</label><br>
								<input type="number" min="1" max="10" id="score1" name="score1" placeholder="ქულის მინიჭება" value="{{ $question->score }}">
							</td>
						</tr>
						<tr>
							<td>
								<br><label for="subject1" style="margin-left: 16px">კითხვის თემატიკა</label><br>
								<input type="text" name="subject1" id="subject1" placeholder="კითხვის თემატიკა" list="subjects" value="{{ $question->question_subject }}">
								<datalist id="subjects">
									@foreach ($subjects as $s)
										<option value="{{$s->subject_name}}"></option>
									@endforeach
								</datalist>
							</td>
							<td>
								<br><label for="duration1" style="margin-left: 16px">ხანგრძლივობა</label><br>
								<input type="number" placeholder="ხანგრძლივობა" name="duration1" id="duration1" min="0" step="0.5" value="{{ $question->duration_minute }}">
							</td>
						</tr>
						<tr>
							<td>
								<button type="submit" name="edit_free" class="p">რედაქტირება</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<!-- ღია კითხვის რედაქტირების ბლოკის დასასრული -->
		@endif
		@if($type == "multiple")
			<!-- ასარჩევი კითხვის რედაქტირების ბლოკის დასაწყისი -->
			<div class="single">
				<form method="POST" action="/edit_multiple">
					{{ csrf_field() }}
					<input type="hidden" name="qid" id="qid" value="{{ $id }}">
					<textarea name="question2" id="question2" placeholder="შეიყვანეთ კითხვა">
						{!! $question->question !!}
					</textarea>
					<br>
					<table>
						<tr>
							<td>
								<br><label for="duration2" style="margin-left: 16px">ხანგრძლივობა</label><br>
								<input type="number" placeholder="ხანგრძლივობა" name="duration2" id="duration2" min="0" value="{{ $question->duration_minute }}">
							</td>
							<td>
								<br><label for="score2" style="margin-left: 16px">ქულის მინიჭება</label><br>
								<input type="number" min="1" max="10" id="score2" name="score2" step="0.5" placeholder="ქულის მინიჭება" value="{{ $question->score }}">
							</td>
						</tr>
						<tr>
							<td>
								<br><label for="subject2" style="margin-left: 16px">კითხვის თემატიკა</label><br>
								<input type="text" name="subject2" id="subject2" placeholder="კითხვის თემატიკა" list="subjects" value="{{ $question->question_subject }}">
								<datalist id="subjects">
									@foreach ($subjects as $s)
										<option value="{{$s->subject_name}}"></option>
									@endforeach
								</datalist>
							</td>
						</tr>
						<tr>
							<br><label style="margin-left: 16px">სავარაუდო პასუხები</label><br>
							@foreach(json_decode(json_encode($question->answers)) as $index => $answers)
								<input type="text" placeholder="პასუხი {{ $index }}" name="answers[]" value="{{ $answers }}"/>
							@endforeach
						</tr>
						<tr>
							<br><label style="margin-left: 16px">სწორი პასუხები</label><br>
							@foreach(json_decode(json_encode($question->corrects)) as $index => $corrects)
								<input type="text" placeholder="სწორი პასუხი {{ $index }}" name="corrects[]" value="{{ $corrects }}"/>
							@endforeach
						</tr>
						<tr>
							<td>
								<button type="submit" name="edit_free" class="p">რედაქტირება</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<!-- ასარჩევი კითხვის რედაქტირების ბლოკის დასასრული -->
		@endif
		<img src="{{ asset("images/chevron-left.png") }}" style="width: 12px;height:12px">&nbsp;<span><strong id="back" style="cursor: pointer">უკან</strong></span>
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
						<input type="text" placeholder="შეიყვანეთ თემის დასახელება" name="subject_name" id="subject_name">
					</td>
				</tr>
				<tr>
					<td>
						<button type="submit" name="add_subj">რედაქტირება</button><span id="statuss"></span>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
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
			<tbody>
				@foreach($history as $h)
					<tr>
						<td> {{ $h->author }} </td>
						<td> {{ $h->created_at }} </td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript" language="javascript">
	document.title = "კითხვარის რედაქტირება";
	
	$(document).ready(function() {
		$(".closemodal1").click(function() {
			$(this).parent().fadeOut("fast");
		});

		$("#open_history").click(function() {
			$(".history-modal").fadeIn("fast");
		});
	});
</script>

@include("layouts.sidebar")