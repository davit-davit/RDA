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
				margin-top: 10px;
				margin-right: 20px;
			}
		</style>
	</head>
	<body>
		<header>
			<p><strong>ტესტის დასახელება:</strong> {!! $test_subject !!}</p>
			<p><strong>სულ კითხვა:</strong> {!! count($q) !!}</p>
			<img src="https://rda.gov.ge/style/img/rda-ge.png" style="width: 200px;height: 60px;margin-top: -12px">
		</header>
		<hr>
		<table>
			@foreach($q as $index => $data)
				<tr>
					<td>{{ $index + 1 }})</td>
					<td>{!! $data->question !!}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>