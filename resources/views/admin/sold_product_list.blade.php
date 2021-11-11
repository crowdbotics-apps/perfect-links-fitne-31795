@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Sold Supplements Lists")

@section('container')
<?php //echo '<pre>'; print_r($soldProductList); ?>
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
					<h4>Sold Supplements List</h4>
					
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Product Image</th>
									<th>Transaction Id</th>
									<th>Category</th>
									<th>Title</th>
									<th>Sub Title</th>
									<th>Product Id</th>
									<th>Quantity</th>
									<th>Amount</th>
									<th>Payment Date</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($soldProductList as $key=>$product)
								@if(!empty($product->tracking_details))
									<tr style="background: skyblue;">
								@else
									<tr>
								@endif
										<td style="text-align: center;">{{$key+1}}</td>
										<td style="text-align: center;"><img src="{{URL::asset('assets/images/product_cover_image/'.$product->cover_image)}}" width="100"></td>
										<td>{{$product->transaction_id}}</td>
										<td>
											@if($product->product_category==1)
												Protein
											@elseif($product->product_category==2)
												Amino acids
											@elseif($product->product_category==3)
												Weight control
											@elseifif($product->product_category==4)
												Vitamins
											@elseifif($product->product_category==5)
												Performance
											@elseifif($product->product_category==6)
												Total life changes
											@elseifif($product->product_category==7)
												Other
											@else
											@endif
										</td>
										<td>{{$product->product_title}}</td>
										<td>{{$product->sub_title}}</td>
										<td>{{$product->product_id}}</td>
										<td style="text-align: center;">{{$product->quantity}}</td>
										<td>{{$product->currency}} {{$product->sub_total}}</td>
										<td>{{$product->created_at}}</td> 
										<!--<td>{{$product->country_code}}</td>-->
										<td style="text-align: center;">
<div id="address{{$key+1}}" style="display:none;">
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Recipient Name</b></div>
		<div class="col-sm-7">{{$product->recipient_name}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>City</b></div>
		<div class="col-sm-7">{{$product->city}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>State</b></div>
		<div class="col-sm-7">{{$product->state}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Postal Code</b></div>
		<div class="col-sm-7">{{$product->postal_code}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Contact Number</b></div>
		<div class="col-sm-7">{{$product->country_code}} {{$product->phone_number}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Land Mark</b></div>
		<div class="col-sm-7">{{$product->land_mark}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3">&nbsp;</div>
		<div class="col-sm-7">{{$product->line1}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3">&nbsp;</div>
		<div class="col-sm-7">{{$product->line2}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
</div>
<?php //echo '<pre>'; print_r($product->tracking_details); ?>
<div id="refeRenceNumber{{$key+1}}" style="display:none;">
@if(empty($product->tracking_details))
	<form method="post" action="{{URL::to('/admin/add_tracking_details')}}">
		{{ csrf_field() }}
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Vendor Name</div>
			<div class="col-sm-7">
				<input type="hidden" class="form-control" name="order_id" value="{{$product->order_id}}" required>
				<input type="hidden" class="form-control" name="user_id" value="{{$product->user_id}}" required>
				<input type="hidden" class="form-control" name="product_type" value="{{$product->product_type}}" required>
				<input type="text" class="form-control" name="vendor_name" value="" required>
			</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Tracking Id</div>
			<div class="col-sm-7"><input type="text" class="form-control" name="tracking_id" value="" required></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Tracking Url</div>
			<div class="col-sm-7"><input type="text" class="form-control" name="tracking_url" value="" required></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Submited Date</div>
			<div class="col-sm-7"><input type="text" class="form-control" name="submited_date" value="" required></div>
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
			<div class="col-sm-3">Vendor Name</div>
			<div class="col-sm-7">{{$product->tracking_details['vendor_name']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Tracking Id</div>
			<div class="col-sm-7">{{$product->tracking_details['tracking_id']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Tracking Url</div>
			<div class="col-sm-7">{{$product->tracking_details['tracking_url']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Submited Date</div>
			<div class="col-sm-7">{{$product->tracking_details['submited_date']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
@endif
</div>
											<button data-toggle="tooltip" title="Shipping Address" class="pd-setting-ed">
												<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
												<!--<a href="{{URL::to('/admin/edit_product?id='.$product->id)}}" title="View Product Details">-->
												<a href="" title="View Product Details" data-toggle="modal" data-target="#addressModal" onclick="changeAddress({{$key+1}})">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
											</button>
											@if(empty($product->tracking_details))
											<button data-toggle="tooltip" title="Add Shipping Details" class="pd-setting-ed">
												<a href="" title="Add Shipping Details" data-toggle="modal" data-target="#shippingDetailsModal" onclick="addShippingDetails({{$key+1}})">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>	
											@else
											<button data-toggle="tooltip" title="Tracking Details" class="pd-setting-ed">
												<a href="" title="Tracking Details" data-toggle="modal" data-target="#TrackingDetailsModal" onclick="getTrackingDetails({{$key+1}})">
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
<div class="modal fade" id="TrackingDetailsModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tracking Details</h4>
			</div>
			<div class="modal-body" id="TrackingDetails">

			</div>
		</div>
	</div>
</div>
<script>
function getTrackingDetails(id){
	var referenceRenceNumber = document.getElementById("refeRenceNumber"+id).innerHTML;
	document.getElementById("TrackingDetails").innerHTML = referenceRenceNumber;
}
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