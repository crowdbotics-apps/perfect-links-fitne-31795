@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Trainer Subscription List")

@section('container')
<?php //echo '<pre>'; print_r($soldAccessorieList); ?>
<style>
.fixed-table-loading{display:none;}
</style>
 <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd" style="height: 50px;">
                                <div class="main-sparkline13-hd">
                                    <h1>Trainer Subscription List</h1>
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
                                                <th data-field="no">No.</th>
                                                <th data-field="trainer_image">Trainer Image</th>
                                                <th data-field="transaction_id" data-editable="true">Transaction Id</th>
                                                <th data-field="trainer_name">Trainer Name</th>
                                                <th data-field="days">Days</th>
                                                <th data-field="start_date" data-editable="true">Start Date</th>
                                                <th data-field="end_date" data-editable="true">End Date</th>
                                                <th data-field="amount" data-editable="true">Amount</th>
                                                <th data-field="payment_date" data-editable="true">Payment Date</th>
                                                <!--<th data-field="setting">Setting</th>-->
                                            </tr>
                                        </thead>
								<tbody>
								@foreach($subscriptionList as $key=>$subscription)
									<tr>
										<td></td>
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