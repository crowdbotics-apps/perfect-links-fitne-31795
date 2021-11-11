@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Edit Gym Profile")

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
.fieldTime{float: left; width: 42%;padding:5px;}
.timepicker{text-transform: uppercase !important;}
.ui-corner-all{text-transform: uppercase;}
</style>
<?php //echo '<pre>';print_r($gymProfile); ?>
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
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-payment-inner-st">
					<ul id="myTabedu1" class="tab-review-design">
						
@if(Session::has('TEMP_UPDATE_GYM'))
	@if(Session::get('TEMP_UPDATE_GYM')=='FIRST')
		<li class="active"><a href="#description">Gym Profile</a></li>
		<li class=""><a href="#equipm">Edit Equipment Quantity</a></li>
		<li class=""><a href="#subsc">Edit Subscription Details</a></li>
	@elseif(Session::get('TEMP_UPDATE_GYM')=='SECOND')
		<li><a href="#description">Gym Profile</a></li>
		<li class="active"><a href="#equipm">Edit Equipment Quantity</a></li>
		<li class=""><a href="#subsc">Edit Subscription Details</a></li>
	@elseif(Session::get('TEMP_UPDATE_GYM')=='THIRD')
		<li><a href="#description">Gym Profile</a></li>
		<li><a href="#equipm">Edit Equipment Quantity</a></li>
		<li class="active"><a href="#subsc">Edit Subscription Details</a></li>
	@endif
@else
		<li class="active"><a href="#description">Gym Profile</a></li>
		<li class=""><a href="#equipm">Edit Equipment Quantity</a></li>
		<li class=""><a href="#subsc">Edit Subscription Details</a></li>
