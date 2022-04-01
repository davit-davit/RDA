@foreach($history as $h)
	<tr>
		<td>{{ $h->author }} </td>
		<td>{{ $h->created_at }} </td>
	</tr>
@endforeach