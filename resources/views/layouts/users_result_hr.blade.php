<?php
	$finalscore = 0 + $answers_sum; // ამ ცვლადში ინახება საბოლოო ქულას დამატებული ღია კითხვების ჯამური ქულა
?>
<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>ტესტის შედეგი</title>
		<meta charset="utf-8">
		<style type="text/css">
			* {
				font-family: DejaVu Sans, sans-serif;
				font-size: 12.5px;
			}

			img {
				position: absolute;
				top: 0;
				right: 0;
				margin-right: 20px;
			}
		</style>
	</head>
	<body>
		@php
			header("Content-type: text/html; charset=utf-8")	
		@endphp
		<header>
			<table>
				<tr>
					<td>
						<strong>ტესტის დასახელება:</strong> {{ $header_data->test_subject }}
					</td>
				</tr>
				<tr>
					<td>
						<strong>აპლიკანტი:</strong> {{ $header_data->user_name . " " . $header_data->user_lastname}}
					</td>
				</tr>
			</table>
			<img src="https://rda.gov.ge/style/img/rda-ge.png" style="width: 200px;height: 60px;margin-top: -12px">
		</header>
		<hr>
		<table border="1">
			<thead>
				<tr>
					<th>კითხვა</th>
					<th>პასუხი (გასაცემი)</th>
					<th>გაცემული პასუხი</th>
				</tr>
			</thead>
			<tbody>
				@foreach($uresult as $res)
					@if($res->type == "free" || $res->type == "single")
						<tr>
							<td>{!! str_replace("_", " ", $res->question) !!}</td>
							<td>{{ str_replace("_", " ", $res->correct) }}</td>
							<td>{{ str_replace("_", " ", $res->answer) }}</td>
						</tr>
					@endif
					@if($res->type == "multiple")
						<tr>
							<td>{!! str_replace("_", " ", $res->question) !!}</td>
							<td>
								@foreach(json_decode($res->corrects) as $corrects)
									{{ $corrects . "," }}
								@endforeach
							</td>
							<td>
								@foreach(json_decode($res->answers) as $answers)
									{{ $answers . "," }}
								@endforeach
							</td>
						</tr>
						@if(json_decode($res->corrects) == json_decode($res->answers))
							{{ $finalscore += $res->score  }}
						@else
							{{ $finalscore += $res->wrong_score  }}
						@endif
					@endif
				@endforeach
			</tbody>
		</table>
		<?php
			$testscore = 0; // ამ ცვლადში ინახება ტესტირების მაქსიმალური ქულა
			// გამოითვლება საბოლოო ქულა
			foreach($uresult as $sc) {
				// აქ ხდება პასუხების გადამოწმება
				// თუ გაცემული პასუხი დაემთხვა კითხვის სწორ პასუხს მაშინ დაემატება შესაფასებელი ქულა
				// სწორი და არასწორი პასუხის შემთხვევაში
				
				// დახურული კითხვის შემთხვევაში თუ პასუხი ემთხვევა სწორ პასუხს, საბოლოო გამოსათვლელ ქულას დაემატება სწორი პასუხის ქულა
				if($sc->type == "single" && $sc->correct == $sc->answer) $finalscore += $sc->score; 
				// დახურული კითხვის შემთხვევაში თუ პასუხი არ ემთხვევა სწორ პასუხს, საბოლოო გამოსათვლელ ქულას დაემატება არასწორი პასუხის ქულა
				else if($sc->type == "single" && $sc->correct != $sc->answer) $finalscore += $sc->wrong_score;
				// თუ არცერთი პირობა დაკმაყოფილდა ანუ პასუხი არ გასცა აპლიკანტმა,  საბოლოო გამოსათვლელ ქულას დაემატება 0 ქულა
				else if($sc->type == "single" && $sc->answer == "") $finalscore += 0;
			}
			// გამოითვლება ტესტირების მაქსიმალური ქულა
			foreach($uresult as $sc) {
				$testscore += $sc->score;
			}
		?>
		<p id="finalscore">საბოლოო ქულა:&nbsp;&nbsp;{{ $finalscore }}&nbsp;/&nbsp;{{ $testscore }}-დან</p>
	</body>
</html>