@endif
						
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<!--<div class="product-tab-list tab-pane fade active in" id="description">-->
				@if(Session::has('TEMP_UPDATE_GYM'))
					@if(Session::get('TEMP_UPDATE_GYM')=='FIRST')
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
											<form method="post" action="{{URL::to('/admin/edit_gym_profile')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<div class="form-group fieldTitle">Select Gym Category</div>
														<div class="form-group">
															<!--<input name="gym_category" type="text" class="form-control" placeholder="Gym Category" required>-->
															<select name="gym_category" class="form-control" required>
																<option value="">Select Gym Category</option>
															@foreach($gym_category as $gymcategory)
																<option {{$gymcategory->id==$gymProfile->gym_category ? 'selected':''}} value="{{$gymcategory->id}}">{{$gymcategory->category_name}}</option>
															@endforeach
															</select>
														</div>
														<div class="form-group fieldTitle">Gym Title</div>
														<div class="form-group">
															<input name="gym_title" value="{{$gymProfile->gym_title}}" type="text" class="form-control" placeholder="Gym Title" required>
															<input name="user_id" value="{{$gymProfile->user_id}}" type="hidden" required>
														</div>
														<div class="form-group fieldTitle">Email Address</div>
														<div class="form-group">
															<input name="email" value="{{$gymProfile->email}}" type="email" class="form-control" placeholder="Email Address" required>
														</div>
														<div class="form-group fieldTitle">Select Country Code</div>
														<div class="form-group">
															<select name="country_code" class="form-control" required>
																<option value="">Select Country Code</option>
															@foreach($countries as $country)
																<option {{$country->phonecode==$gymProfile->country_code ? 'selected':''}}  value="{{$country->phonecode}}">{{$country->phonecode}}</option>
															@endforeach
															</select>
														</div>
														<div class="form-group fieldTitle">Contact Number</div>
														<div class="form-group">
															<input name="contact_number" value="{{$gymProfile->contact_number}}" type="text" class="form-control" placeholder="Contact Number" required>
														</div>
														<div class="form-group fieldTitle">Gym Address</div>
														<div class="form-group">
															<input name="address" value="{{$gymProfile->address}}" type="text" class="form-control" placeholder="Address" required>
														</div>
														<div class="form-group fieldTitle">Latitude</div>
														<div class="form-group">
															<input name="lat" value="{{$gymProfile->lat}}" type="text" class="form-control" placeholder="Latitude">
														</div>
														<div class="form-group fieldTitle">Longitude</div>
														<div class="form-group">
															<input name="lon" value="{{$gymProfile->lon}}" type="text" class="form-control" placeholder="Longitude">
														</div>
														<!--Monday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Monday Open Time</div>
															<div class="fieldTime">Monday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[0]->open_close_time!=""){
															$mondayOpenclose = json_decode($gymOpenTime[0]->open_close_time);
														foreach($mondayOpenclose as $mkey=>$mval){
															$monday = explode('-', $mondayOpenclose[$mkey]);
															$mondayOpenTime = trim($monday[0]);
															$mondayCloseTime = trim($monday[1]);
															if($mkey==0){
														?>
															<input name="monopen[]" type="text" class="form-control timepicker" placeholder="Monday Open Time" style="width:40%;float:left;" value="{{$mondayOpenTime}}" autocomplete="off">
															<input name="monclose[]" type="text" class="form-control timepicker" placeholder="Monday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$mondayCloseTime}}" autocomplete="off" >
															<div class="plusButton" id="monPlus">+</div>
														<?php } else{ ?>
															<div id="monExt">
																<div class="form-group">
																	<input name="monopen[]" type="text" class="form-control timepicker" placeholder="Monday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$mondayOpenTime}}" required="" autocomplete="off">
																	<input name="monclose[]" type="text" class="form-control timepicker" placeholder="Monday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$mondayCloseTime}}" required="" autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php }
														}														
														} else {
														?>
															<input name="monopen[]" type="text" class="form-control timepicker" placeholder="Monday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="monclose[]" type="text" class="form-control timepicker" placeholder="Monday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="monPlus">+</div>
														<?php } ?>
														</div>
														<div id="monExt"></div>
														<!--Tuesday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Tuesday Open Time</div>
															<div class="fieldTime">Tuesday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[1]->open_close_time!=""){
															$tuesdayOpenclose = json_decode($gymOpenTime[1]->open_close_time);
															foreach($tuesdayOpenclose as $tkey=>$tval){
																$tuesday = explode('-', $tuesdayOpenclose[$tkey]);
																$tuesdayOpenTime = trim($tuesday[0]);
																$tuesdayCloseTime = trim($tuesday[1]);
																if($tkey==0){
														?>
															<input name="tuesopen[]" type="text" class="form-control timepicker" placeholder="Tuesday Open Time" style="width:40%;float:left;" value="{{$tuesdayOpenTime}}" autocomplete="off">
															<input name="tuesclose[]" type="text" class="form-control timepicker" placeholder="Tuesday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$tuesdayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="tuesPlus">+</div>
														<?php } else{ ?> 
															<div id="tuesExt">
																<div class="form-group">
																	<input name="tuesopen[]" type="text" class="form-control timepicker" placeholder="Tuesday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$tuesdayOpenTime}}" required="" autocomplete="off">
																	<input name="tuesclose[]" type="text" class="form-control timepicker" value="{{$tuesdayCloseTime}}" placeholder="Tuesday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required="" autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php } 
															}
														} else { ?>
															<input name="tuesopen[]" type="text" class="form-control timepicker" placeholder="Tuesday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="tuesclose[]" type="text" class="form-control timepicker" placeholder="Tuesday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="tuesPlus">+</div>
														<?php } ?>
														
														</div>
														<div id="tuesExt"></div>
														<!--Wednesday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Wednesday Open Time</div>
															<div class="fieldTime">Wednesday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[2]->open_close_time!=""){
															$wednesdayOpenclose = json_decode($gymOpenTime[2]->open_close_time);
															foreach($wednesdayOpenclose as $wkey=>$wval){
															$wednesday = explode('-', $wednesdayOpenclose[$wkey]);
															$wednesdayOpenTime = trim($wednesday[0]);
															$wednesdayCloseTime = trim($wednesday[1]);
															if($wkey==0){
														?>
															<input name="wedopen[]" type="text" class="form-control timepicker" placeholder="Wednesday Open Time" style="width:40%;float:left;" value="{{$wednesdayOpenTime}}" autocomplete="off">
															<input name="wedclose[]" type="text" class="form-control timepicker" placeholder="Wednesday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$wednesdayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="wedPlus">+</div>
														<?php } else{ ?>
															<div id="wedExt">
																<div class="form-group">
																	<input name="wedopen[]" type="text" class="form-control timepicker" placeholder="Wednesday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$wednesdayOpenTime}}" required="" autocomplete="off">
																	<input name="wedclose[]" type="text" class="form-control timepicker" placeholder="Wednesday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$wednesdayCloseTime}}" required="" autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php } }
														} else {
														?>
															<input name="wedopen[]" type="text" class="form-control timepicker" placeholder="Wednesday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="wedclose[]" type="text" class="form-control timepicker" placeholder="Wednesday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="wedPlus">+</div>
														<?php } ?>
														</div>
														<div id="wedExt"></div>
														
														<!--Thursday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Thursday Open Time</div>
															<div class="fieldTime">Thursday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[3]->open_close_time!=""){
															$thursdayOpenclose = json_decode($gymOpenTime[3]->open_close_time);
															foreach($thursdayOpenclose as $thkey=>$thval){
															$thursday = explode('-', $thursdayOpenclose[$thkey]);
															$thursdayOpenTime = trim($thursday[0]);
															$thursdayCloseTime = trim($thursday[1]);
															if($thkey==0){
														?>
															<input name="thursopen[]" type="text" class="form-control timepicker" placeholder="Thursday Open Time" style="width:40%;float:left;" value="{{$thursdayOpenTime}}" autocomplete="off">
															<input name="thursclose[]" type="text" class="form-control timepicker" placeholder="Thursday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$thursdayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="thursPlus">+</div>
														<?php } else{ ?>
															<div id="thursExt">
																<div class="form-group">
																	<input name="thursopen[]" type="text" class="form-control timepicker" placeholder="Thursday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$thursdayOpenTime}}" required="" autocomplete="off">
																	<input name="thursclose[]" type="text" class="form-control timepicker" placeholder="Thursday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$thursdayCloseTime}}" required="" autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php }
														} } else {
														?>	
															<input name="thursopen[]" type="text" class="form-control timepicker" placeholder="Thursday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="thursclose[]" type="text" class="form-control timepicker" placeholder="Thursday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="thursPlus">+</div>
														<?php } ?>
														</div>
														<div id="thursExt"></div>
														
														<!--Friday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Friday Open Time</div>
															<div class="fieldTime">Friday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[4]->open_close_time!=""){
															$fridayOpenclose = json_decode($gymOpenTime[4]->open_close_time);
															
															foreach($fridayOpenclose as $fkey=>$fval){
																$friday = explode('-', $fridayOpenclose[$fkey]);
																$fridayOpenTime = trim($friday[0]);
																$fridayCloseTime = trim($friday[1]);
																if($fkey==0){
														?>
															<input name="fridopen[]" type="text" class="form-control timepicker" placeholder="Friday Open Time" style="width:40%;float:left;" value="{{$fridayOpenTime}}" autocomplete="off">
															<input name="fridclose[]" type="text" class="form-control timepicker" placeholder="Friday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$fridayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="fridPlus">+</div>
															<?php } else{ ?>
															<div id="fridExt">
																<div class="form-group">
																	<input name="fridopen[]" type="text" class="form-control timepicker" placeholder="Friday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$fridayOpenTime}}" required autocomplete="off">
																	<input name="fridclose[]" type="text" class="form-control timepicker" placeholder="Friday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$fridayOpenTime}}" required autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
															<?php } }
														} else {
														?>		
															<input name="fridopen[]" type="text" class="form-control timepicker" placeholder="Friday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="fridclose[]" type="text" class="form-control timepicker" placeholder="Friday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="fridPlus">+</div>
														<?php } ?>
														</div>
														<div id="fridExt"></div>
														
														<!--Saturday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Saturday Open Time</div>
															<div class="fieldTime">Saturday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[5]->open_close_time!=""){
															$saturdayOpenclose = json_decode($gymOpenTime[5]->open_close_time);
															foreach($saturdayOpenclose as $skey=>$sval){
																$saturday = explode('-', $saturdayOpenclose[$skey]);
																$saturdayOpenTime = trim($saturday[0]);
																$saturdayCloseTime = trim($saturday[1]);
															if($skey==0){
														?>
															<input name="satopen[]" type="text" class="form-control timepicker" placeholder="Saturday Open Time" style="width:40%;float:left;" value="{{$saturdayOpenTime}}" autocomplete="off">
															<input name="satclose[]" type="text" class="form-control timepicker" placeholder="Saturday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$saturdayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="satPlus">+</div>	
														<?php } else { ?> 
															<div id="satExt">
																<div class="form-group">
																	<input name="satopen[]" type="text" class="form-control timepicker" placeholder="Saturday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$saturdayOpenTime}}" required autocomplete="off">
																	<input name="satclose[]" type="text" class="form-control timepicker" placeholder="Saturday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$saturdayCloseTime}}" required autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php }
															}
														} else {
														?>			
															<input name="satopen[]" type="text" class="form-control timepicker" placeholder="Saturday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="satclose[]" type="text" class="form-control timepicker" placeholder="Saturday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="satPlus">+</div>
														<?php } ?>
														</div>
														<div id="satExt"></div>
														
														<!--Sunday-->
														<div class="form-group fieldTitle">
															<div class="fieldTime">Sunday Open Time</div>
															<div class="fieldTime">Sunday Close Time</div>
														</div>
														<div class="form-group">
														<?php
														if($gymOpenTime[6]->open_close_time!=""){
															$sundayOpenclose = json_decode($gymOpenTime[6]->open_close_time);
															
															foreach($sundayOpenclose as $sukey=>$suval){
																$sunday = explode('-', $sundayOpenclose[$sukey]);
																$sundayOpenTime = trim($sunday[0]);
																$sundayCloseTime = trim($sunday[1]);
															if($sukey==0){
														?>
															<input name="sundopen[]" type="text" class="form-control timepicker" placeholder="Sunday Open Time" style="width:40%;float:left;" value="{{$sundayOpenTime}}" autocomplete="off">
															<input name="sundclose[]" type="text" class="form-control timepicker" placeholder="Sunday Close Time" style="width:40%;float:left;margin-left:10px;" value="{{$sundayCloseTime}}" autocomplete="off">
															<div class="plusButton" id="sundPlus">+</div>
														<?php } else { ?>
															<div id="sundExt">
																<div class="form-group">
																	<input name="sundopen[]" type="text" class="form-control timepicker" placeholder="Sunday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" value="{{$sundayOpenTime}}" required autocomplete="off">
																	<input name="sundclose[]" type="text" class="form-control timepicker" placeholder="Sunday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" value="{{$sundayCloseTime}}" required autocomplete="off">
																	<div class="plusButton removeGroup">X</div>
																</div>
															</div>
														<?php }
														} } else {
														?>			
															<input name="sundopen[]" type="text" class="form-control timepicker" placeholder="Sunday Open Time" style="width:40%;float:left;" autocomplete="off">
															<input name="sundclose[]" type="text" class="form-control timepicker" placeholder="Sunday Close Time" style="width:40%;float:left;margin-left:10px;" autocomplete="off">
															<div class="plusButton" id="sundPlus">+</div>
														<?php } ?>
														</div>
														<div id="sundExt"></div>
														
														<div class="form-group alert-up-pd" style="clear:both;">
															<div class="form-group fieldTitle">Cover Image</div>
															<input name="cover_image" class="" type="file" {{ $gymProfile->cover_image!='' ? '':'required' }} />
														</div>
														
														<div class="form-group alert-up-pd">
															<div class="form-group fieldTitle">Multiple Image</div>
															<input name="galary_image[]" class="" type="file" multiple 
															{{ !empty($gym_galary_images) ? "" : "required" }} >
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
															<textarea id="summernote1" rows="6" name="brief_description" placeholder="Brief Description" required>{{$gymProfile->brief_description}}</textarea>
														</div>
														<div class="form-group fieldTitle">Full Description</div>
														<div class="form-group">
															<!--<textarea name="full_description" placeholder="Full Description"></textarea>-->
															<textarea id="summernote2" rows="6" name="full_description" placeholder="Full Description" required>{{$gymProfile->full_description}}</textarea>
														</div>
													</div>
													
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="requredEqupment">
													<div class="form-group fieldTitle">Select Gym Equipments</div>
													@foreach($gym_equipments as $key=>$value)
														<input type="checkbox" value="{{$value->id}}" name="equipmentId[]" {{ in_array($value->id,$selectedEqu) ? 'checked' : ''}} >
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
<div class="form-group alert-up-pd" style="position: absolute; margin-top: -903px;margin-left: 51%;"> 
    
	<div style="margin-top:30px;">
	<?php //echo '<pre>'; print_r($gym_galary_images);?>
		@foreach($gym_galary_images as $key=>$galaryImage)
			<form style="float:left; margin-right:10px;" id="formId_{{$galaryImage->id}}">
				<img src="/assets/images/gym_galary_image/{{$galaryImage->image}}" width="100px" height="100px" id="img__{{$galaryImage->id}}">
				<input type="hidden" name="productId_{{$galaryImage->id}}" value="{{$galaryImage->id}}">
				<input type="hidden" name="type_{{$galaryImage->id}}" value="GYM">
				<button class="galImgRmv" id="{{$galaryImage->id}}" >&#10005;</button>
			</form>
		@endforeach
	</div>
