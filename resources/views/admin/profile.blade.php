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
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-payment-inner-st">
					<ul id="myTabedu1" class="tab-review-design">
						<li class="active"><a href="#myProfile">My Profile</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="myProfile">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
										<?php //echo '<pre>'; print_r($userinfo);?>
											<div class="col-lg-12">
												<div class="devit-card-custom">
													<form method="post" action="{{URL::to('/admin/my_profile')}}" enctype="multipart/form-data">
													{{ csrf_field() }}
													<div class="form-group">
														<input type="email" class="form-control" name="email" value="{{ $userinfo->email}}" placeholder="Email" disabled required>
														<input type="hidden" name="id" value="1">
														<input type="hidden" name="userId" value="N383VABLGL5J">
													</div>
													<div class="form-group">
														<input type="password" class="form-control" name="password" id="password"  placeholder="Password" required>
														
													</div>
													<div class="form-group">
														<input type="password" class="form-control" name="password_confirmation" id="password-confirm" placeholder="Confirm Password" required>
													</div>
													<input type="submit" class="btn btn-primary waves-effect waves-light" value="Submit">
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
	</div>
</div>
@endsection