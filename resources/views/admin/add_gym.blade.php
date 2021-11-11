@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Add Gym")

@section('container')
<style>
.plusButton{    
    width: 17%;
    float: left;
    color: #18520afc;
    padding: 2px;
    text-align: center;
    font-size: 27px;
    font-weight: bold;
	cursor:pointer;
}
.fieldTitle{    
	font-size: 18px;
    font-weight: bold;
	padding:5px;
}
.fieldTime{float: left; width: 42%;padding:5px; }
input.timepicker{text-transform: uppercase;}
.ui-corner-all{text-transform: uppercase;}
</style>
<?php //echo '<pre>';print_r($gym_category); ?>
@if(session('alert-success'))
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="col-lg-3 col-md-12 col-sm-12 col-xs-3"></div>
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
		<div class="alert alert-success alert-st-one" role="alert">
			<i class="fa fa-check edu-checked-pro admin-check-pro" aria-hidden="true"></i>
			<p class="message-mg-rt"><strong>{{session('alert-success')}}</strong></p>
		</div>
	</div>
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-3"></div>
</div>
@endif

<div class="single-pro-review-area mt-t-30 mg-b-15">
	<div class="container-fluid">
		<div class="row">
<!--
<div class="input-group bootstrap-timepicker timepicker">
	<input id="timepicker1" type="text" class="form-control input-small">
	<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
</div>
<script type="text/javascript">
	$('#timepicker1').timepicker();
</script>
-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-payment-inner-st">
					<ul id="myTabedu1" class="tab-review-design">
@if(Session::has('TEMP_CREATE_GYM')) 
	@if(Session::get('TEMP_CREATE_GYM')=='FIRST')
		<li class="active"><a href="#description">Add Gym</a></li>
		<li class=""><a href="#equipm">Add Equipment Quantity</a></li>
		<li class=""><a href="#subsc">Add Subscription Details</a></li>
	@elseif(Session::get('TEMP_CREATE_GYM')=='SECOND')
		<li><a href="#description">Gym Profile</a></li>
		<li class="active"><a href="#equipm">Add Gym</a></li>
		<li class=""><a href="#subsc">Add Subscription Details</a></li>
	@elseif(Session::get('TEMP_CREATE_GYM')=='THIRD')
		<li><a href="#description">Gym Profile</a></li>
		<li><a href="#equipm">Add Equipment Quantity</a></li>
		<li class="active"><a href="#subsc">Add Subscription Details</a></li>
	@endif
@else
		<li class="active"><a href="#description">Add Gym</a></li>
		<li class=""><a href="#equipm">Add Equipment Quantity</a></li>
		<li class=""><a href="#subsc">Add Subscription Details</a></li>
