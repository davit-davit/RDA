<table>
	<thead>
		<tr>
			<th>ტესტის თემატიკა</th>
			<th>ჩატარების თარიღი</th>
			<th>დაწყება</th>
			<th>ხანგრძლივობა</th>
		</tr>
	</thead>
	<tbody id="tests">
		@foreach($tests as $test)
			<tr>
				<td>{{ $test->test_subject }}</td>
				<td>{{ $test->test_date }}</td>
				<td>{{ $test->test_start_time . " სთ" }}</td>
				<td>{{ $test->test_duration . " წთ" }}</td>
				<td style="display: flex;justify-content: center;align-center:center;">
					<a href="/maketest/{{ $test->test_subject }}/{{ $test->wrong_score }}" class="default-btn" style="padding: 4px;margin:4px">
						<img src="{{ asset('images/icons/useradd1.png') }}" style="width: 16px;height:16px">
					</a>
					<a href="/delete_test/{{ $test->id }}" class="danger-btn" style="padding: 4px;margin:4px">
						<img src="{{ asset('images/icons/white-trash.png') }}">
					</a>
					<a href="/get_pdf_or_excel/pdf/{{ $test->test_subject }}" class="download-btn" style="padding: 4px;margin:4px" target="_blank">
						<img src="{{ asset('images/icons/pdf.png') }}" style="width: 16px;height:16px">
					</a>
				</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td>{{ $tests->links("pagination::bootstrap-4") }}</td>
		</tr>
	</tfoot>
</table>