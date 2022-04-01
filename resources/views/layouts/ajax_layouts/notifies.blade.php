@foreach($notifications as $index => $nt)
<ul class="notif" style="border-bottom: 1px solid lightgray;font-size: 12px;overflow:hidden;">
	<li style="display:flex;justify-content:center;align:items:center">
		<div style="width:40px">
			<img src="{{ asset($nt->author_avatar) }}" style="width: 40px;height:40px;">
		</div>
		<div style="margin-left: 10px">
			<a href="{{ $nt->test_subject_for_link }}" title="{{ $nt->content }} თემაზე: {{ $nt->test_subject_for_link }}"><span>{{ $nt->content }}</i></span></a>
		</div>
	</li>
</ul>
@endforeach