@endif
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<!--<div class="product-tab-list tab-pane fade active in" id="description">-->
						
						@if(Session::has('TEMP_CREATE_GYM'))
							@if(Session::get('TEMP_CREATE_GYM')=='FIRST')
								<div class="product-tab-list tab-pane fade active in" id="description">
							@else
								<div class="product-tab-list tab-pane fade" id="description">
							@endif
						@else
							<div class="product-tab-list tab-pane fade active in" id="description">
						@endif
						
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/add_gym')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group fieldTitle">Select Gym Category</div>
														<div class="form-group">
															<!--<input name="gym_category" type="text" class="form-control" placeholder="Gym Category" required>-->
															<select name="gym_category" class="form-control" required>
																<option value="">Select Gym Category</option>
															@foreach($gym_category as $gymcategory)
																<option value="{{$gymcategory->id}}">{{$gymcategory->category_name}}</option>
															@endforeach
															</select>
														</div>
														<div class="form-group fieldTitle">Gym Title</div>
														<div class="form-group">
															<input name="gym_title" type="text" class="form-control" placeholder="Gym Title" required>
														</div>
														<div class="form-group fieldTitle">Email Address</div>
														<div class="form-group">
															<input name="email" type="email" class="form-control" placeholder="Email Address" required>
														</div>
														<div class="form-group fieldTitle">Select Country Code</div>
														<div class="form-group">
															<select name="country_code" class="form-control" required>
																<option value="">Select Country Code</option>
															@foreach($countries as $country)
																<option value="{{$country->phonecode}}">{{$country->nicename}}</option>
															@endforeach
															</select>
														</div>
														<div class="form-group fieldTitle">Contact Number</div>
														<div class="form-group">
															<input name="contact_number" type="text" class="form-control" placeholder="Contact Number" required>
														</div>
														<div class="form-group fieldTitle">Gym Address</div>
														<div class="form-group">
															<input name="address" type="text" class="form-control" placeholder="Address" required>
														</div>
														<div class="form-group fieldTitle">Latitude</div>
														<div class="form-group">
															<input name="lat" type="text" class="form-control" placeholder="Latitude">
														</div>
														<div class="form-group fieldTitle">Longitude</div>
														<div class="form-group">
															<input name="lon" type="text" class="form-control" placeholder="Longitude">
														</div>
														<!--Monday-->
														
														<div class="form-group fieldTitle">
															<div class="fieldTime">Monday Open Time</div>
															<div class="fieldTime">Monday Close Time</div>
														</div>
														<div class="form-group">
															<input name="monopen[]" type="text" class="form-control timepicker readonly" placeholder="Monday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="monclose[]" type="text" class="form-control timepicker" placeholder="Monday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="monPlus">+</div>
														</div>
														<div id="monExt"></div>
														<!--Tuesday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Tuesday Open Time</div>
															<div class="fieldTime">Tuesday Close Time</div>
														</div>
														<div class="form-group">
															<input name="tuesopen[]" type="text" class="form-control timepicker" placeholder="Tuesday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="tuesclose[]" type="text" class="form-control timepicker" placeholder="Tuesday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="tuesPlus">+</div>
														</div>
														<div id="tuesExt"></div>
														<!--Wednesday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Wednesday Open Time</div>
															<div class="fieldTime">Wednesday Close Time</div>
														</div>
														<div class="form-group">
															<input name="wedopen[]" type="text" class="form-control timepicker" placeholder="Wednesday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="wedclose[]" type="text" class="form-control timepicker" placeholder="Wednesday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="wedPlus">+</div>
														</div>
														<div id="wedExt"></div>
														
														<!--Thursday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Thursday Open Time</div>
															<div class="fieldTime">Thursday Close Time</div>
														</div>
														<div class="form-group">
															<input name="thursopen[]" type="text" class="form-control timepicker" placeholder="Thursday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="thursclose[]" type="text" class="form-control timepicker" placeholder="Thursday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="thursPlus">+</div>
														</div>
														<div id="thursExt"></div>
														
														<!--Friday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Friday Open Time</div>
															<div class="fieldTime">Friday Close Time</div>
														</div>
														<div class="form-group">
															<input name="fridopen[]" type="text" class="form-control timepicker" placeholder="Friday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="fridclose[]" type="text" class="form-control timepicker" placeholder="Friday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="fridPlus">+</div>
														</div>
														<div id="fridExt"></div>
														
														<!--Saturday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Saturday Open Time</div>
															<div class="fieldTime">Saturday Close Time</div>
														</div>
														<div class="form-group">
															<input name="satopen[]" type="text" class="form-control timepicker" placeholder="Saturday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="satclose[]" type="text" class="form-control timepicker" placeholder="Saturday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="satPlus">+</div>
														</div>
														<div id="satExt"></div>
														
														<!--Sunday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Sunday Open Time</div>
															<div class="fieldTime">Sunday Close Time</div>
														</div>
														<div class="form-group">
															<input name="sundopen[]" type="text" class="form-control timepicker" placeholder="Sunday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="sundclose[]" type="text" class="form-control timepicker" placeholder="Sunday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="sundPlus">+</div>
														</div>
														<div id="sundExt"></div>
														
														<div class="form-group alert-up-pd" style="clear:both;">
															<div class="form-group fieldTitle">Cover Image</div>
															<input name="cover_image" class="" type="file" accept="image/png, image/jpg, image/jpeg" required />
														</div>
														
														<div class="form-group alert-up-pd">
															<div class="form-group fieldTitle">Multiple Image</div>
															<input name="galary_image[]" class="" type="file" accept="image/png, image/jpg, image/jpeg" multiple required>
														</div>
														<!--
														<div class="form-group alert-up-pd">
															<div style="">Video</div>
															<input name="video" class="" type="file" />
														</div>-->
														
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group fieldTitle">Brief Description</div>
														<div class="form-group">
															<!--<textarea name="brief_description" placeholder="Brief Description"></textarea>-->
															<textarea id="summernote1" rows="6" name="brief_description" placeholder="Brief Description" required></textarea>
														</div>
														<div class="form-group fieldTitle">Full Description</div>
														<div class="form-group">
															<!--<textarea name="full_description" placeholder="Full Description"></textarea>-->
															<textarea id="summernote2" rows="6" name="full_description" placeholder="Full Description" required></textarea>
														</div>
													</div>
													
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="requredEqupment">
													<div class="form-group fieldTitle">Select Gym Equipments</div>
													@foreach($gym_equipments as $key=>$value)
														<input type="checkbox" value="{{$value->id}}" name="equipmentId[]">
														{{$value->equipment_name}}
													@endforeach
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12">
														<div class="payment-adress">
															<button type="submit" class="btn btn-primary waves-effect waves-light" onclick="return gymFormSubmit();">Submit</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!---->
						
						<!--<div class="product-tab-list tab-pane fade" id="equipm">-->
				@if(Session::has('TEMP_CREATE_GYM'))
					@if(Session::get('TEMP_CREATE_GYM')=='SECOND')		
						<div class="product-tab-list tab-pane fade active in" id="equipm">
					@else
						<div class="product-tab-list tab-pane fade" id="equipm">
					@endif
				@else
						<div class="product-tab-list tab-pane fade" id="equipm">
				@endif
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
									<?php //echo '<pre>'; print_r($gym_selected); ?>
										@if(!empty($gym_selected))
										<div id="dropzone1" class="pro-ad addcoursepro">
											
											<div class="row">
											<div class="col-xs-2" style="padding-bottom:10px;font-weight: bold;">Seq. No. </div>
											<div class="col-xs-3" style="padding-bottom:10px;font-weight: bold;">Equipment Image</div>
											<div class="col-xs-4" style="padding-bottom:10px;font-weight: bold;">Equipment Name</div>
											<div class="col-xs-3" style="padding-bottom:10px;font-weight: bold;">Equipment Quantity</div>
