@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Lawyers")

@section('container')
<?php //echo '<pre>'; print_r($specialty); die;?>

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
			@if(!$lawyerInfo->isEmpty())
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="profile-info-inner">
					<div class="profile-img">
						<img src="{{ URL::asset('lawyers_profile_pic/'.$lawyerInfo[0]->profileImage) }}" alt="">
					</div>
					<div class="profile-details-hr">
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr">
									<p><b>Name</b><br> {{$lawyerInfo[0]->fullName}}</p>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr tb-sm-res-d-n dps-tb-ntn">
									<p><b>Company</b><br> {{$lawyerInfo[0]->companyName}}</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr">
									<p><b>Email ID</b><br> {{$lawyerInfo[0]->email}}</p>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr tb-sm-res-d-n dps-tb-ntn">
									<p><b>Phone</b><br> {{$lawyerInfo[0]->countryCode}} {{$lawyerInfo[0]->mobileNumber}}</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr">
									<p><b>Undergraduate School</b><br> {{$lawyerInfo[0]->undergraduateSchool}}</p>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr tb-sm-res-d-n dps-tb-ntn">
									<p><b>Graduate School</b><br> {{$lawyerInfo[0]->graduateSchool}}</p>
								</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr">
									<p><b>Bar Attorney</b><br> {{$lawyerInfo[0]->barAttorney}}</p>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
								<div class="address-hr tb-sm-res-d-n dps-tb-ntn">
									<p><b>Language</b><br> {{$lawyerInfo[0]->language}}</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="address-hr">
									<p><b>Address</b><br> {{$lawyerInfo[0]->mailingAddress}} , {{$lawyerInfo[0]->address1}} , {{$lawyerInfo[0]->address2}} , {{$lawyerInfo[0]->city}} , {{$lawyerInfo[0]->state}} , {{$lawyerInfo[0]->zipCode}}  </p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
								<div class="address-hr">
								</div>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<div class="address-hr">
						<!-- 1 is featured and 0 is not featured -->
						<form method="post" action="{{URL::to('/admin/update_featured_status')}}">
						 {{ csrf_field() }}
							@if($lawyerInfo[0]->featured==1)
								<input type="hidden" name="id" value="{{$lawyerInfo[0]->id}}">
								<input type="hidden" name="status" value="0">
									<button type="submit" class="btn btn-custon-four btn-success" style="margin-top: 13px;padding: 8px 44px;">Remove From Featured</button>
							@else	
								<input type="hidden" name="id" value="{{$lawyerInfo[0]->id}}">
								<input type="hidden" name="status" value="1">
									<button type="submit" class="btn btn-custon-four btn-warning" style="margin-top: 13px;padding: 8px 44px;">Add To Featured</button>
							@endif
						</form>
								</div>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
								<div class="address-hr">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="product-payment-inner-st res-mg-t-30 analysis-progrebar-ctn">
					<ul id="myTabedu1" class="tab-review-design">
						<li class="active"><a href="#description">About</a></li>
						<li><a href="#reviews"> Specialty </a></li>
						<!--<li><a href="#INFORMATION">Update Details</a></li>-->
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit st-prf-pro">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
											<div class="col-lg-12">
												<div class="content-profile">
													<p>{{$lawyerInfo[0]->briefBio}}</p>
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
										<div class="chat-discussion" style="height: auto">
											@foreach($specialty as $lawSpecilty)
											<div class="chat-message">
												<div class="message" style="margin-left:0;">
													<span class="message-content">{{$lawSpecilty}}</span>
												</div>
											</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@else
				<h1>No Result found</h1>
			@endif
		</div>
	</div>
</div>
@endsection