<!DOCTYPE html>
<html lang="ka-GE">
	<head>
		<title>კითხვები</title>
		<meta charset="utf-8">
		<style type="text/css">
			* {
				font-family: DejaVu Sans;
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
		<center>
			<p id="finalscore">საბოლოო ქულა:&nbsp;&nbsp;{{ $finalscore }}&nbsp;/&nbsp;{{ $testscore }}-დან</p>
		</center>
	</body>
</html>