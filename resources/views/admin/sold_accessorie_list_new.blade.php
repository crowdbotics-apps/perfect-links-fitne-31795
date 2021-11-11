@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Sold Accessories Lists")

@section('container')
<?php //echo '<pre>'; print_r($soldAccessorieList); ?>
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

 <!-- Static Table Start -->
        <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                               	<div class="sparkline13-hd">
									<div class="main-sparkline13-hd">
										<h1>Sold Accessories List</h1>
									</div>
								</div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <div id="toolbar">
                                        <select class="form-control dt-tb">
											<option value="">Export Basic</option>
											<option value="all">Export All</option>
											<option value="selected">Export Selected</option>
										</select>
                                    </div>
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="state" data-checkbox="true"></th>
                                                <th data-field="id">No.</th>
                                                <th data-field="accessorie_image">Accessorie Image</th>
                                                <th data-field="transaction_id" data-editable="true">Transaction Id</th>
                                                <th data-field="accessorie_title" data-editable="true">Accessorie Title</th>
                                                <th data-field="accessorie_id">Accessorie Id</th>
                                                <th data-field="quantity" data-editable="true">Quantity</th>
                                                <th data-field="amount" data-editable="true">Amount</th>
                                                <th data-field="payment_date" data-editable="true">Payment Date</th>
                                                <th data-field="setting">Setting</th>
                                            </tr>
                                        </thead>
                                       <tbody>
								@foreach($soldAccessorieList as $key=>$accessorie)
										
									@if(!empty($accessorie->tracking_details))
										<tr style="background: skyblue;">
									@else
										<tr>
									@endif
										<td></td>
										<td style="text-align: center;">{{$key+1}}</td>
										<td style="text-align: center;"><img src="{{URL::asset('assets/images/accessorie_cover_image/'.$accessorie->cover_image)}}" width="100"></td>
										<td>{{$accessorie->transaction_id}}</td>
										<td>{{$accessorie->accessorie_title}}</td>
										<td>{{$accessorie->accessorie_id}}</td>
										<td style="text-align: center;">{{$accessorie->quantity}}</td>
										<td>{{$accessorie->currency}} {{$accessorie->sub_total}}</td>
										<td>{{$accessorie->created_at}}</td>
										
										<td style="text-align: center;">
<div id="address{{$key+1}}" style="display:none;">
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Recipient Name</b></div>
		<div class="col-sm-7">{{$accessorie->recipient_name}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>City</b></div>
		<div class="col-sm-7">{{$accessorie->city}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>State</b></div>
		<div class="col-sm-7">{{$accessorie->state}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Postal Code</b></div>
		<div class="col-sm-7">{{$accessorie->postal_code}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Contact Number</b></div>
		<div class="col-sm-7">{{$accessorie->country_code}} {{$accessorie->phone_number}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><b>Land Mark</b></div>
		<div class="col-sm-7">{{$accessorie->land_mark}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3">&nbsp;</div>
		<div class="col-sm-7">{{$accessorie->line1}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
	<div class="row" style="margin-bottom:10px;">
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3">&nbsp;</div>
		<div class="col-sm-7">{{$accessorie->line2}}</div>
		<div class="col-sm-1">&nbsp;</div>
	</div>
</div>
<div id="refeRenceNumber{{$key+1}}" style="display:none;">
@if(empty($accessorie->tracking_details))
	<form method="post" action="{{URL::to('/admin/add_tracking_details')}}">
	{{ csrf_field() }}
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top: 10px;">Vendor Name</div>
			<div class="col-sm-7">
				<input type="hidden" class="form-control" name="order_id" value="{{$accessorie->order_id}}" required>
				<input type="hidden" class="form-control" name="user_id" value="{{$accessorie->user_id}}" required>
				<input type="hidden" class="form-control" name="product_type" value="{{$accessorie->product_type}}" required>
				<input type="hidden" class="form-control" name="accessorie_id" value="{{$accessorie->accessorie_id}}" required>
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
			<div class="col-sm-11">
			<button type="submit" title="{{$accessorie->order_id}}" class="btn btn-primary waves-effect waves-light" id="shippDetailsSubmit{{$key+1}}" name="submit" style="float:right;"">Submit</button></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
	</form>
@else
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Vendor Name</div>
			<div class="col-sm-7">{{$accessorie->tracking_details['vendor_name']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Tracking Id</div>
			<div class="col-sm-7">{{$accessorie->tracking_details['tracking_id']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Tracking Url</div>
			<div class="col-sm-7">{{$accessorie->tracking_details['tracking_url']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3">Submited Date</div>
			<div class="col-sm-7">{{$accessorie->tracking_details['submited_date']}}</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
@endif
</div>
<!--created_at  onclick="submitShippingDetails({{$key+1}})-->
											<button data-toggle="tooltip" title="Shipping Address" class="pd-setting-ed">
												<a href="" title="View Product Details" data-toggle="modal" data-target="#addressModal" onclick="changeAddress({{$key+1}})">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
											</button>
											@if(empty($accessorie->tracking_details))
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