@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Add Coupon Code")

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
						<li class="active"><a href="#description">Add Coupon Code</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/add_coupon')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="title" type="text" class="form-control" placeholder="Page Title" required>
														</div>
														<div class="form-group">
															<input name="coupon_code" type="text" class="form-control" placeholder="Coupon Code" required>
														</div>
														<div class="form-group">
														<select name="coupon_type" class="form-control" required>
															<option value="">Select Type</option>
															<option value="amount">Amount</option>
															<option value="percentage">Percentage</option>
														</select>
														</div>	
														<div class="form-group">
														<select name="coupon_category" class="form-control" required>
															<option value="">Select Coupon Category</option>
															<option value="0">All</option>
															<option value="1">Gym</option>
															<option value="2">Trainer</option>
															<option value="3">Supplements</option>
															<option value="4">Accessories</option> 
														</select>
														</div>
														<div class="form-group">
															<input name="amount_percentage" type="text" class="form-control" placeholder="Amount/Percentage" required>
														</div>
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Brief Description</div>
														<div class="form-group">
															<textarea id="summernote1" rows="6" name="brief_description" placeholder="Brief Description" required></textarea>
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