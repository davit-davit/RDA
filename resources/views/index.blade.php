@include("layouts.header")

<div class="main-block">
	@if($t == 1)
		<script type="text/javascript" language="javascript">window.alert("ტესტი შესრულებულია");</script>
	@endif
	<div class="profile-block">
		<div class="left-side">
			<a href="/edit_profile" class="settings" title="პარამეტრები">
				<img src="{{ asset('images/icons/gear.png') }}" style="width: 24px;height: 24px">
			</a>
			<center>
				@foreach($data as $dt)
					<div class="image-block">
						<p>სურათის ცვლილება</p>
						<img src="{{ asset($dt->avatar) }}" style="height: 120px; width: 120px;border-radius:100%;border:2px solid lightgray" id="avat">
						<form method="POST" action="/change_avatar" id="phone_change_form" enctype="multipart/form-data">
							@csrf
							<input type="file" style="display: none" name="image" id="image" accept="images/*">
						</form>
						<div class="photo">
							<img src="{{ asset('images/icons/camera.png') }}">
						</div>
					</div>
				@endforeach
				<hr>
				@foreach($data as $datas)
					<h3 style="font-size: 1.5em">{{ $datas->name . " " . $datas->lastname }}</h3>
					<hr>
					<p><strong>დეპარტამენტი:&nbsp;&nbsp;</strong>{{ $datas->department }}</p>
					<p><strong>სამსახური:&nbsp;&nbsp;</strong>{{ $datas->service }}</p>
					<p><strong>პოზიცია:&nbsp;&nbsp;</strong>{{ $datas->position }}</p>
					<p style="font-family: verdana !important"><strong>იმეილი:&nbsp;&nbsp;</strong>{{ $datas->email }}</p>
					<p id="nm" style="display:flex;justify-content:center;align-content: center;">
						<span class="toolt" style="margin-top: 422px !important;margin-left: 1px;">დააკლიკეთ ნომერზე მის შესაცლელად!</span>
						<strong style="margin-top: 8px;">ტელეფონი:&nbsp;&nbsp;</strong>
						<span id="phone_changeable" _token="<?php echo csrf_token(); ?>" contenteditable="true" style="font-family: verdana !important;margin-top: 8px;">
							{{ $datas->phone }}
						</span>&nbsp;&nbsp;
						<button type="button" id="change_phone" class="info-btn">
							<img src="{{ asset('images/icons/edit.png') }}" style="width: 16px;height:16px">
						</button>
					</p>
					<p style="font-family: verdana !important"><strong>პ/ნ:&nbsp;&nbsp;</strong>{{ $datas->pid }}</p>
				@endforeach
			</center>
		</div>
	</div>
</div>

@include("layouts.sidebar")