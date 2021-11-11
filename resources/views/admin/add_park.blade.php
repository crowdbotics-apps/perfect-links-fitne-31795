@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Add Park")

@section('container')
<?php //echo '<pre>'; print_r($lawyers); ?>
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
						<li class="active"><a href="#description">Add Park</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/add_park')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="park_name" type="text" class="form-control" placeholder="Park Name" required>
														</div>
														<div class="form-group">
															<select name="park_type" class="form-control" required>
																<option value="">Select Park Type</option>
																<option value="1">Park Link</option>
																<option value="2">Free Run</option>
																<option value="3">Both</option> 
															</select>
														</div>
														<div class="form-group">
															<input name="lat" type="text" class="form-control" placeholder="Latitude" required>
														</div>
														<div class="form-group">
															<input name="lon" type="text" class="form-control" placeholder="Longitude" required>
														</div>
														<div class="form-group alert-up-pd">
															<input name="park_image" class="" type="file" required />
														</div>
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<textarea name="park_address" placeholder="Park Address" required></textarea>
														</div>
														<div class="form-group">
															<textarea name="about_park" placeholder="About Park" required></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12">
														<div class="payment-adress">
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
				</div>
			</div>
		</div>
	</div>
</div>
@endsection