@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Park Lists")

@section('container')
<?php //echo '<pre>'; print_r($parkLists); ?>
<style>
.fixed-table-loading{display:none;}
</style>
        <!-- Static Table Start -->
        <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd" style="height: 50px;">
                                <div class="main-sparkline13-hd">
                                    <h1>Park List</h1>
									<div class="add-product">
										<a href="{{URL::to('/admin/add_park')}}">Add Park</a>
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
                                                <th data-field="no">No.</th>
                                                <th data-field="park_name" data-editable="true">Park Name</th>
                                                <th data-field="park_type" data-editable="true">Park Type</th>
                                                <th data-field="park_image">Park Image</th>
                                                <th data-field="park_address">Park Address</th>
                                                <th data-field="park_latitude" data-editable="true">Park Latitude</th>
                                                <th data-field="park_longitude" data-editable="true">Park Longitude</th>
                                                <th data-field="setting">Setting</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										@foreach($parkLists as $key=>$park)
											<tr>
											 <td></td>
												<td>{{$key+1}}</td>
												<td>{{$park->park_name}}</td>
												<td>
													@if($park->park_type==1)
														Park Link
													@elseif($park->park_type==2)
														Free Run
													@elseif($park->park_type==3)
														Both
													@else
													@endif
												</td>
												<td><img src="/assets/images/parks/{{$park->park_image}}" width="100" style=""></td>
												<td>{{$park->park_address}}</td>
												<td>{{$park->lat}}</td>
												<td>{{$park->lon}}</td>
												<td>
													<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
														<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
														<a href="{{URL::to('/admin/edit_park?id='.$park->id)}}" title="Edit Park">
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
@endsection