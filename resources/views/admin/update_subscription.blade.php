@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Update Subscription")

@section('container')
<?php //echo '<pre>'; print_r($subscriptionInfo); ?>
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
						<li class="active"><a href="#description">Subscription ({{$subscriptionInfo->name}})</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/update_subscription')}}" enctype="multipart/form-data" class="" id="demo1-upload">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Subscription Name</div>
														<div class="form-group">
															<input name="name" type="text" class="form-control" value="{{$subscriptionInfo->name}}" placeholder="Name">
															<input name="id" type="hidden" class="form-control" value="{{$subscriptionInfo->id}}">
														</div>
														<div class="form-group">Title</div>
														<div class="form-group">
															<input name="title" type="text" class="form-control" value="{{$subscriptionInfo->title}}" placeholder="Title">
														</div>
														<div class="form-group">Amount</div>
														<div class="form-group">
															<input name="amount" type="text" class="form-control" value="{{$subscriptionInfo->amount}}" placeholder="Subscription Amount">
														</div>
														
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Description</div>
														<div class="form-group">
															<!--<textarea name="brief_description" placeholder="Brief Description"></textarea>-->
															<textarea id="summernote1" rows="6" name="description" placeholder="Description">{{$subscriptionInfo->description}}</textarea>
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