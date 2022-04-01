@include("layouts.header")

<script type="text/javascript">
	document.title = "ტესტირების შედეგები";

	$(document).ready(function() {
		$.get("/load_test_results", function(data) {
			$(".table-block").html(data);
		});
	});

	$(document).on("click", ".pagination a", function(event){
		event.preventDefault(); 
		var page = $(this).attr("href").split("page=")[1];
		$.ajax({
			url : "/fetch_results?page=" + page,
			success : function(data) {
				$(".table-block").html(data);
			}
		});
	});
</script>

@if(auth()->user()->role == "employee" || auth()->user()->role == "manager")
	{{-- გვერდზე წვდომის განსაზღვრა--}}
	@php
		/* თუ ავტორიზირებული მომხმარებელი არის employee ან manager როლის მაშინ ის გადამისამართდება 
		მთავარ გვერდზე, რათა წვდომა არ მოხდეს მიმდინარე გვერდზე */
		header("Location: " . URL::to("/"), true, 302);
		exit();
	@endphp
@endif

<div class="main-block-add">
	<div class="table-block"></div>
</div>

@include("layouts.sidebar")
