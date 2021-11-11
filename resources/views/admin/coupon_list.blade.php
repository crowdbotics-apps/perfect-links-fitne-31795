@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Coupon Lists")

@section('container')
<?php //echo '<pre>'; print_r($couponList); ?>
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
<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Coupon List</h4>
					<div class="add-product">
						<a href="{{URL::to('/admin/add_coupon')}}">Add Coupon</a>
					</div>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Coupon Title</th>
									<th>Coupon Code</th>
									<th>Coupon Type</th>
									<th>Coupon Category</th>
									<th>Description</th>
									<th>percentage</th>
									<th>amount</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($couponList as $key=>$coupon)
									<tr>
										<td style="text-align: center;">{{$key+1}}</td>
										<td>{{$coupon->title}}</td>
										<td>{{$coupon->coupon_code}}</td>
										<td>{{$coupon->coupon_type}}</td>
										<td>
											@if($coupon->coupon_category==1)
												Trainer
											@elseif($coupon->coupon_category==2)
												Gym
											@elseif($coupon->coupon_category==3)
												Supplement
											@elseif($coupon->coupon_category==4)
												Accessories
											@else
												All
											@endif
											</td>
										<td>{!!html_entity_decode($coupon->brief_description)!!}</td>
										<td style="text-align: center;">@if($coupon->percentage!=Null)
												{{$coupon->percentage}}%
											@else
												----
											@endif
										</td>
										<td style="text-align: center;">@if($coupon->amount!=Null)
												{{$coupon->amount}}
											@else
												----
											@endif</td>
										<td style="text-align: center;">
											
												<form method="post" action="{{URL::to('/admin/remove_coupon')}}">
													{{ csrf_field() }}
													<input name="id" type="hidden" class="form-control" value="{{ $coupon->id }}">
													<button type="submit" name="submit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
												</form>
											
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>

@endsection