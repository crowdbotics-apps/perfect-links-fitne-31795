@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Trainer Subscription List")

@section('container')
<?php //echo '<pre>'; print_r($soldAccessorieList); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Trainer Subscription List</h4>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Trainer Image</th>
									<th>Transaction Id</th>
									<th>Trainer Name</th>
									<th>Months</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>Amount</th>
									<th>Payment Date</th>
									<!--<th>Setting</th>-->
								</tr>
							</thead>
							<tbody>
								@foreach($subscriptionList as $key=>$subscription)
									<tr>
										<td style="text-align: center;">{{$key+1}}</td>
										<td style="text-align: center;"><img src="{{URL::asset($subscription->profile_picture)}}" width="100"></td>
										<td>{{$subscription->transaction_id}}</td>
										<td>{{$subscription->name}}</td>
										<td style="text-align: center;">{{$subscription->months}}</td>
										<td>{{$subscription->start_date}}</td>
										<td style="text-align: center;">{{$subscription->end_date}}</td>
										<td>{{$subscription->currency}} {{$subscription->sub_total}}</td>
										<td>{{$subscription->created_at}}</td>
										<!--
										<td style="text-align: center;">
											<button data-toggle="tooltip" title="Shipping Address" class="pd-setting-ed">
												<a href="" title="View Product Details" data-toggle="modal" data-target="#addressModal" onclick="changeAddress({{$key+1}})">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
											</button>
											<button data-toggle="tooltip" title="Add Shipping Details" class="pd-setting-ed">
												<a href="" title="Add Shipping Details" data-toggle="modal" data-target="#shippingDetailsModal" onclick="addShippingDetails({{$key+1}})">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>
										</td>-->
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
<!--Model Popup-->
<div class="modal fade" id="addressModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Shipping Address</h4>
			</div>
			<div class="modal-body" id="ShippingAddress">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="shippingDetailsModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Shipping Details</h4>
			</div>
			<div class="modal-body" id="ShippingDetails">

			</div>
		</div>
	</div>
</div>
<script>
function addShippingDetails(id){
	var referenceRenceNumber = document.getElementById("refeRenceNumber"+id).innerHTML;
	document.getElementById("ShippingDetails").innerHTML = referenceRenceNumber;
}

function changeAddress(id){
	var shippAdderss = document.getElementById("address"+id).innerHTML;
	document.getElementById("ShippingAddress").innerHTML = shippAdderss;
}
</script>
<!---->
@endsection