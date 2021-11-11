@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Gym Category")

@section('container')
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
						<li class="active"><a href="#description">Add Gym Category</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/add_gym_category')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="category_name" type="text" class="form-control" placeholder="Category Name" required>
														</div>
														<div class="form-group">
															<input name="subscription_title" type="text" class="form-control" placeholder="Subscription Title">
														</div>
														<div class="form-group">
															<input name="subscription_amount" type="text" class="form-control" placeholder="Subscription Amount">
														</div>
														<div class="form-group">
															<input name="subscription_month" type="text" class="form-control" placeholder="Subscription Time Period">
														</div>
														<div class="form-group alert-up-pd">
															<input name="category_image" class="" type="file" required />
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Subscription Description</div>
														<div class="form-group">
															<!--<textarea name="full_description" placeholder="Full Description"></textarea>-->
															<textarea id="summernote2" rows="6" name="subscription_details" placeholder="Subscription Description"></textarea>
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
				</div>
			</div>
		</div>
	</div>
</div>
@endsection