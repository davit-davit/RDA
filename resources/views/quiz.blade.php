@include("layouts.header")

<script type="text/javascript">
	document.title = "გამოკითხვის შედეგები";
</script>

@if(auth()->user()->role == "employee")
	@php
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<div class="main-block-add">
	<div class="table-block">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>გამოკითხვის დასახელება</th>
					<th>შედეგი PDF-ში</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $result)
					<tr>
						<td>{{ $result->id }}</td>
						<td>{{ $result->test_subject }}</td>
						<td style="display: flex;justify-content: center;align-center:center;">
							<a href="/quizresult/{{ $result->id }}/{{ $result->test_subject }}" class="download-btn" style="padding: 4px;margin: 4px" target="_blank">
								<img src="{{ asset('images/icons/pdf.png') }}" style="width: 16px;height:16px">
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@include("layouts.sidebar")