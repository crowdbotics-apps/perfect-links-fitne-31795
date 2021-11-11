@extends('admin.layouts.admin-inner')

@section('title',"Trainer Wallet Request")

@section('breadcrumbs-title',"Trainer Wallet Request")

@section('container')
<?php //echo '<pre>'; print_r($walletRequest); 

?>
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
					<h4>Trainer Wallet Request</h4>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Trainer Name</th>
									<th>Email</th>
									<th>Number</th>
									<th>Category</th>
									<th>Total Amount</th>
									<th>Trainer Amount</th>
									<th>Discount</th>
									<th>Requested Date</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($walletRequest as $key=>$request)
									<?php 
										$requestDateTime = strtotime($request->requested_date); 
										$dateTime = explode(' ', $request->requested_date); 
									?>
									@if($request->status==2)
										<tr style="background: skyblue;">
									@else
										<tr>
									@endif
										<td style="text-align: center;">{{$key+1}}</td>
										
										<td style="text-align: center;">{{$request->name}}</td>
										<td style="text-align: center;">{{$request->email}}</td>
										<td style="text-align: center;">{{$request->country_code}}{{$request->phone_number}}</td>
										<td style="text-align: center;">
											@if($request->trainer_category==1)
												Free Run
											@elseif($request->trainer_category==2)
												Home Service
											@elseif($request->trainer_category==3)
												Park Link
											@else
											@endif
										</td>
										<td style="text-align: center;">${{$request->total_amount}}</td>
										<td style="text-align: center;">${{$request->requested_amount}}</td>
										<td style="text-align: center;">
											@if($request->discount_amount!='')
												${{$request->discount_amount}}
											@else 
												{{0}}
											@endif
										</td>
										<td style="text-align: center;">{{$dateTime[0]}}</br>{{ $dateTime[1]}}</td>
										<td style="text-align: center;">
<div id="refeRenceNumber{{$key+1}}" style="display:none;">
<b style="width: 100%;text-align: center;float: left;padding: 0px 5px 24px;">Paypal Id:  {{$request->paypal_id}}</b>
@if($request->payment_info=='')
	<form method="post" action="{{URL::to('/admin/add_trainer_payment_details')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Payment Details</div>
			<input type="hidden" class="form-control" name="trainer_id" value="{{$request->trainer_id}}" required>
			<input type="hidden" class="form-control" name="request_id" value="{{$request->id}}" required>
			<input type="hidden" class="form-control" name="request_date_time" value="{{$request->requested_date}}" required>
			<div class="col-sm-7"><input type="text" class="form-control" name="payment_info" value="" required></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Payment Image</div>
			<div class="col-sm-7"><input type="file" class="form-control" name="payment_image" value=""></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Submited Date</div>
			<div class="col-sm-7"><input type="text" class="form-control" name="payment_date" value="" required></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-11"><button type="submit" class="btn btn-primary waves-effect waves-light" name="submit" style="float:right;">Submit</button></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
	</form>
@else
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Payment Details</div>
			<div class="col-sm-7">{{$request->payment_info}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Payment Image</div>
			<div class="col-sm-7"><img src="{{URL::asset('assets/images/'.$request->payment_image)}}" width="300"></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Payment Date</div>
			<div class="col-sm-7">{{$request->payment_date}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
@endif
</div>
											<button data-toggle="tooltip" title="Subscription Deatils" class="pd-setting-ed">
							<a href="{{URL::to('/admin/wallet_subscription_details/'.$request->trainer_id.'/'.$requestDateTime)}}" title="Subscription Deatils">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
											</button>
											@if($request->status==1)
											<button data-toggle="tooltip" title="Add Payment Details" class="pd-setting-ed">
												<a href="" title="Add Payment Details" data-toggle="modal" data-target="#addPaymentDetailsModal" onclick="addPaymentDetails({{$key+1}})">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>
											@else
											<button data-toggle="tooltip" title="Payment Details" class="pd-setting-ed">
												<a href="" title="Payment Details" data-toggle="modal" data-target="#getPaymentDetailsModal" onclick="getPaymentDetails({{$key+1}})">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>
											@endif
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
<!--Model Popup-->
<div class="modal fade" id="addressModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Subscription Deatils</h4>
			</div>
			<div class="modal-body" id="ShippingAddress">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addPaymentDetailsModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Payment Details</h4>
			</div>
			<div class="modal-body" id="AddPaymentDetails">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="getPaymentDetailsModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Payment Details</h4>
			</div>
			<div class="modal-body" id="PaymentDetails">

			</div>
		</div>
	</div>
</div>
<script>
function getPaymentDetails(id){
	var referenceRenceNumber = document.getElementById("refeRenceNumber"+id).innerHTML;
	document.getElementById("PaymentDetails").innerHTML = referenceRenceNumber;
}

function addPaymentDetails(id){
	var referenceRenceNumber = document.getElementById("refeRenceNumber"+id).innerHTML;
	document.getElementById("AddPaymentDetails").innerHTML = referenceRenceNumber;
}

</script>
<!---->
@endsection