<form method="post" action="{{URL::to('/admin/add_gym_euqupment_quantity')}}" enctype="multipart/form-data" class="" id="demo1-upload">
{{ csrf_field() }}
	@foreach($gym_selected as $key=>$value)
		<div class="row" style="margin-top: 15px;">
			<div class="col-xs-2" style="padding-top: 40px;padding-left: 65px;">
				{{$key+1}}
			</div>
			<div class="col-xs-3">
				<img src ="{{URL::asset('assets/images/equipments/'.$value->equipment_image)}}" width="100">
			</div>
			<div class="col-xs-4" style="padding-top: 40px;font-weight: bold;">
				{{$value->equipment_name}}
			</div>
			<div class="col-xs-3" style="padding-top: 40px;font-weight: bold;">
				<input type="hidden" name="id[{{$key}}]" value="{{$value->id}}">
				<input type="text" name="equipQuent[{{$key}}]" value="" onkeypress="return isNumberKey(event)" required>
			</div>
		</div>
	@endforeach
		<div class="col-xs-11">
			<button type="submit" class="btn btn-primary waves-effect waves-light" style="float:right;">Submit</button>
		</div>
</form>
											</div>
										</div>
										@else
											Please add Gym first.
										@endif
										
									</div>
								</div>
							</div>
						</div>
						
						<!---->
						
					<!--<div class="product-tab-list tab-pane fade" id="subsc">-->
				@if(Session::has('TEMP_CREATE_GYM'))
					@if(Session::get('TEMP_CREATE_GYM')=='THIRD')		
						<div class="product-tab-list tab-pane fade active in" id="subsc">
					@else
						<div class="product-tab-list tab-pane fade" id="subsc">
					@endif
				@else
						<div class="product-tab-list tab-pane fade" id="subsc">
				@endif
						
						
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
									<?php //echo '<pre>'; print_r($gym_selected); ?>
@if(session()->has('TEMP_SUBSCRIPTION_DETAILS_STATUS'))

									
										
										<div id="dropzone1" class="pro-ad addcoursepro">
											
											<div class="row">
											
<form method="post" action="{{URL::to('/admin/add_gym_subscription_details')}}" enctype="multipart/form-data" class="" id="demo1-upload">
{{ csrf_field() }}
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group fieldTitle">Subscription Title</div>
			<div class="form-group">
				<input name="subscription_title" type="text" class="form-control" placeholder="Subscription Title" required>
			</div>
				<div class="form-group fieldTitle">Subscription Amount</div>
			<div class="form-group">
				<input name="subscription_amount" type="text" class="form-control" placeholder="Subscription Amount" required>
			</div>
			<!--<div class="form-group fieldTitle">Subscription Time Period</div>-->
			<div class="form-group">
				<input name="subscription_month" type="hidden" class="form-control" placeholder="Subscription Time Period" value="1" required>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group fieldTitle">Subscription Description</div>
			<div class="form-group">
				<!--<textarea name="full_description" placeholder="Full Description"></textarea>-->
				<textarea id="summernote2" rows="6" name="subscription_details" placeholder="Subscription Description" required></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="payment-adress" style="float:right;">
				<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
			</div>
		</div>
	</div>
</form>
											</div>
										</div>
							
@else 
  	Please add Gym first.
@endif										
									</div>
								</div>
							</div>
						</div>
						
						
						<!---->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection