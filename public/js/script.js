/**
 * @author დავით ჭეჭელაშვილი
 * @version 0.4.5
 * @name script.js
 */

console.log("%cთუ თქვენ ამას კითხულობთ, %cდატოვეთ აქაურობა!", "font-size: 40px;font-weight: bold;color:red;", "font-size: 30px;font-weight: bold;color: palegreen;")

$(document).ready(function() {
	var editor_value;
	// ედიტორი დახურული კითხვისთვის
	ClassicEditor.create(document.getElementById("question")).then( editor => {
		editor_value = editor;
	}).catch( error => {
		console.error( error );
	});
	// ედიტორი ღია კითხვისთვის
	ClassicEditor.create(document.getElementById("question1")).then( editor => {
		editor_value = editor;
	}).catch( error => {
		console.error( error );
	});
	// ედიტორი ასარჩევი კითხვისთვის
	ClassicEditor.create(document.getElementById("question2")).then( editor => {
		editor_value = editor;
	}).catch( error => {
		console.error( error );
	});
	// მოცემული კოდი ზღუდავს სქრინის გადაღებას
	$(document).keyup(function(event) {
		if (event.key == 'PrintScreen') {
        	navigator.clipboard.writeText('');
        	window.alert("სქრინის გადაღება შეზღუდულია!");
    	}
	});
	/*კონტექსტური მენიუს გამორთვის კოდი*/
	//$(document).on('contextmenu', function(e) {
	//	e.preventDefault();
	//});

	// მოცემული კოდი პასუხისმგებელია საიტზე inpect element და საიტის სორს კოდის ფუნქციის შეზღუდვაზე
	document.onkeydown = function(event) {
		if(event.keyCode == 123) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'C'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) return false;
		if(event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) return false;
	}
	/*კონტექსტური მენიუს გამორთვის კოდი*/

	// პაროლის ცვლილების ფუნქციონალი
	$("#chngpsw_form").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		var old_pass = $("#oldpassword").val(); // ველში ჩაწერილი ძველი პაროლის მნიშვნელობა
		var new_pass1 = $("#newpassword1").val(); // ველში ჩაწერილი ახალი პაროლის მნიშვნელობა
		var new_pass2 = $("#newpassword2").val(); // ველში ჩაწერილი გამეორებული პაროლის მნიშვნელობა

		var token = $('[name="_token"]').attr("value"); // უსაფრთხოების ტოკენი

		if(old_pass == null || old_pass == "") {
			// თუ ძველი პაროლის ველი არის ცარიელი შესრულდება შემდეგი კოდი
			$(".danger-alert").fadeIn("fast"); // გამოჩნდება გამაფრთხილებელი შეტყობინება 
			$(".danger-alert").find("p").find("strong").text("შეიყვანეთ ამჟამინდელი პაროლი"); // შეტყობინების ბლოკში ჩაიწერება მოცემული ტექსტი
			setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
		}else if(new_pass1 == "" || new_pass1 == null) {
			// თუ ძველი ახალი პაროლის ველი არის ცარიელი შესრულდება შემდეგი კოდი
			$(".danger-alert").fadeIn("fast"); // გამოჩნდება გამაფრთხილებელი შეტყობინება 
			$(".danger-alert").find("p").find("strong").text("შეიყვანეთ ახალი პაროლი"); // შეტყობინების ბლოკში ჩაიწერება მოცემული ტექსტი
			setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
		}else if(new_pass2 == "" || new_pass2 == null) {
			// თუ პაროლის გამეორების ველი არის ცარიელი, მაშინ შესრულდება შემდეგი კოდი
			$(".danger-alert").fadeIn("fast"); // გამოჩნდება გამაფრთხილებელი შეტყობინება
			$(".danger-alert").find("p").find("strong").text("გაიმეორეთ პაროლი"); // შეტყობინების ბლოკში ჩაიწერება მოცემული ტექსტი
			setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
		}else if(new_pass1 != new_pass2) {
			// თუ ძველი ახალი პაროლი არ ემთხვევა გამეორებულ პაროლს შესრულდება შემდეგი კოდი
			$(".danger-alert").fadeIn("fast"); // გამოჩნდება გამაფრთხილებელი შეტყობინება
			$(".danger-alert").find("p").find("strong").text("პაროლები არ ემთხვევა ერთმანეთს"); // შეტყობინების ბლოკში ჩაიწერება მოცემული ტექსტი
			setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
		}else if(new_pass1.length < 4 || new_pass2.length < 4) {
			// თუ "ახალი პაროლი"-ს ველში ჩაწერილი პაროლის ზომა არის 4 სიმბოლოზე ნაკლები ან "გაიმეორეთ პაროლი"-ს ველში ჩაწერილი მნიშვნელობა
			$(".danger-alert").fadeIn("fast"); // გამოჩნდება გამაფრთხილებელი შეტყობინება
			$(".danger-alert").find("p").find("strong").text("პაროლი უნდა შედგებოდეს მინიმუმ 4 სიმბოლოსგან"); // შეტყობინების ბლოკში ჩაიწერება მოცემული ტექსტი
			setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
		}else {
			/* თუ ყველა პირობა დაკმაყოფილდა მოხდება ajax მოთხოვნის შესრულება, მითითებულ ბმულზე მოხდება მონაცემების გაგზავნა, სადაც სერვერული მხარის დახმარებით მოხდება პაროლის შეცვლა */
			$.ajax({
				method : "POST", // მოტხოვნის ტიპი
				url : "/change_pass", // მოთხოვნის მისამართი
				// გაგზავნილი მონაცემები
				data : {
					oldpass : old_pass,
					newpass1 : new_pass1,
					newpass2 : new_pass2,
					_token : token
				},
				
				beforeSend : function() {
					/* ajax მოთხოვნის გაგზავნამდე მოხდება მოცემული კოდის შესრულება, რომელის დახმარებითაც შეცვლის ღილაკს დაედება გამორთული ღილაკის ეფექტი */
					$('[name="psw"]').html("დაელოდეთ&nbsp;&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				// თუ მოთხოვნის შემდგომ სერვერისაგან პასუხი წარმატებით დაბრუნდა, მაშინ მოხდება მოცემული კოდის შესრულება
				success : function(response) {
					if(response.status == 1) {
						$(".success-alert").fadeIn("fast").find("p").find("strong").text("პაროლი შეიცვალა"); // გამოჩდენა შეტყობინება
						setTimeout(function() { $(".success-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
						$('[name="psw"]').text("შეცვლა").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$("#chngpsw_form").find(":password").val(""); // მოხდება შევსებული ველების გასუფთავება
						$(".password_alert").fadeOut("fast"); // პაროლის სეცვლის გამაფრთხილებელი შეტყობინება გაქრება
					}else {
						$(".danger-alert").fadeIn("fast").find("p").find("strong").text("პაროლი ვერ შეიცვალა"); // გამოჩდენა შეტყობინება
						setTimeout(function() { $(".danger-alert").fadeOut("fast"); }, 2000); // გამოსული შეტყობინება გაქრება 3 წამში
						$('[name="psw"]').text("შეცვლა").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$("#chngpsw_form").find(":password").val(""); // მოხდება შევსებული ველების გასუფთავება
					}
				}
			});
		}
	});

	// ტელეფონის ნომრის ცვლილების ფუნქციონალი
	$("#change_phone").click(function() {
		let ph = $("#phone_changeable").text(); // ტელეფონის ნომრის ველის მნიშვნელობა
		let tok = $("#phone_changeable").attr("_token");
		
		$.ajax({
			method : "POST", // მოთხოვნის ტიპი
			url : "/change_phone", // მოთხოვნის მისამართი
			data : {
				phone : ph,
				_token : tok
			},
 
			beforeSend : function() {
				// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, რომლის დახმარებითაც ცვლილების ღილაკს დაედება გამორთული ღილაკის ეფექტი
				$("#change_phone").html("<img src='http://edu.rda.gov.ge/images/icons/wait.png' id='sp'>").prop("disabled", true);
			},

			// მოთხოვნის წარმატებით დასრულებისას, შესრულდება შემდეგი კოდი
			success : function() {
				// გაუქმებული ღილაკის ეფექტი აღარ იქნება
				$("#change_phone").html("<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>").prop("disabled", false);
				// ღილაკში 2 წამის შემდეგ ჩაჯდება თოლიას ნიშანი, რაც იქნება ცვლილების წარმატებით განხორციელების ნიშანიs
				setTimeout(() => {
					$("#change_phone").html("<img src='http://edu.rda.gov.ge/images/icons/edit.png' style='width: 16px;height:16px'>");
				}, 2000);
			}
		});
	});

	// ფოტოს ხატულაზე დაკლიკებისას ამოხტება ფანჯარა რითაც მოხდება სურათის არჩევა
	$(".photo").click(function() {
		$("#image").click();
	});

	/* ტესტის შედგენისას იუზერებისა და კითხვების პოპაპის დამხურავი მეთოდი*/
	$("#popup_close").click(function() {
		$(this).parent().fadeOut("fast");
		$(".blur-block").fadeOut("fast"); // მოხდება გამოჩეილი დაბლარული ბლოკის გაქრობა
	});
 
	$("#image").change(function() {
		// სურათის არჩევისას, ფორმა ავტომატურად დასაბმითდება და საბოლოოდ გამოჩნდება განახლებული ავატარი
		$("#phone_change_form").submit();
	});

	/* ნავიგაციის ჩამოშლის ფუნქციონალები */
	$(".sub").on("click", function() {
		$("#sub1").slideToggle(300);
	});
	$("#sub_2").click(function() {
		$("#sub2").slideToggle(300);
	});
	$("#sub_3").click(function() {
		$("#sub3").slideToggle(300);
	});
	/* ნავიგაციის ჩამოშლის ფუნქციონალები */

	$(".close_alert").click(function() {
		$(this).parent().fadeOut(500);
	});

	$(".closemodal1").click(function() {
		$(this).parent().fadeOut("fast");
	});

	$(".closemodal").click(function() {
		$(this).parent().parent().parent().fadeOut("fast");
	});

	$("#open_subjects, #open_subjects1").click(function() {
		$(".subject-modal").fadeIn("fast");
	});

	$("#subj_add").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან
		$subject_value = $("#subject_name").val(); // თემატიკის ველის მნიშვნელობა
		$token = $("#tok_en").val();

		if($subject_value.length < 1 || $subject_value == null || $subject_value == "") {
			// თუ თემატიკის ველში არ იქნება ჩაწერილი მნიშვნელობა გამოვა შესაბამისი სეტყობინება
			window.alert("შეიყვანეთ თემა");
		}else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/subject_add", // მოთხოვნის მისამართი
				data : {
					subject_value : $subject_value, // თემატიკის მნიშვნელობა
					_token : $token
				},

				beforeSend : function() {
					$('[name="add_subj"]').text("დაელოდეთ...").prop("disabled", true); // ღილაკს დაედება გამორთული ღილაკის ეფექტი
				},

				success : function(data) {
					// მოთხოვნის წარმატებით დასრულებისას შესრულდება მოცემული კოდი
					if(data == "1") {
						window.alert("დაემატა"); // თუ სერვერი დააბრუნებს მნიშვნელობა 1-ს გამოვა შესაბამისი შეტყობინება
						$('[name="add_subj"]').text("დამატება").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$("#subject_name").val(""); // გასუფთავდება ფორმა
					}
				}
			});
		}
	});

	var set_ans = document.getElementsByClassName("set_answer"); // სწორ პასუხად მონიშვნის ღილაკი
	var correct_ans = $("#correct"); // სწორი პასუხის ველი
	var checked = "";

	/* სწორი პასუხის მონიშვნის ღილაკებზე აღმნიშნელის დადების კოდი,რომელ ღილაკზეც დააკლიკავს მომხმარებელი, მხოლოდ იმ ღილაკს დაედება მონიშნულის ხატულა */
	for(var i = 0; i < set_ans.length; i++) {
		set_ans[i].onclick = function() {
			correct_ans.val(this.getAttribute("id"));
			this.innerHTML = "<img src='../images/icons/checked.png'>";
			checked = this.getAttribute("id");
			
			if(checked == "a") {
				// თუ a ვარიანტია იქნება მონიშნული, დანარჩენები ამ შემთხვევაში იქნება პირიქით
				set_ans[1].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[2].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[3].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
			}else if(checked == "b") {
				// თუ b ვარიანტია იქნება მონიშნული, დანარჩენები ამ შემთხვევაში იქნება პირიქით
				set_ans[0].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[2].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[3].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
			}else if(checked == "c") {
				// თუ c ვარიანტია იქნება მონიშნული, დანარჩენები ამ შემთხვევაში იქნება პირიქით
				set_ans[0].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[1].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[3].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
			}else if(checked == "d") {
				// თუ d ვარიანტია იქნება მონიშნული, დანარჩენები ამ შემთხვევაში იქნება პირიქით
				set_ans[0].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[1].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
				set_ans[2].innerHTML = "<img src='http://edu.rda.gov.ge/images/icons/checkk.png'>";
			}
		}
	}

	//ტესტისთვის კითხვების ჩვენების ფუნქციონალი შემდგომში ტესტისთვის მისანიჭებლად
	$("#load_question").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან
		$question_subject = $("#subject").val(); // თემატიკის მნიშვნელობა
		$question_quantity = $("#quantity").val(); // კითხვების რაოდენობის მნიშვნელობა
		$question_score = $("#score").val(); // ქლის მნიშვნელობა თუ რამდენად ფასდება კითხვა
		$type_question = $("#type_question").val(); // კითხვის ტიპი
		$tkn = $('[name="_token"]').val(); // უსაფრთხოების ტოკენი
		$ts = $("#ts").val(); // ტესტის თემატიკა, თუ რა ტესტს ენიჭება კითხვები

		if($question_score == null || $question_score == "" || $question_subject == null || $question_subject == "" || $question_quantity == null || $question_quantity == "") {
			window.alert("შეავსეთ ყველა ველი");
		}else {
			$.ajax({
				method : "POST",
				url : "/show_question",
				data : {
					subject : $question_subject,
					quantity : $question_quantity,
					score : $question_score,
					type : $type_question,
					test_subject : $ts,
					wrong_score : $("#ws").val(), // არასწორი პასუხის მნიშვნელობა თუ რამდენი ქულა დააკლდეს ტესტის კითხვას არასწორი პასუხის შემთხვევაში
					_token : $tkn
				},

				beforeSend : function() {
					$("#lq").text("იტვირთება...").prop("disabled", true); // ღილაკს დაედება გამორთული ღილაკის ეფექტი
				},

				success : function(data) {
					$("#qs").html(data); // გვერდით გამოსულ პანელზე ჩაიყრება შერჩეული კითხვები
					$("#lq").text("კითხვების ჩვენება").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
					$(".sidepanel").fadeIn("fast"); // გვერდით გამოჩნდება პანელი, სადაც იქნება ჩაყრილი კითხვები
				}
			});
		}
	});

	//თანამშრომლებისა ან კითხვების ყველა ჩეკბოქსის მოსანიშნი მეთოდი
	$("#checkall").click(function() {
		$("#qs").find(":checkbox").prop("checked", true);
	});

	// თანამშრომლების ჩატვირთვის მეთოდი ტესტებზე მიმაგრებისას
	$("#load_employees").on("submit", function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან
		$tkn1 = $('[name="_token"]').val(); // უსაფრთხოების ტოკენი
		$department = $("#depart_ment").val(); // მითითებული დეპარტამენტის მნიშვნელობ
		$service = $("#services").val(); // მითითებული სამსახურის მნიშვნელობა
		$position = $("#position").val(); // მითითებული პოზიციის მნიშვნელობა

		if($department == null || $department == "" || $service == null || $service == "" || $position == null || $position == "") {
			// თუ რომელიმე კრიტერიუმი არ იქნება არჩეული გამოვა შესაბამისი შეტყობინება
			window.alert("აირჩიეთ სამივე კრიტერიუმი");
		}else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/show_emps", // მოთხოვნის მისამართი
				data : {
					department : $department,
					service : $service,
					position : $position,
					_token : $tkn1
				},

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი
					$("#lemp").text("იტვირთება...").prop("disabled", true); // ღილაკს დაედება გამორთული ღილაკის ეფექტი
				},

				success : function(data) {
					$("#qs").html(data); // გვერდით გამოსულ პანელში ჩაიყრება თანამშრომლები, მითითებული პარამეტრების მიხედვით
					$("#lemp").text("თანამშრომლების ჩვენება").prop("disabled", false); // შილაკს გაუუქმდება გამორთული ღილაკის ეფექტი
					$(".sidepanel").fadeIn("fast"); // გამოჩნდება გვერდითა პანელი
				}
			});
		}
	});

	// შეტყობინებების მონიშვნა წაკითხულად
	$("#seen").click(function() {
		$.get("/seen/" + $("#logged_in_id").val(), function(response) {
			console.log("notification seen!");
		});
	});

	$("#cl").click(function() {
		$(this).parent().fadeOut(300);
	});

	//თანამშრომლის რედაქტირება და ვალიდაცია (ajax მეთოდის გამოყენებით)
	$("#edit_form").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$firstname = $("#firstname1").val(); // სახელის ველის მნიშვნელობა
		$lastname = $("#lastname1").val();// გვარის ველის მნიშვნელობა
		$email = $("#email1").val();// იმეილის ველის მნიშვნელობა
		$pid = $("#pid1").val();// პირადი ნომრის ველის მნიშვნელობა
		$phone = $("#phone1").val();// ტელეფონის ველის მნიშვნელობა
		$department = $("#department1").val();// დეპარტამენტის ველის მნიშვნელობა
		$service = $("#service1").val();// სამსახურის ველის მნიშვნელობა
		$position = $("#position1").val();// პოზიციის ველის მნიშვნელობა
		$role = $("#role1").val(); //როლის ველის მნიშვნელობა
		$uid = $("#id").val(); // დალოგინებული მომხმარებლის აიდი

		$token_edit = $('[name="_token"]').val(); // უსაფრთხოების ტოკენი

		if($firstname == null || $firstname == "") window.alert("შეიყვანეთ სახელი"); // თუ სახელი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else if($lastname == null || $lastname == "") window.alert("შეიყვანეთ გვარი"); // თუ გვარი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else if($email == null || $email == "") window.alert("შეიყვანეთ იმეილი"); // თუ იმეილი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else if($pid == null || $pid == "") window.alert("შეიყვანეთ პირადი ნომერი"); // თუ პირადი ნომერი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else if($phone == null || $phone == "") window.alert("შეიყვანეთ ტელეფონი"); // თუ ტელეფონი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else if($role == null || $role == "") window.alert("შეიყვანეთ როლი"); // თუ როლი იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/edit_emp/" + $uid, // მოთხოვნის მისამართი
				data : $("#edit_form").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$("#eemp").html("მუშავდება&nbsp;&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.edited == "1") {
						// თუ სერვერმა დააბრუნა რიცხვი 1 ანუ მონაცემები დარედაქტირდა, მაშინ შესრულდება მოცემული კოდი
						$(".success-alert").fadeIn("fast").find("p").find("strong").text("დარედაქტირდა"); // გამოვა შეტყობინება
						$("#eemp").html("თანამშრომლის რედაქტირება").prop("disabled", false); // ღილაკს გაუუქმდება გამორთული ღილაკის ეფექტი
						// მოცემული კოდის საშუალებით გამოსული შეტყობინება გამოირთობა 3 წამში
						setTimeout(function() {
							$(".success-alert").fadeOut("fast");
						}, 3000);
					}
					else if(data.edited == "0") window.alert("ვერ დარედაქტირდა"); // თუ მონაცემები ვერ დარედაქტირდა გამოვა შეტყობინება
				}
			});
		}
	});

	// ტესტის დამატების/შექმნის ვალიდაცია (ajax მეთოდის გამოყენებით)
	$("#createtest").on("submit", function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$subject = $("#subject").val(); // ტესტის თემატიკის ველის მნიშვნელობა
		$start_date = $("#test_date").val(); // ტესტის ჩატარების თარიღის მნიშვნელობა
		$start_time = $("#test_start_time").val(); // ტესტირების დაწყების დროის მნიშვნელობა
		$type = $("test_type").val(); // ტესტირების ტიპის მნიშვნელობა

		if($subject == "" || $subject == null) {
			// თუ თემატიკის მნიშვნელობა იქნება ცარიელი მაშინ გამოვა შეტყობინება
			$(".danger-alert").fadeIn("fast").find("p").find("strong").text("შეიყვანეთ თემატიკა");
			setTimeout(function() {
				// 3 წამის შემდეგ გაითიშება გამოსული შეტყობინება
				$(".danger-alert").fadeOut("fast");
			}, 3000);
		}else if($start_date == "" || $start_date == null) {
			// თუ ტესტის ჩატარების თარიღის მნიშვნელობა იქნება ცარიელი მაშინ გამოვა შეტყობინება
			$(".danger-alert").fadeIn("fast").find("p").find("strong").text("შეიყვანეთ ჩატარების თარიღი");
			setTimeout(function() {
				// 3 წამის შემდეგ გაითიშება გამოსული შეტყობინება
				$(".danger-alert").fadeOut("fast");
			}, 3000);
		}else if($start_time == "" || $start_time == null) {
			// თუ ტესტის დაწყების დროის მნიშვნელობა იქნება ცარიელი მაშინ გამოვა შეტყობინება
			$(".danger-alert").fadeIn("fast").find("p").find("strong").text("შეიყვანეთ დაწყების დრო");
			setTimeout(function() {
				// 3 წამის შემდეგ გაითიშება გამოსული შეტყობინება
				$(".danger-alert").fadeOut("fast");
			}, 3000);
		}else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/create_test", // მოთხოვნის მისამართი
				data : $("#createtest").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="create_test"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.status_test == 1) {
						// თუ სერვერმა დააბრუნა რიცხვი 1 ანუ ტესტი შეიქმნა, მაშინ შესრულდება მოცემული კოდი
						$('[name="create_test"]').text("შენახვა").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$(".success-alert").fadeIn("fast").find("p").find("strong").text("ტესტი შეიქმნა"); // გამოვა შეტყობინება
						setTimeout(function() {
							// გამოსული შეტყობინება გამოირთობა 3 წამში
							$(".success-alert").fadeOut("fast");
						}, 3000);
						// ქვემოთ მოცემული კდების საშუალებით მოხდება ფორმაში შეყვანილი ველების გასუფთავება
						$("#subject").val(""); $("#test_date").val(""); $("#test_start_time").val(""); $("test_type").val("");
					}else if(data.status_test = 0) window.alert("დაფიქსირდა შეცდომა");

					// ტესტების სექმნისას ავტომატურად ჩავარდება ბოლოს დამატებული ტესტები ახალ ტესტთან ერთად, ტესტების ცხრილში, რომელიც ქვემოთ იქნება მოცემული
					$.get("/showtests", function(data) {
						$("#alltest").html(data);
					});
				},
				// სერვერული შეცდომის შემთხვევაში შესრულდება ქვემოთ მოცემული კოდი, გამოვა შეტყობინება
				error : function() {
					window.alert("დაფიქსირდა შეცდომა!");
				}
			});
		}
	});

	// ასარჩევ პასუხებიანი კითხვის დამატების ვალიდაციის ფუნქცია, რომელსაც აქვს რამდენიმე სწორი პასუხი 
	$("#add_multiple_form").submit(function() {
		$subject = $("#subject2").val(); // კითხვის თემატიკის ველის მიშველობა
		$duration = $("#duration2").val(); // კითხვის ხანგრძლივობის ველის მიშველობა
		$question = $("#question2").val(); // კითხვის ველის მიშველობა
		$score = $("#score2").val(); // კითხვის ქულის/წონის ველის მიშველობა

		if($question == "" || $question == null) {
			window.alert("შეიყვანეთ კითხვა");
			return false;
		}else if($subject == "" || $subject == null) {
			window.alert("შეიყვანეთ თემატიკა");
			return false;
		}else if($duration == "" || $duration == null) {
			window.alert("შეიყვანეთ ხანგრძლივობა");
			return false;
		}else if($score == "" || $score == null) {
			window.alert("შეიყვანეთ ქულა");
			return false;
		}

		if($(".corr").length < 2) {
			window.alert("მონიშნეთ მინიმუმ 2 სწორი პასუხი!");
			return false;
		}
	});

	//ღია კითხვის დამატების ვალიდაცია (ajax მეთოდის გამოყენებით)
	$("#add_question_free").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$question = $("#question1").val(); // კითხვის ველის მნიშვნელობა
		$score = $("#score1").val(); // კითხვის ქულისწონის მნიშვნელობა
		$subject = $("#subject1").val(); // თემატიკის ველის მნიშვნელობა
		$duration = $("#duration1").val(); // კითხვის ხანგრძლივობის ველის მნიშვნელობა
		$correct_answer = $("#correct_answer_free").val(); // სწორი პასუხის ველის მნიშვნელობა

		if($question == "" || $question == null) window.alert("შეიყვანეთ კითხვა"); // თუ კითხვის ველის მნიშვნელობა ცარიელია, გამოვა შეტყობინება
		else if($correct_answer == null || $correct_answer == "") window.alert("შეიყვანეთ სწორი პასუხი"); // თუ კითხვის სწორი პასუხის ველის მნიშვნელობა ცარიელია, გამოვა შეტყობინება
		else if($subject == "" || $subject == null) window.alert("შეიყვანეთ თემატიკა"); // თუ კითხვის თემატიკის ველის მნიშვნელობა ცარიელია, გამოვა შეტყობინება
		else if($duration == "" || $duration == null) window.alert("შეიყვანეთ ხანგრძლივობა"); // თუ კითხვის ხანგრძლივობის მნიშვნელობა ცარიელია, გამოვა შეტყობინება
		else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/add_question1", // მოთხოვნის მისამართი
				data : $("#add_question_free").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="add_free"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.free == 1) {
						// თუ სერვერმა დააბრუნა რიცხვი 1 ანუ კითხვა დაემატა შესრულდება შემდეგი კოდები
						$('[name="add_free"]').text("დამატება").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$(".success-alert").find("p").find("strong").text("კითხვა დაემატა").fadeIn("fast"); // გამოვა შეტყობინება
						// მოხდება ფორმის გასუფთავება
						$("#subject1").val(""); $("#duration1").val(""); $("#correct_answer_free").val(""); $("#score1").val("");
						$(".ck-editor__editable").find('[data-placeholder="შეიყვანეთ კითხვა"]').text("");
						setTimeout(() => {
							// გამოსული შეტყობინება გამოირთობა 3 წამში
							$(".success-alert").fadeOut("fast");
						}, 3000);
					}else window.alert("კითხვა ვერ დაემატა");
					// როდესაც კითხვა დაემატება მის ქვემოთ ავტომატურად ჩავარდება ახალი დამატებული კითხვა
					// სადაც მოცემული იქნება ყველა დამატებული კითხვა, რომლებიც იქნებიან გვერდებად დაყოფილი
					$.get("/load_all_question", function(data1) {
						$("#all").html(data1);
					});
				},
				// იმ შემთხვევაში თუ პასუხის მიღებისას სერვერზე დაფიქსირდა შეცდომა, შესრულდება ქვემოთ მოცემული // კოდი და გამოვა შესაბამისი შეტყობინება
				error : function() {
					window.alert("დაფიქსირდა შეცდომა");
				}
			});
		}
	});

	// დახურული კითხვის ვალიდაცია (ajax მეთოდის გამოყენებითხ)
	$("#one_correct").submit(function(e) {
		e.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$question = $("#question").val(); // კითხვის ველი
		$a = $("#a").val(); // პასუხის ვარიანტი a
		$b = $("#b").val(); // პასუხის ვარიანტი b
		$c = $("#c").val(); // პასუხის ვარიანტი c
		$d = $("#d").val(); // პასუხის ვარიანტი d
		$corr_score = $("#score").val(); // სწორი პასუხის ქულის
		$duration = $("#duration").val(); // ხანგრძლივობის ველი
		$subject = $("#subject").val(); // თემატიკის ველი
		$correct_answer = $("#correct").val(); // სწორი პასუხის ველი

		var checks = document.getElementsByClassName("set_answer");

		if($question == "" || $question == null) window.alert("შეიყვანეთ კითხვა"); // თუ კითხვის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($a == "" || $a == null) window.alert("შეიყვანეთ ვარიანტი A"); // თუ "ა" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($b == "" || $b == null) window.alert("შეიყვანეთ ვარიანტი B"); // თუ "ბ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($c == "" || $c == null) window.alert("შეიყვანეთ ვარიანტი C"); // თუ "გ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($d == "" || $d == null) window.alert("შეიყვანეთ ვარიანტი D"); // თუ "დ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($duration == "" || $duration == null) window.alert("შეიყვანეთ კითხვის ხანგრძლივობა (წუთებში)"); // თუ ხანგრძლივობის ველი იქნება ცარიელი გამოვა შეტყობინება
		else if($subject == "" || $subject == null) window.alert("შეიყვანეთ თემატიკა"); // თუ თემატიკის ველი იქნება ცარიელი გამოვა შეტყობინება
		else if($correct_answer == "" || $correct_answer == null) window.alert("მონიშნეთ სწორი პასუხი"); // თუ სწორი პასუხი არ იქნება მონიშნული გამოვა შეტყობინება
		else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/add_question", // მოთხოვნის მისამართი
				data : $("#one_correct").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="add_single_choice"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.added == 1) {
						window.alert("დაემატა"); // თუ კითხვა დაემატა გამოვა შეტყობინება
						$('[name="add_single_choice"]').text("დამატება").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$("#a").val(""); $("#b").val(""); $("#c").val(""); $("#d").val(""); $("#duration").val(""); $("#subject").val(""); $("#correct").val("");$("#score").val("") // გასუფთავდება ფორმაში სეტანილი მონაცემები
						// ციკლის საშუალებით პასუხის მონიშვნის ღილაკებში ჩაამატებს check-ის ხატულას
						for(let i = 0; i < checks.length; i++)  {
							checks[i].innerHTML = "<img src=\"http://edu.rda.gov.ge/images/icons/checkk.png\">";
						}
						$(".ck-editor__editable").find('[data-placeholder="შეიყვანეთ კითხვა"]').text("");
					}else if(data.added == 0) window.alert("ვერ დაემატა");
					// როდესაც კითხვა დაემატება მის ქვემოთ ავტომატურად ჩავარდება ახალი დამატებული კითხვა
					// სადაც მოცემული იქნება ყველა დამატებული კითხვა, რომლებიც იქნებიან გვერდებად დაყოფილი
					$.get("/load_all_question", function(data1) {
						$("#all").html(data1);
					});
				}
			});
		}
	});

	//დახურული კითხვის რედაქტირების ფუნქციონალი ajax მეთოდით
	$("#edit_one_correct").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$question = $("#question").val(); // კითხვის ველი
		$a = $("#a").val(); // პასუხის ვარიანტი a
		$b = $("#b").val(); // პასუხის ვარიანტი b
		$c = $("#c").val(); // პასუხის ვარიანტი c
		$d = $("#d").val(); // პასუხის ვარიანტი d
		$corr_score = $("#score").val(); // სწორი პასუხის ქულის
		$duration = $("#duration").val(); // ხანგრძლივობის ველი
		$subject = $("#subject").val(); // თემატიკის ველი
		$correct_answer = $("#correct").val(); // სწორი პასუხის ველი

		if($question == "" || $question == null) window.alert("შეიყვანეთ კითხვა");// თუ კითხვის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($a == "" || $a == null) window.alert("შეიყვანეთ ვარიანტი (ა)"); // თუ "ა" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($b == "" || $b == null) window.alert("შეიყვანეთ ვარიანტი (ბ)"); // თუ "ბ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($c == "" || $c == null) window.alert("შეიყვანეთ ვარიანტი (გ)"); // თუ "გ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($d == "" || $d == null) window.alert("შეიყვანეთ ვარიანტი (დ)"); // თუ "დ" ვარიანტის ველი იქნება ცარიელი, გამოვა შეტყობინება
		else if($duration == "" || $duration == null) window.alert("შეიყვანეთ კითხვის ხანგრძლივობა (წუთებში)"); // თუ ხანგრძლივობის ველი იქნება ცარიელი გამოვა შეტყობინება
		else if($subject == "" || $subject == null) window.alert("შეიყვანეთ თემატიკა"); // თუ თემატიკის ველი იქნება ცარიელი გამოვა შეტყობინება
		else if($correct_answer == "" || $correct_answer == null) window.alert("მონიშნეთ სწორი პასუხი");// თუ სწორი პასუხი არ იქნება მონიშნული გამოვა შეტყობინება
		else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/edit_question/" + $("#qid").val(), // მოთხოვნის მისამართი, გადაცემული პარამეტრით, რომელიც არის კითხვის აიდი
				data : $("#edit_one_correct").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="edit_single_choice"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.updated == 1) {
						window.alert("დარედაქტირდა"); // თუ კითხვა დარედაქტირდა გამოვა შეტყობინება
						$('[name="edit_single_choice"]').text("რედაქტირება").prop("disabled", false); // გაუქმდება გამორთული ღილაკის ეფექტი
						$("#a").val("");$("#b").val("");$("#c").val("");$("#d").val("");$("#score").val("");$("#duration").val("");$("#subject").val("");$("#correct").val("");
						editor_value.setData('');
					}else if(data.updated == 0) window.alert("ვერ დარედაქტირდა"); // თუ ვერ დარედაქტირდა გამოვა შესაბამისი შეტყობინება
				},
				// თუ სერვერზე დაფიქსირდა შეცდომა გამოვა შესაბამისი შეტყობინება
				error : function() {
					window.alert("დაფიქსირდა შეცდომა!");
				}
			});
		}
	});

	//ღია კითხვის დარედაქტირების ფუნქციონალი ajax მეთოდით
	$("#edit_question_free").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან

		$question = $("#question1").val(); // კითხვის ველის მნიშვნელობა
		$score = $("#score1").val(); // ქულის ველის მნიშვნელობა
		$subject = $("#subject1").val(); // თემატიკის ველის მნიშვნელობა
		$duration = $("#duration1").val(); // ხანგრძლივობის ველის მნიშვნელობა
		$correct = $("#correct_answer_free").val(); // სწორი პასუხის ველის მნიშვნელობა

		if($question == "" || $question == null) window.alert("შეიყვანეთ კითხვა"); // თუ კითხვა იქნება ცარიელი, გამოვა შეტყობინება
		else if($correct == "" || $correct == null) window.alert("შეიყვანეთ სწორი პასუხი"); // თუ სწორი პასუხი იქნება ცარიელი, გამოვა შეტყობინება
		else if($subject == "" || $subject == null) window.alert("შეიყვანეთ თემატიკა"); // თუ თემატიკა იქნება ცარიელი, გამოვა შეტყობინება
		else if($duration == "" || $duration == null) window.alert("შეიყვანეთ ხანგრძლივობა"); // თუ ხანგრძლივობა ცარიელია გამოვა შეტყობინება
		else {
			$.ajax({
				method : "POST", // მოთხოვნის ტიპი
				url : "/edit_question1/" + $("#qid").val(), // მოთხოვნის მისამართი პარამეტრით
				data : $("#edit_question_free").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="edit_free"]').html("დაელოდეთ&nbsp;<img src='http://edu.rda.gov.ge/images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					if(data.edited == 1) {
						$('[name="edit_free"]').text("რედაქტირება").prop("disabled", false); // გაუქმდება გმორთული ღილაკის ეფექტი
						$(".success-alert").fadeIn("fast").find("p").find("strong").text("კითხვა დარედაქტირდა"); // გამოვა შეტყობინება
						// ველეფის გასუფთავება===========================
						$("#score1").val("");$("#subject1").val("");$("#duration1").val("");$("#correct_answer_free").val("");
						editor_value.setData('');
						// ველეფის გასუფთავება===========================
					}else window.alert("კითხვა ვერ დარედაქტირდა");
				},
				// თუ სერვერზე დაფიქსირდა შეცდომა, გამოვა შეტყობინება
				error : function() {
					window.alert("დაფიქსირდა შეცდომა");
				}
			});
		}
	});

	//dropdown- სქემის გააქტიურების მეთოდი, სადაც ხდება კონკრეტული დეპარტამენტის არცევის შემთხვევაში
	//შესაბამის მენიუში პოზიციებისა და სამსახურების დაჯგუფება
	$("#depart_ment, #department1, #department").change(function() {
		/* მოცემულ სელექტორებში ის სელექტორები, რომლებიც ბოლოვდებიან 1-ით, განკუთვნილია თანამშრომლის რედაქტირების ველებისთვის, ხოლო, რომლებიც არ ბოლოვდებიან 1-ით ისინი განკუთვნილნი არიან ფილტრაციისთვის */
		$("#service1, #services, #service").empty(); // სამსახურების დროფდაუნ ველების გასუფთავება
		$("#position_freelance1, #position_freelance").empty();
		$("#position_staff1, #position_staff").empty();

		$("#position_freelance1, #position_freelance").append("<option value=\"\"></option>");
		$("#service1, #service, #services").append("<option value=\"\"></option>");
		$("#position_staff1, #position_staff").append("<option value=\"\"></option>");

		/* მოცემული for  ციკლების საშუალებით მოხდება სასმახურებისა და პოზიციების ჩატვირთვა პროგრამაში, კონკრეტული ველში ცვლილების განხორციელებისას. მაგ: დეპარტამენტის არჩევისას პროგრამაში მოხდება ჩატვირთვა არჩეული დეპარტამენტის სასმახურებისა და მათი პოზიციების */
		for(var i = 0; i < staffs[$(this).val()].staff.service.length; i++) {
			$("#service1, #services, #service").append("<option value=\"" + staffs[$(this).val()].staff.service[i] + "\">" + staffs[$(this).val()].staff.service[i] + "</option>");
		}

		for(var i = 0; i < staffs[$(this).val()].freelance.position.length; i++) {
			$("#position_freelance1, #position_freelance").append("<option value=\"" + staffs[$(this).val()].freelance.position[i] + "\">" + staffs[$(this).val()].freelance.position[i] + "</option>");
		}

		for(var i = 0; i < staffs[$(this).val()].staff.position.length; i++) {
			$("#position_staff1, #position_staff").append("<option value=\"" + staffs[$(this).val()].staff.position[i] + "\">" + staffs[$(this).val()].staff.position[i] + "</option>");
		}
	});

	$("#service1, #services, #service").change(function() {
		/* მოცემულ სელექტორებში ის სელექტორები, რომლებიც ბოლოვდებიან 1-ით, განკუთვნილია თანამშრომლის რედაქტირების ველებისთვის, ხოლო, რომლებიც არ ბოლოვდებიან 1-ით ისინი განკუთვნილნი არიან ფილტრაციისთვის */
		$("#reg_positions1").empty(); // რეგიონული პოზიციების ჯგუფის გასუფთავება პოზიციების დროფდაუნში
		$("#reg_positions").empty(); // რეგიონული პოზიციების ჯგუფის გასუფთავება პოზიციების დროფდაუნში
		$("#reg_positions1").prop("hidden", false); // რეგიონული პოზიციების ჯგუფის დროფდაუნის გამოჩენა
		$("#reg_positions").prop("hidden", false); // რეგიონული პოზიციების ჯგუფის დროფდაუნის გამოჩენა

		for(var i = 0; i < staffs[$(this).val()].positions.length; i++) {
			$("#reg_positions1, #reg_positions").append("<option value=\"" + staffs[$(this).val()].positions[i] + "\">" + staffs[$(this).val()].positions[i] + "</option>");
		}
	});

	// კითხვის წაშლის ღილაკზე დაკლიკებით გამოიტანს შეტყობინებას ნამდვილად სურს თუ არა წაშლა
	$(".danger-btn").on("click", function() {
		$(".agree").attr("href", "/delete_question/" + $(this).attr("data-question-id"));
		$(".modal-action").fadeIn("fast"); // შეტყობინების გამოჩენა
	});

	// კითხვის ტიპის ასარჩევი ღილაკების, გადასართველების ფუნქციონალი, რომლებიც მუშაობენ კითხვის დამატების გვერდზე
	$(".switch").each(function(index, data) {
		$(this).click(function() {
			if(index == 0) {
				$("#single").fadeIn("fast");
				$("#freeq").fadeOut("fast");
				$("#multiple").fadeOut("fast");
			}else if(index == 1) {
				$("#freeq").fadeIn("fast");
				$("#single").fadeOut("fast");
				$("#multiple").fadeOut("fast");
			}else if(index == 2) {
				$("#multiple").fadeIn("fast");
				$("#freeq").fadeOut("fast");
				$("#single").fadeOut("fast");
			}
		});
	});

	// კითხვების მოსანიშნი ფუნქციონალი
	$(".mark_question").click(function() {
		let switcher = Number.parseInt($(this).attr("data-counter"));
		if(switcher % 2 == 1) {
			$(this).parent().parent().parent().css("background-color", "#fa9f47");
			$(this).attr("src", "http://edu.rda.gov.ge/images/icons/checked.png");
		}else if(switcher % 2 == 0) {
			$(this).parent().parent().parent().css("background-color", "#fff");
			$(this).attr("src", "http://edu.rda.gov.ge/images/icons/checkk.png");
		}
		$(this).attr("data-counter", switcher += 1);
	});

	// კითხვის თემატიკაზე დაკლიკების სემდგომ გამოხტება ფანჯარა სადაც მოცემული იქნება დამატებული თემატიკები
	$('[name="subject1"], [name="subject"], [name="subject2"]').click(function() {
		$.ajax({
			method : "GET",
			url : "/load_subjects",
			success : function(data) {
				$(".datas").html(data);
			}
		});
		$(".subjects_dropdown").fadeIn("fast");
	});
	// კითხვის თემატიკის ველის გარეთ დაკლიკების შემთხვევაში გაქრება თემატიკების ფანჯარა
	$('[name="subject1"], [name="subject"], [name="subject2"]').blur(function() {
		$(".subjects_dropdown").fadeOut("fast");
	});

	// ახალი პასუხის დამატების ფუნქცია, ასარჩევი კითხვის ტაბში
	let answer_count = 1;
	$("#new_answer").click(function() {
		$("#multiple_answer").append(`
			<tr>
				<td>
					<input type="text" placeholder="პასუხი ${answer_count}" name="answer${answer_count}" id="answer${answer_count}" />&nbsp;&nbsp;
					<span class="set_answer">
						<img src="http://edu.rda.gov.ge/images/icons/checkk.png" class="mark" data-id="1">
					</span>
				</td>
			</tr>`);
		answer_count += 1;
	});

	// თანამშრომელთა ფილტრაციის ჩვენების კოდი
	$("#filter").click(function() {
		$(".filter-block").fadeIn("fast");
		$(".blur-block").fadeIn("fast"); // გამოჩნდება დაბლარული ბლოკი
	});

	// თანამშრომელთა გაფილტვრის ფუნქციონალის კოდი
	$("#filter_employee").submit(function(event) {
		event.preventDefault(); // მოახდენს პრევენციას ფორმის დასაბმითებისაგან
		$department = $("#department").val(); // დეპარტამენტის დროფდაუნის მნიშვნელობა
		$service = $("#service").val(); // სამსახურის დროფდაუნის მნიშვნელობა
		$position = $("#position").val(); // პოზიციის დროფდაუნის მნიშვნელობა
		// თუ ყველა დროფდაუნის მნიშვნელობა იქნება ცარიელი გამოვა შესაბამისი შეტყობინება
		if($department == "" && $service == "" && $position == "") window.alert("აირჩიეთ ერთი კატეგორია მაინც!");
		else {
			$.ajax({
				method : "GET", // მოთხოვნის ტიპი
				url : "/filter_employee", // მოთხოვნის მისამართი
				data : $("#filter_employee").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე
	
				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="filter_emp"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},
	
				success : function(data) {
					$("#all-employee").html(data);
					$('[name="filter_emp"]').text("გაფილტვრა").prop("disabled", false); // გამორთული ღილაკის ეფექტის გაუქმება
				}
			});
		}
	});

	/* ======== პასუხების მონიშვნისას ხდება მათი შეფერადება ფერით, რათა მომხმარებელმა ადვილად გაარჩიოს
	მონიშნული კითხვა მოუნიშნავისაგან */
	// ==================================================== //
	$(".question-label").each(function() {
		$(this).click(function() {
			$(this).css("background-color", "palegreen");
		});

		$(this).focusout(function() {
			$(this).css("background-color", "#fff");
		});

		$(this).find(":radio").each(function() {
			if($(this).is(":checked")) {
				$(this).parent().css("background-color", "palegreen");
			}
		});
	});
	
	// ასარჩევ პასუხებიან კითხვების პასუხებზე დაკლიკება, განკლიკებისას მიენიჭებათ შესაბამისი ფერები
	$(".question-label1").each(function() {
		$(this).click(function() {
			if($(this).find(":checkbox").is(":checked")) {
				$(this).css("background-color", "palegreen");
			}else {
				$(this).css("background-color", "#fff");
			}
		});
	});

	$(document).click(function() {
		$(".question-label").find(":radio").each(function() {
			if($(this).is(":checked")) {
				$(this).parent().css("background-color", "palegreen");
			}else {
				$(this).parent().css("background-color", "#fff");
			}
		});
	});
	// ==================================================== //

	// კითხვის ფილტრაციის ფუნქციონალი
	$("#question_filter_form").submit(function(event) {
		event.preventDefault(); // მოცემული კოდი არ მოახდენს ფორმის დასაბმითებას

		$q_subject = $(this).find("#subject").val(); // კითხვის თემატიკის მნიშვნელობა
		$q_type = $("#qtype").val(); // კითხვის ტიპის მნიშვნელობა
		$q_wscore = $("#wscore").val(); // კითხვის არასწორი პასუხის ქულის მნიშვნელობა
		$q_score = $("#f_score").val(); // კითხვის წონის/ქულის მნიშვნელობა
		$q_duration = $("#f_duration").val(); // კითხვის ხანგრძლივობის მნიშვნელობა

		if($q_subject == "" && $q_type == "" && $q_wscore == "" && $q_score == "" && $q_duration == "") {
			window.alert("აირჩიეთ ერთი კატეგორია მაინც");
		}else {
			$.ajax({
				method : "GET", // მოთხოვნის ტიპი
				url : "/filter_questions", // მოთხოვნის მისამართი
				data : $("#question_filter_form").serialize(), // ფორმაში შეყვანილი მონაცემები, რომლებიც უნდა გაიგზავნოს სერვერზე

				beforeSend : function() {
					// მოთხოვნის გაგზავნამდე შესრულდება მოცემული კოდი, ღილაკს დაედება გამორთული ღილაკის ეფექტი
					$('[name="filter_question"]').html("დაელოდეთ&nbsp;<img src='../images/icons/wait.png' id='sp'>").prop("disabled", true);
				},

				success : function(data) {
					$("#all").html(data);
					$('[name="filter_question"]').text("გაფილტვრა").prop("disabled", false); // გაუქმდება გმორთული ღილაკის ეფექტი
				}
			});
		}
	});

	/* მოცემული კოდის საშუალებით, კითხვების თემატიკაზე მაუსის მიტანისას გამოვა ფანჯარა, სადაც იქნება მითითებული, თუ რამდენი აქტიური და არააქტიური კითხვაა თემატიკაში */
	$(document).on("mouseover", '[data-question-subject="subject"]', function() {
		$.ajax({
			method : "GET", // მოთხოვნის ტიპი
			url : "/count_questions/" + $(this).text(), // მოთხოვნის მისამართი გადაცემული პარამეტრით, რომელიც არის ტესტის თემატიკის მნიშვნელობა
			success : function(data) {
				window.alert("მოცემულ თემატიკაში " + data.actives + " აქტიური და " + data.inactives + " არააქტიური კითხვაა"); // გამოვა აქტიური კითხვების რაოდენობა
			}
		});
	});

	// მოცემული კოდის საშუალებით მოხდება ყველა არააქტიური კითხვის გააქტიურება
	$(document).on("click", '[name="make-all-active"]', function() {
		$(".toggle1").each(function() {
			$(this).find(".slider1").css("margin-left", "30px");
			$(this).css("background-color", "#1ca35e");
		});
		// გაიგზავნება მოთხოვნა სერვერზე, რომ არააქტიური კითხვები გააქტიურდეს
		$.get("/set_all_active", function(response) {
			console.log(response);
		});
	});

	// მოცემული კოდის საშუალებით მოხდება ყველა აქტიური კითხვის არააქტიურზე გადაყვანა
	$("#make-inactive-all").click(function() {
		$(".toggle").each(function() {
			$(this).find(".slider").css("margin-left", "5px");
			$(this).css("background-color", "lightgray");
		});
		// გაიგზავნება მოთხოვნა სერვერზე, რომ ყველა აქტიური კითხვა გახდეს არააქტიური
		$.get("/set_all_inactive", function(response) {
			console.log(response);
		});
	});

	$("#back").click(function() {
		window.history.back();
	});
});