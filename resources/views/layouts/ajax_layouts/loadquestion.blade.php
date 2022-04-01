<form method="post" action="/addtotest/{{ $ts }}/{{ $ws }}">
	{{ csrf_field() }}
	@foreach($questions as $q)
		<label style="display:flex;justify-content: center;align-content:center"><input type="checkbox" name="questions[]" value="{{ $q->question }}" checked>&nbsp;&nbsp;{!! $q->question !!}</label><br>
	@endforeach
	<hr>
		<center>
			<p>სულ&nbsp;<b>{{ $count_questions }}</b>&nbsp;კითხვა</p>
		</center>
	<hr>
	<button type="submit" name="addtotest">კითხვების დამატება ტესტში</button>
</form>

@if($count_questions != $quantity)
	<script type="text/javascript" language="javascript">
		window.alert("მითითებული რაოდენობის კითხვა ბაზაში არ არსებობს!");
	</script>
@endif