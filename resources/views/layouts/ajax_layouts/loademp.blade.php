<form method="post" action="">
	{{ csrf_field() }}
	<table style="margin-left: 20px">
		@foreach($employees as $emp)
			<tr>
				<td><input type="checkbox" name="emps[]" value="{{ $emp->id }}" checked></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $emp->name . " " . $emp->lastname }}</td>
			</tr>
		@endforeach
	</table>
	<button type="submit" name="assign_to_test">ტესტის გაგზავნა</button>
</form>