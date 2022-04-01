<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>კითხვები</title>
		<meta charset="utf-8">
		<style type="text/css">
			* {
				font-family: DejaVu Sans;
				font-size: 12px;
			}

			img {
				position: absolute;
				top: 0;
				right: 0;
				margin-top: 10px;
				margin-right: 20px;
			}
		</style>
	</head>
	<body>
		<header>
			<p><strong>გამოკითხვის დასახელება:</strong> {{ $header_data->test_subject }}</p>
			<p><strong>ID:</strong> {{ $header_data->user_id}}</p>
			<img src="https://rda.gov.ge/style/img/rda-ge.png" style="width: 200px;height: 60px;margin-top: -12px">
		</header>
		<hr>
		<table border="1">
			<thead>
				<tr>
					<th>კითხვა</th>
					<th>პასუხი</th>
					<th>ქულა</th>
				</tr>
			</thead>
			<tbody>
				@foreach($uresult as $res)
					<tr>
						<td>{!! str_replace("_", " ", $res->question) !!}</td>
						<td>{{ str_replace("_", " ", $res->answer) }}</td>
						<td class="sc">{{ str_replace("_", " ", $res->score) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<?php
			$finalscore = 0;
			$testscore = 0;
			foreach($uresult as $sc) {
				$finalscore += $sc->score;
			}

			foreach($uresult as $sc) {
				$testscore += $sc->answer_score;
			}
		?>
		<p id="finalscore">საბოლოო ქულა:&nbsp;&nbsp;{{ $finalscore }}&nbsp;/&nbsp;{{ $testscore }}-დან</p>
	</body>
</html>