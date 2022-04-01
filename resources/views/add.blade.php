@include("layouts.header")

<script type="text/javascript" language="javascript">
	document.title = "თანამშრომლის დამატება"; // მიმდინარე გვერდის სათაური
</script>

<div class="main-block-add">
	<br>
	<div class="container" style="width: 70%; margin-top: 30px;">
		@if(count($errors) > 0)
			<div class="alert alert-dismissable alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<ul>
					@foreach($errors->all() as $err)
						<li> {{ $err }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		@if(session("success_emp"))
			<div class="alert alert-dismissable alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong> {{ session("success_emp") }}</strong>
			</div>
		@endif
		@if(session("error_emp"))
			<div class="alert alert-dismissable alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong> {{ session("error_emp") }}</strong>
			</div>
		@endif
		<div class="panel panel-success">
			<div class="panel-heading">
				<h4 class="panel-title">თანამშრომლის დამატება</h4>
			</div>
			<div class="panel-body">
				<form method="post" action="" name="add_form" ng-app="">
					{{ csrf_field() }}
					<div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<label for="firstname">სახელი</label>
						<input type="text" class="form-control" placeholder="სახელი" name="firstname" id="firstname" ng-model="firstname" required>
						<span class="error" ng-show="add_form.firstname.$dirty && add_form.firstname.$invalid">სახელი აუცილებელია</span>
					</div>
					<div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<label for="lastname">გვარი</label>
						<input type="text" class="form-control" placeholder="გვარი" name="lastname" id="lastname" ng-model="lastname" required>
						<span class="error" ng-show="add_form.lastname.$dirty && add_form.lastname.$invalid">გვარი აუცილებელია</span>
					</div>
					<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
						<label for="email">იმეილი</label>
						<input type="email" class="form-control" placeholder="იმეილი" name="email" id="email" ng-model="email" required>
						<span class="error" ng-show="add_form.email.$dirty && add_form.email.$error.email">შეიტანეთ სწორი ფორმატის იმეილი</span><br>
						<span class="error" ng-show="add_form.email.$dirty && add_form.email.$invalid">იმეილი აუცილებელია</span>
					</div>
					<div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<label for="pid">პირადი ნომერი</label>
						<input type="text" class="form-control" placeholder="პირადი ნომერი" name="pid" id="pid" ng-model="pid" required>
						<span class="error" ng-show="add_form.pid.$dirty && add_form.pid.$invalid">პირადი ნომერი აუცილებელია</span>
					</div>
					<div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<label for="phone">ტელეფონი</label>
						<input type="text" class="form-control" placeholder="ტელეფონი" name="phone" id="phone" ng-model="phone" required>
						<span class="error" ng-show="add_form.phone.$dirty && add_form.phone.$invalid">ტელეფონი აუცილებელია</span>
					</div>
					<div class="form-group col-md-3 col-lg-3 col-sm-12 col-xs-12">
						<label for="department">დეპარტამენტი</label>
						<select class="form-control" name="department" id="department">
							<option value=""></option>
							<option value="შიდა აუდიტისა და კონტროლის დეპარტამენტი">შიდა აუდიტისა და კონტროლის დეპარტამენტი</option>
							<option value="საზოგადოებასთან ურთიერთობისა და მარკეტინგის დეპარტამენტი">საზოგადოებასთან ურთიერთობისა და მარკეტინგის დეპარტამენტი</option>
							<option value="ადამიანური რესურსების მართვის დეპარტამენტი">ადამიანური რესურსების მართვის დეპარტამენტი</option>
							<option value="საფინანსო დეპარტამენტი">საფინანსო დეპარტამენტი</option>
							<option value="ადმინისტრაციული დეპარტამენტი">ადმინისტრაციული დეპარტამენტი</option>
							<option value="საინფორმაციო დეპარტამენტი">საინფორმაციო დეპარტამენტი</option>
							<option value="პროექტების საოპერაციო დეპარტამენტი">პროექტების საოპერაციო დეპარტამენტი</option>
							<option value="პროექტების მართვისა და ტექნიკური დახმარების დეპარტამენტი">პროექტების მართვისა და ტექნიკური დახმარების დეპარტამენტი</option>
							<option value="პროექტების განვითარების დეპარტამენტი">პროექტების განვითარების დეპარტამენტი</option>
							<option value="კოოპერატივების განვითარების დეპარტამენტი">კოოპერატივების განვითარების დეპარტამენტი</option>
							<option value="რეგიონებთან ურთიერთობის დეპარტამენტი">რეგიონებთან ურთიერთობის დეპარტამენტი</option>
						</select>
					</div>
					<div class="form-group col-md-3 col-lg-3 col-sm-12 col-xs-12">
						<label for="service">სამსახური</label>
						<select class="form-control"name="service" id="service">
							<option value=""></option>
						</select>
					</div>
					<div class="form-group col-md-3 col-lg-3 col-sm-12 col-xs-12">
						<label for="position">პოზიცია</label>
						<select name="position" id="position" class="form-control">
							<option value=""></option>
							<option value="დირექტორი">დირექტორი</option>
							<option value="დირექტორის პირველი მოადგილე">დირექტორის პირველი მოადგილე</option>
							<option value="დირექტორის მოადგილე">დირექტორის მოადგილე</option>
							<option value="დირექტორის მრჩეველი">დირექტორის მრჩეველი</option>
							<option value="დირექტორის თანაშემწე">დირექტორის თანაშემწე</option>
							<option value="დირექტორის პირველი მოადგილის თანაშემწე">დირექტორის პირველი მოადგილის თანაშემწე</option>
							<option value="დირექტორის მოადგილის თანაშემწე">დირექტორის მოადგილის თანაშემწე</option>
							<option value="შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი">შეღავათიანი აგროკრედიტის პროექტის კოორდინატორი</option>
							<option value="პროექტების კოორდინატორი">პროექტების კოორდინატორი</option>
							<optgroup label="შტატგარეშე" id="position_freelance"></optgroup>
							<optgroup label="შტატიანი" id="position_staff"></optgroup>
							<optgroup label="რეგიონები" id="reg_positions" hidden></optgroup>
						</select>
					</div>
					<div class="form-group col-md-3 col-lg-3">
						<label for="role">როლი</label>
						<select name="role" id="role" ng-model="role" required class="form-control">
							<option value=""></option>
							<option value="hr">HR</option>
							<option value="employee">თანამშრომელი</option>
							<option value="manager">მენეჯრი</option>
						</select>
						<span class="error" ng-show="add_form.role.$dirty && add_form.role.$invalid">პაროლი აუცილებელია</span>
					</div>
					<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
						<label for="password">პაროლი</label>
						<input type="password" class="form-control" placeholder="პაროლი (საწყისად შეიტანეთ 1234)" name="password" id="password" ng-model="password" required>
						<span class="error" ng-show="add_form.password.$dirty && add_form.password.$invalid">პაროლი აუცილებელია</span>
					</div>
					<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
						<input type="submit" name="add" value="თანამშრომლის დამატება" class="btn btn-success btn-block" ng-click="insert_employee()">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@include("layouts.sidebar")