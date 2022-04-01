@foreach($subjects as $subj)
	<tr>
		<th class="subjectsvalue" data-value="{{ $subj->subject_name }}" style="padding: 10px !important">
			{{ $subj->subject_name }}
		</th>
	</tr>
@endforeach
<script type="text/javascript" language="">
	$(document).ready(function() {
		$(".subjectsvalue").click(function() {
			// თემატიკები რაც ჩამოიყრება კითხვების დამატების გვერდზე, თითოეულზე დაკლიკებისას ის
			// კონკრეტული თემა ჩაიწერება თემატიკის ველში მნიშვნელობად
			$(this).parent().parent().parent().parent().find('[name="subject1"], [name="subject"], [name="subject2"]').empty();
			$(this).parent().parent().parent().parent().find('[name="subject1"], [name="subject"], [name="subject2"]').val($(this).attr("data-value"));
		});
	});
</script>