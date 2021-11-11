@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Subscription List")

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
                                    <h1>Subscription List</h1>
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
                                                <th data-field="user_name">User Name</th>
                                                <th data-field="user_id">User Id</th>
                                                <th data-field="order_id" data-editable="true">Order Id</th>
                                                <th data-field="days">Days</th>
                                                <th data-field="start_date" data-editable="true">Start Date</th>
                                                <th data-field="total_amount" data-editable="true">Total Amount</th>
                                                <th data-field="received_amount" data-editable="true">Received Amount</th>
                                                <th data-field="trainer_amount" data-editable="true">Trainer Amount</th>
                                                <th data-field="discount" data-editable="true">Discount</th>
                                                <th data-field="payment_date" data-editable="true">Payment Date</th>
                                            </tr>
                                        </thead>
								<tbody>
								@foreach($subscriptionList as $key=>$subscription)
									<tr>
										<td></td>
										<td style="text-align: center;">{{$key+1}}</td>
										<td style="text-align: center;">{{$subscription->name}}</td>
										<td>{{$subscription->user_id}}</td>
										<td>{{$subscription->order_id}}</td>
										<td style="text-align: center;">{{$subscription->months}}</td>
										<td>{{$subscription->start_date}}</td>
										<td style="text-align: center;">${{$subscription->membership_amount}}</td>
										<td>${{$subscription->total}}</td>
										<td>${{(75 / 100) * $subscription->membership_amount}}</td>
										<td style="text-align: center;">
											@if($subscription->discounted_amount!='')
												${{$subscription->discounted_amount}}
											@else 
												{{0}}
											@endif
										</td>
										<td>{{$subscription->created_at}}</td>
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
<!---->
@endsection