@forelse($search_result as $datas)
	<div class="user-card">
		<a href="javascript:void(0)" class="dotmenu"><img src="{{ asset('images/icons/menu-dots.png') }}"></a>
    	<div>
			<div class="menu">
				<li>
					<a href="javascript:void(0)" id="edit" title="{{ $datas->id }}"><img src="{{ asset('images/icons/edit.png') }}" style="width:20px;height:20px">&nbsp;რედაქტირება</a>
				</li>
				<li>
					<a href="javascript:void(0)" title="{{ $datas->id }}" id="delete"><img src="{{ asset('images/icons/trash.png') }}" style="width:20px;height:20px">&nbsp;წაშლა</a>
				</li>
			</div>
			<center>
				<img src="{{ asset($datas->avatar) }}" class="avatar">
				@if($datas->active_status == "active")
					<div class="active-badge"></div>
				@else
					<div class="inactive-badge"></div>
				@endif
				<br>
				<p>{{ $datas->name . " " . $datas->lastname }}</p>
				<p style="font-family: Verdana, Geneva, Tahoma, sans-serif !important;font-size:12px !important"><img src="{{ asset('images/icons/mail.png') }}"> {{ $datas->email }}</p>
				<p style="font-family: Verdana, Geneva, Tahoma, sans-serif !important;font-size:12px !important"><img src="{{ asset('images/icons/mobile.png') }}"> {{ $datas->phone }}</p>
			</center>
		</div>
	</div>
	@empty($search_result as $datas)
		<p>ასეთი მომხმარებელი არ არსებობს</p>
	@endempty
@endforelse