$(document).ready(function() {
	// ნოთიფიკაციების ღილაკზე დაკლიკებისას ავტომატურად წამოვა მონაცემთა ბაზიდან შეტყობინებები
	$("#seen").click(function() {
		$.ajax({
			method : "GET",
			url : "/load_notify",
			success : function(data) {
				$("#notif").html(data);
			}
		});
	});

	// მოცემული ფუნქცია ითვლის თუ რამდენი შეტყობინება აქვს მომხმარებელს მოსული
	setInterval(function() {
		$.ajax({
			method : "GET",
			url : "/count_notify",
			success : function(data) {
				if(data > 0) {
					$("#count_notif").text(data).css("display", "block");
				}else if(data == 0) $("#count_notif").css("display", "none");
			}
		});
	}, 1000);
});