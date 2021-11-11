@extends('admin.layouts.admin-inner')

@section('title',"Marlo Subscription")

@section('breadcrumbs-title',"Subscription List")

@section('container')

<?php //echo '<pre>'; print_r($lawyers); ?>
<div class="contacts-area mg-b-15">
	<div class="container-fluid">
		<div class="row">
		@foreach($subscriptions as $subscription)
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<div class="student-inner-std res-mg-b-30">
					<h3 style="padding-top: 35px;">{{$subscription->name}}</h3>
					<div class="student-img" style="padding: 30px 30px 0px 30px;font-size: 28px;font-weight: bold;">
						$ {{$subscription->amount}}
					</div>
					<div class="student-dtl">
						
						<p class="dp" style="padding: 14px;font-size: 19px;">{{$subscription->title}}</p>
						<p class="dp">
							<a href="{{URL::to('/admin/update_subscription?id='.$subscription->id)}}" title="View Profile">
								<button type="button" class="btn btn-custon-four btn-primary" style="margin-top: 13px;padding: 8px 44px;">Edit</button>
							</a>
						</p>
					</div>
				</div> 
			</div>
		@endforeach
		</div>
		<div class="row" style="margin-top:180px;"></div>
	</div>
</div>
@endsection