@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Gym Subscription List")

@section('container')
<?php //echo '<pre>'; print_r($soldAccessorieList); ?>
<?php //echo '<pre>'; print_r($gym_category); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Gym Subscription List</h4>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Cover Image</th>
									<th>Transaction Id</th>
									<th>Gym Category</th>
									<th>Gym Title</th>
									<th>Months</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>Amount</th>
									<th>Payment Date</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($subscriptionList as $key=>$subscription)
									<tr>
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