</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!---->
				@if(Session::has('TEMP_UPDATE_GYM'))
					@if(Session::get('TEMP_UPDATE_GYM')=='SECOND')		
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
										@if(!empty($gymEquipmets))
										<div id="dropzone1" class="pro-ad addcoursepro">
											
											<div class="row">
											<div class="col-xs-2" style="padding-bottom:10px;font-weight: bold;">Seq. No. </div>
											<div class="col-xs-3" style="padding-bottom:10px;font-weight: bold;">Equipment Image</div>
											<div class="col-xs-4" style="padding-bottom:10px;font-weight: bold;">Equipment Name</div>
											<div class="col-xs-3" style="padding-bottom:10px;font-weight: bold;">Equipment Quantity</div>
<form method="post" action="{{URL::to('/admin/update_gym_euqupment_quantity')}}" enctype="multipart/form-data" class="" id="demo1-upload">
{{ csrf_field() }}
	<input type="hidden" name="user_id" value="{{$gymProfile->user_id}}">
	@foreach($gymEquipmets as $key=>$value)
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
				
				<input type="text" name="equipQuent[{{$key}}]" value="{{$value->quantity}}" onkeypress="return isNumberKey(event)" required>
			</div>
		</div>
	@endforeach
		<div class="col-xs-11" style="margin-top: 22px;">
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
						
						
@if(Session::has('TEMP_UPDATE_GYM'))
	@if(Session::get('TEMP_UPDATE_GYM')=='THIRD')
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


									
										
										<div id="dropzone1" class="pro-ad addcoursepro">
											
											<div class="row">

		<form method="post" action="{{URL::to('/admin/update_gym_subscription_details')}}" enctype="multipart/form-data" class="" id="demo1-upload">

{{ csrf_field() }}
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group fieldTitle">Subscription Title</div>
			<div class="form-group">
			<input type="hidden" name="user_id" value="{{$value->user_id}}">
				<input name="subscription_title" type="text" value="{{$gymProfile->subscription_title}}" class="form-control" placeholder="Subscription Title" required>
			</div>
			<div class="form-group fieldTitle">Subscription Amount</div>
			<div class="form-group">
				<input name="subscription_amount" type="text" value="{{$gymProfile->subscription_amount}}" class="form-control" placeholder="Subscription Amount" required>
			</div>
			<div class="form-group fieldTitle">Subscription Time Period</div>
			<div class="form-group">
				<input name="subscription_month" type="hidden" value="1" class="form-control" placeholder="Subscription Time Period" required>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group fieldTitle">Subscription Description</div>
			<div class="form-group">
				<!--<textarea name="full_description" placeholder="Full Description"></textarea>-->
				<textarea id="summernote2" rows="6" name="subscription_details" placeholder="Subscription Description" required>{!! html_entity_decode($gymProfile->subscription_details) !!}</textarea>
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