@extends('admin.layouts.admin-inner')

@section('title',"Trainer Membership Rate")

@section('breadcrumbs-title',"Trainer Membership Rate")

@section('container')
<?php // print_r($subscription); ?>
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
						<li class="active"><a href="#description">Trainer Membership Rate</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/update_membership_rate')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Single User Rate</div>
														<div class="form-group">
															<input type="text" name="single_rate" value="{{ $subscription->single_rate }}" class="form-control" placeholder="Single User Rate">
														</div>
														<div class="form-group">Group User Rate</div>
														<div class="form-group">
															<input type="text" name="group_rate" value="{{ $subscription->group_rate }}" class="form-control" placeholder="Group User Rate">
														</div>
														<div class="form-group">Group Count</div>
														<div class="form-group">
															<input type="text" name="group_count" value="{{ $subscription->group_count }}" class="form-control" placeholder="Group Count">
														</div>
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