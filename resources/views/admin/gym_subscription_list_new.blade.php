@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Gym Subscription List")

@section('container')
<?php //echo '<pre>'; print_r($soldAccessorieList); ?>
<?php //echo '<pre>'; print_r($gym_category); ?>
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
                                    <h1>Gym Subscription List</h1>
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
                                                <th data-field="cover_image">Cover Image</th>
                                                <th data-field="transaction_id" data-editable="true">Transaction Id</th>
                                                <th data-field="gym_category">Gym Category</th>
                                                <th data-field="gym_title">Gym Title</th>
                                                <th data-field="months">Months</th>
                                                <th data-field="start_date" data-editable="true">Start Date</th>
                                                <th data-field="end_date" data-editable="true">End Date</th>
                                                <th data-field="amount" data-editable="true">Amount</th>
                                                <th data-field="payment_date" data-editable="true">Payment Date</th>
                                                <th data-field="setting">Setting</th>
                                            </tr>										
                                        </thead>
									<tbody>
								@foreach($subscriptionList as $key=>$subscription)
									<tr>
										<td></td>
										<td style="text-align: center;">{{$key+1}}</td>
										<td style="text-align: center;"><img src="{{URL::asset($subscription->cover_image)}}" width="100"></td>
										<td>{{$subscription->transaction_id}}</td>
										<td style="text-align: center;">
										@foreach($gym_category as $key=>$category)
											@if($category->id==$subscription->gym_category)
												{{$category->category_name}}
											@endif
										@endforeach
										</td>
										<td>{{$subscription->gym_title}}</td>
										<td style="text-align: center;">{{$subscription->months}}</td>
										<td>{{$subscription->start_date}}</td>
										<td style="text-align: center;">{{$subscription->end_date}}</td>
										<td>{{$subscription->currency}} {{$subscription->sub_total}}</td>
										<td>{{$subscription->created_at}}</td>
										<td style="text-align: center;">
<div id="userIdRference{{$key+1}}" style="display:none;">
	<form method="post">
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-1">&nbsp;</div>
			<div class="col-sm-3" style="margin-top:10px;">User Id</div>
			<div class="col-sm-7">
				<input type="hidden" class="form-control" name="order_id" value="{{$subscription->order_id}}" required>
				<input type="text" class="form-control" name="vendor_name" value="" required>
			</div>
			<div class="col-sm-1">&nbsp;</div>
		</div>	
		<div class="row" style="margin-bottom:10px;">
			<div class="col-sm-11"><button type="submit" class="btn btn-primary waves-effect waves-light" name="submit" id="shippDetailsSubmit" style="float:right;">Submit</button></div>
			<div class="col-sm-1">&nbsp;</div>
		</div>
	</form>
</div>
											<button data-toggle="tooltip" title="Add User Id" class="pd-setting-ed">
												<a href="" title="Add User Id" data-toggle="modal" data-target="#userIdModal" onclick="addId({{$key+1}})">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>
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
<div class="modal fade" id="userIdModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add User Id</h4>
			</div>
			<div class="modal-body" id="GyimUserId">

			</div>
		</div>
	</div>
</div>
<script>
function addId(id){
	var userIdRference = document.getElementById("userIdRference"+id).innerHTML;
	document.getElementById("GyimUserId").innerHTML = userIdRference;
}

</script>
<!---->
@endsection