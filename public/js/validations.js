$(document).ready(function() {
	/*კონტექსტური მენიუს გამორთვის კოდი*/
	document.onkeydown = function(event) {
		if(event.keyCode == 123) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'C'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) return false;
	}
	// მობილურით შესვლის შეზღუდვის კოდი 
	$(window).on("load", function() {
		var platform = ["Android", "iOS"];
		for (var i = 0; i < platform.length; i++) {
			if (navigator.platform.indexOf(platform[i]) > -1 || $(window).width() < 800) {
				document.writeln("<center>სისტემის მობილურით მოხმარება შეზღუდულია</center>");
				break;
			}
		}
	});
	// ამ კოდის საშუალებით ხდება შეცდომის შეტყობინების გატიშვა
	$(".danger-alert").find("a").click(function() {
		$(".danger-alert").fadeOut("fast");
	});
	// სისტემაში შესვლის ფორმის ვალიდაცია
	$("#login_form").on("submit", function() {
		var email = $("#email").val(); //ლოგინის ფორმაზე არსებული იმეილის ველის მნიშვნელობა
		var password = $("#password").val(); //ლოგინის ფორმაზე არსებული პაროლის ველის მნიშვნელობა

		if(email.length == 0 || email == null || email == "") {
			$(".danger-alert").find("p").find("strong").text("შეიყვანეთ იმეილი");
			$(".danger-alert").fadeIn("fast");
			setTimeout(() => {
				$(".danger-alert").fadeOut("fast");
			}, 3000);
			return false;
		}else if(password.length == 0 || password == null || password == "") {
			$(".danger-alert").fadeIn("fast");
			$(".danger-alert").find("p").find("strong").text("შეიყვანეთ პაროლი");
			setTimeout(() => {
				$(".danger-alert").fadeOut("fast");
			}, 3000);
			return false;
		}else if(password.length < 4) {
			$(".danger-alert").fadeIn("fast");
			$(".danger-alert").find("p").find("strong").text("პაროლი უნდა შედგებოდეს მინიმუმ 4 სიმბოლოსგან");
			setTimeout(() => {
				$(".danger-alert").fadeOut("fast");
			}, 3000);
			return false;
		}else return true;
	});
	//პაროლის აღდგენისას იმეილის ფორმის ვალიდაცია
	$("#reset_form").submit(function() {
		var email = $("#email").val();
		if(email.length == 0 || email == null || email == "") {
			$(".danger-alert").find("p").find("strong").text("შეიყვანეთ იმეილი");
			$(".danger-alert").fadeIn("fast");
			setTimeout(() => {
				$(".danger-alert").fadeOut("fast");
			}, 3000);
			return false;
		}else return true;
	});
});