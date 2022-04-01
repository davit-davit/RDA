@include("layouts.header")

<script type="text/javascript">
	document.title = "ტესტირების შედეგები";
</script>

<div class="main-block-add">
	<div class="table-block">
		<table>
			<thead>
				<tr>
					<th>ტესტის დასახელება</th>
					<th>შედეგის ნახვა</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $td)
					<tr>
						<td>{{ $td->test_subject }}</td>
						<td style="display: flex;justify-content: center;align-center:center;">
							<a href="/pdfresult/{{ auth()->user()->id }}/{{ $td->test_subject }}" class="download-btn" style="padding: 4px;margin: 4px" target="_blank">
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
