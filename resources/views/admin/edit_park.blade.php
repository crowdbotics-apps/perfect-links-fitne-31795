@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Edit Park Information")

@section('container')
<?php //echo '<pre>'; print_r($park); ?>
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
						<li class="active"><!--<a href="#description">Add Park</a>--></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/edit_park')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="id" type="hidden" class="form-control" value="{{ $park[0]->id }}">
															<input name="park_name" type="text" class="form-control" value="{{ $park[0]->park_name }}" placeholder="Park Name" required>
														</div>
														<div class="form-group">
															<select name="park_type" class="form-control" required>
																<option value="">Select Park Type</option>
																<option {{ $park[0]->park_type==1 ? 'selected':'' }} value="1">Park Link</option>
																<option {{ $park[0]->park_type==2 ? 'selected':'' }} value="2">Free Run</option>
																<option {{ $park[0]->park_type==3 ? 'selected':'' }} value="3">Both</option> 
															</select>
														</div>
														<div class="form-group">
															<input name="lat" type="text" class="form-control" value="{{ $park[0]->lat }}" placeholder="Latitude" required>
														</div>
														<div class="form-group">
															<input name="lon" type="text" class="form-control" value="{{ $park[0]->lon }}" placeholder="Longitude" required>
														</div>
														<div class="form-group alert-up-pd">
															<input name="park_image" class="" type="file" />
														</div>
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<textarea name="park_address" placeholder="Park Address" required>{{ $park[0]->park_address }}</textarea>
														</div>
														<div class="form-group">
															<textarea name="about_park" placeholder="About Park" required>{{ $park[0]->about_park }}</textarea>
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