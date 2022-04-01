@include("layouts.header")

<script type="text/javascript" language="javascript">
	document.title = "ტესტირება";
</script>

<div class="main-block-add">
	@if(Request::method() == "GET")
		<div class="test-info-block">
			<table>
				<tr>
					<td>
						<strong>დასახელება:</strong>&nbsp;&nbsp;<span>{{ $test_info->test_subject }}</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong>ჩატარების თარიღი:</strong>&nbsp;&nbsp;<span>{{ $test_info->test_date }}</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong>დასაწყისი:</strong>&nbsp;&nbsp;<span>{{ $test_info->test_start_time }} სთ</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong>ხანგრძლივობა:</strong>&nbsp;&nbsp;<span>{{ $test_info->test_duration }} წუთი</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong>დასრულების დრო:</strong>&nbsp;&nbsp;<span id="endtime"></span>
					</td>
				</tr>
				<tr>
					<td>
						<form method="post" action="">
							{{ csrf_field() }}
							<input type="submit" value="დაწყება" class="primary-btn" id="st">
						</form>
					</td>
				</tr>
			</table>
		</div>

		<script type="text/javascript" language="javascript">
			let btnstart = document.getElementById("st"); // ტესტის დაწყების ღილაკი
			let date = new Date();

			let current_time = date.getHours() + ":" + ((date.getMinutes() < 10) ? ("0" + date.getMinutes()) : date.getMinutes()); // მიმდინარე დრო

			let test_start_time = "{{ $test_info->test_start_time }}"; //ტესტის დაწყების დრო
			let test_start_date = "{{ $test_info->test_date }}"; // ტესტის ჩატარების თარიღი

			let current_year = date.getFullYear(); // მიმდინარე წელი
			let current_day = date.getDate(); // მიმდინარე დღე
			let current_month = ((date.getMonth() + 1) < 10) ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1); // გამოითვლება მიმდინარე თვე 0-იანი ფორმატით

			let current_full_date = current_year + "-" + current_month + "-" + current_day; // მიმდინარე სრული თარიღი
			
			const m = moment("{{ $test_info->test_start_time }}", "H:m");
			let duration = "{{ $test_info->test_duration }}"; // ტესტის ხანგრძლივობა
			let d = m.add(duration, "minutes").format("H:mm"); // ტესტის დაწყების დროს დაემატება ხანგრძლივობა და გამოითვლება დასრულების დრო
			document.getElementById("endtime").innerHTML = d + " სთ"; // ტესტის დასრულების დროის გამოტანა ბლოკში

			if(current_full_date > test_start_date || current_full_date < test_start_date) {
				btnstart.style.display = "none";
			}

			if(current_time < test_start_time || current_time >= d) {
				/* თუ მიმდინარე დრო ნაკლებია ტესტის დაწყების დროზე ან მიმდინარე დრო მეტია ან ტოლი დასრულების დროზე, მაშინ ტესტის დაწყების ღილაკი იქნება დამალული, წინააღმდეგ შემთხვევაში იქნება ხილული */
				btnstart.style.display = "none";
			}else {
				btnstart.style.display = "block";
			}
		</script>

		@php
			if(isset($submited->user_id) && isset($submited->test_subject) && $submited->user_id == auth()->user()->id && $submited->test_subject == $test_subject) {
				header("Location: " . URL::to("/"), true, 302);
				exit();
			}
		@endphp
	@else
		@php
			if(isset($submited->user_id) && isset($submited->test_subject) && $submited->user_id == auth()->user()->id && $submited->test_subject == $test_subject) {
				header("Location: " . URL::to("/"), true, 302);
				exit();
			}
		@endphp
		<div class="timer" style="width: 200px;padding: 15px;background-color: #fff;box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;position:fixed;top: 0;right: 0;display: flex;justify-content: center;align-content: center;margin-top: 80px;overflow: hidden;border-top-left-radius: 10px;border-bottom-left-radius: 10px;">
			<p><img src="{{ asset('images/icons/timer.png') }}">&nbsp;&nbsp;<b>დარჩა:&nbsp;<span id="dur">{{ $test_duration->test_duration }}</span>&nbsp;წთ</b></p>
		</div>
		<div class="testing-block" style="margin-top: 30px !important;width: 60% !important">
			<form method="post" action="/answer/{{ $test_subject }}" id="testing_form">
				{{ csrf_field() }}
				@foreach($q as $question)
					@if($question->type == "free")
						<table>
							<tr>
								<td>
									<strong><p>{!! $question->question !!}</p></strong>
								</td>
								<td>
									<img src="{{ asset('images/icons/checkk.png') }}"  style="margin-left: -30px !important;cursor:pointer" class="mark_question" data-counter="1">
								</td>
							</tr>
							<tr>
								<td>
									<textarea name="{!! $question->question !!}[{{ $question->id }}]" id="{!! $question->question !!}" data-q="{!! $question->question !!}" value=" " placeholder="ჩაწერეთ პასუხი" oninvalid="alert('უპასუხოდ დარჩენილია კითხვა: ' + this.getAttribute('data-q').replace(/(<([^>]+)>)/gi, ''));" required></textarea>
								</td>
							</tr>
						</table><hr>
					@elseif($question->type == "single")
						<table>
							<tr>
								<td>
									<strong><p>{!! $question->question !!}</p></strong>
								</td>
								<td>
									<img src="{{ asset('images/icons/checkk.png') }}"  style="position: absolute;right: 0;margin-right: 260px;margin-left: 30px !important;cursor:pointer" class="mark_question" data-counter="1">
								</td>
							</tr>
							<tr>
								<td>
									<label class="question-label" style="padding: 5px;border-radius: 4px;">
										<input type="radio" name="{!! $question->question !!}[{{ $question->id}}]" id="{!! $question->question !!}" value="{{ $question->A}}" required>&nbsp;&nbsp;<strong>{{ $question->A }}</strong>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label class="question-label" style="padding: 5px;border-radius: 4px;">
										<input type="radio" name="{!! $question->question !!}[{{ $question->id}}]" id="{!! $question->question !!}" value="{{ $question->B}}">&nbsp;&nbsp;<strong>{{ $question->B }}</strong>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label class="question-label" style="padding: 5px;border-radius: 4px;">
										<input type="radio" name="{!! $question->question !!}[{{ $question->id}}]" id="{!! $question->question !!}" value="{{ $question->C}}">&nbsp;&nbsp;<strong>{{ $question->C }}</strong>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label class="question-label" style="padding: 5px;border-radius: 4px;">
										<input type="radio" name="{!! $question->question !!}[{{ $question->id}}]" id="{!! $question->question !!}" value="{{ $question->D}}">&nbsp;&nbsp;<strong>{{ $question->D }}</strong>
									</label>
								</td>
							</tr>
						</table><hr>
					@elseif($question->type == "multiple")
						<table>
							<tr>
								<td>
									<strong><p>{!! $question->question !!}</p></strong>
								</td>
								<td>
									<img src="{{ asset('images/icons/checkk.png') }}"  style="position: absolute;right: 0;margin-right: 260px;margin-left: 30px !important;cursor:pointer" class="mark_question" data-counter="1">
								</td>
							</tr>
							@foreach($question->multi_answers as $answers)
								@php
									if(is_null($answers)) continue;
								@endphp
								<tr>
									<td>
										<label class="question-label1" style="padding: 5px;border-radius: 4px;">
											<input type="checkbox" name="{!! $question->question !!}[{{ $question->id}}][]" id="{!! $question->question !!}" value="{{ $answers }}">&nbsp;&nbsp;<strong>{{ $answers }}</strong>
										</label>
									</td>
								</tr>
							@endforeach
						</table><hr>
					@endif
				@endforeach
				<center>
					<input type="submit" name="submit_test" value="ტესტის დასრულება" class="primary-btn">
				</center>
			</form>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				var duration = "{{ $test_duration->test_duration }}"; // ტესტის ხანგრძლივობა
				var momen_t = moment(duration, "mm"); // შეიქმნება ხანგრძლივობის ობიექტი, რომელსაც შემდგომში გამოაკლდება წამები
				var left; // ამ ცვლადში შეინახება უკუთვლისას მიღებული დრო
				// ჩაირთობა ინტერვალი და ყოველ ერთ წამში გამოაკლებს ტესტის ხანგრძლივობას 1 წამს, ანუ მოხდება ტაიმერის უკუთვლა
				var count_down = setInterval(() => {
					left = momen_t.subtract(1, "seconds").format("mm:ss");
					$("#dur").html(left);
					if(left == "00:00") {
						clearInterval(count_down); // თუ დრო ამოუწურა ინტერვალი შეწყდება
						$("#testing_form").find("textarea").removeAttr("required"); // ფორმის შიგნით არსებული textarea-ს წაეშლება ატრიბუტი required, რის შემდეგაც ტექსტის შევსება სავალდებულო აღარ გახდება
						$("#testing_form").find(":radio").removeAttr("required"); // ფორმის შიგნით არსებული radio ღილაკს-ს წაეშლება ატრიბუტი required, რის შემდეგაც მისი მონიშვნა სავალდებულო აღარ გახდება
						$("#testing_form").submit(); // როცა დრო ამოიწურება ტესტირება ავტომატურად დადასტურდება
					}
				}, 1000);
				
				$("#testing_form").submit(function() {
					$('[name="submit_test"]').prop("disabled", true); //ტესტირების დადასტურებისას ღილაკი გახდება არააქტიური
				});
			});
		</script>
	@endif
</div>

@include("layouts.sidebar")