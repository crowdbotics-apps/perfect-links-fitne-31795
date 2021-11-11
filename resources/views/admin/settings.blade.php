@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Lawyers")

@section('container')
<?php //print_r($socialLinks);die;?>
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
						<li class="active"><a href="#INFORMATION">Social Information</a></li>
						<li class=""><a href="#reviews"> Footer Email & Phone Number</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="INFORMATION">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
											<div class="col-lg-12">
												<div class="devit-card-custom">
													<form method="post" action="{{URL::to('/admin/social_links')}}" enctype="multipart/form-data">
														{{ csrf_field() }}
														<div class="form-group">
															<input type="text" class="form-control" name="linkedin" value="{{$socialLinks->linkedin}}" placeholder="Linkedin URL" required>
															<input type="hidden" value="1" name="id">
														</div>
														<div class="form-group">
															<input type="text" class="form-control" name="instagram" value="{{$socialLinks->instagram}}" placeholder="Instagram URL" required>
														</div>
														<div class="form-group">
															<input type="text" class="form-control" name="twitter" value="{{$socialLinks->twitter}}" placeholder="Twitter URL" required>
														</div>
														<div class="form-group">
															<input type="text" class="form-control" name="facebook" value="{{$socialLinks->facebook}}" placeholder="Facebook URL" required>
														</div>
														
														<div class="form-group">
															<input type="text" class="form-control" name="google_plus" value="{{$socialLinks->google_plus}}" placeholder="Google Plus URL" required>
														</div>
														<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					
						<div class="product-tab-list tab-pane fade" id="reviews">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="devit-card-custom">
													<form method="post" action="{{URL::to('/admin/footer_email_phone')}}" enctype="multipart/form-data">
														{{ csrf_field() }}
														<div class="form-group">
															<input type="email" class="form-control" name="email" value="{{$footerEmailPhone->email}}" id="email" placeholder="Email" required>
															<input type="hidden" value="1" name="id">
														</div>
														<div class="form-group">
															<input type="text" class="form-control" name="phone" id="phone" value="{{$footerEmailPhone->phone}}" placeholder="Phone Number" required>
														</div>
														<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!--
						<div class="product-tab-list tab-pane fade" id="reviews">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="devit-card-custom">
												<form method="post" action="{{URL::to('/admin/update_super_admin_password')}}" enctype="multipart/form-data">
													{{ csrf_field() }}
													<div class="form-group">
														<input type="email" class="form-control" name="email" placeholder="Email" required>
														<input type="text" name="id" value="1">
														<input type="text" name="userId" value="N383VABLGL5J">
													</div>
													<div class="form-group">
														<input type="password" class="form-control" name="password" id="password"  placeholder="Password" required>
														
													</div>
													<div class="form-group">
														<input type="password" class="form-control" name="password_confirmation" id="password-confirm" placeholder="Confirm Password" required>
													</div>
													<a href="#!" class="btn btn-primary waves-effect waves-light">Submit</a>
												</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						-->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection