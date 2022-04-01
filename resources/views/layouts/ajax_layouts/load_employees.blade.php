@foreach($users as $datas)
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
@endforeach
<div style="margin-left: 70%;">{{ $users->links('pagination::bootstrap-4') }}</div>

<script type="text/javascript">
	$(".dotmenu").click(function() {
		$(this).parent().find(".menu").fadeToggle("fast");
	});

	$(".dotmenu").click(function() {
		$(this).parent().find("#delete").click(function() {
			$(".modal-action").fadeIn("fast");
			$(".modal-action").find(".agree").attr("href", "/delete_emp/" + $(this).attr("title"));
		});
		// მომხმარებელი როდესაც დააჭერს დარედაქტირების ღილაკს მენიუში
		// გამოვა ფანჯარა რომელშიც ავტომატურად იქნება შევსებული ველები კონკრეტული მომხმარებლის
		// ინფორმაციაზე და ადგილიდანვე შეეძლება მისი ინფოს დარედაქტირება
		$(this).parent().find("#edit").click(function() {
			$(".edit-modal").fadeIn("fast"); // გაიხსნება დასარედაქტირებელი ფანჯარა
			let userid = $(this).attr("title"); // ცვლადში იწერება იმ მომხმარებლის აიდი, რომლის 
			//დარედაქტირებას ხდება
			// გაიგზავნება გეთ მოთხოვნა მონაცემების მისაღებად
			$.get("/edit_emp/" + userid, function(response) {
				$("#firstname1").val(response.name); // სახელი
				$("#lastname1").val(response.lastname); // გვარი
				$("#email1").val(response.email); // მაილი
				$("#pid1").val(response.pid); // პირადი ნომერი
				$("#phone1").val(response.phone); // ტელეფონი
				$("#department1").val(response.department); // დეპარტამენტი
				$("#service1").prepend("<option value\"" + response.service + "\" selected>" + response.service + "</option>"); // სამსახური
				$("#position1").prepend("<option value\"" + response.position + "\" selected>" + response.position + "</option>"); // პოზიცია
				$("#role1").val(response.role); // როლი
				$("#id").val(userid); // აიდი
			});
		});
	});
</script>