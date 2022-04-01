<table>
    <thead>
        <tr>
            <th>აპლიკანტი</th>
            <th>ტესტის დასახელება</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $result)
            <tr>
                <td>{{ $result->name . " " . $result->lastname }}</td>
                <td>{{ $result->test_subject }}</td>
                <td style="display: flex;justify-content: center;align-center:center;">
                    <a href="/check_test/{{ $result->id }}/{{ $result->test_subject }}" class="info-btn" style="padding: 4px; margin: 4px">
                        <img src="{{ asset('images/icons/check.png') }}">
                    </a>
                    <a href="/pdfresulthr/{{ $result->id }}/{{ $result->test_subject }}" class="download-btn" style="padding: 4px;margin: 4px" target="_blank">
                        <img src="{{ asset('images/icons/pdf.png') }}" style="width: 16px;height:16px">
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>{{ $users->links("pagination::bootstrap-4") }}</td>
        </tr>
        <tr>
            <td>
                <img src="{{ asset("images/chevron-left.png") }}" style="width: 12px;height:12px">&nbsp;<span><strong id="back" style="cursor: pointer">უკან</strong></span><br>
            </td>
        </tr>
    </tfoot>
</table>