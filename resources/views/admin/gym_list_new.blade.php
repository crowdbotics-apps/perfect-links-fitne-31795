@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Gym Lists")

@section('container')
<?php //echo '<pre>'; print_r($gymList); ?>	
<!--<div class="all-content-wrapper">-->
<div class="">
        <!-- Static Table Start -->
        <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Gym List</h1>
									<div class="add-product">
										<a href="{{URL::to('/admin/add_gym')}}">Add Gym</a>
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
                                                <th data-field="gym_title" data-editable="true">Gym Title</th>
                                                <th data-field="gym_category" data-editable="true">Gym Category</th>
                                                <th data-field="email" data-editable="true">Email</th>
                                                <th data-field="contact_number">Contact Number</th>
                                                <th data-field="gym_address" data-editable="true">Gym Address</th>
                                                <th data-field="setting">Setting</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										@foreach($gymList as $key=>$gym)
                                            <tr>
                                                <td></td>
                                                <td>{{$key+1}}</td>
                                                <td>{{$gym->gym_title}}</td>
                                                <td>{{$gym->category_name}}</td>
                                                <td>{{$gym->email}}</td>
                                                <td>{{$gym->country_code}}{{$gym->contact_number}}</td>
                                                <td>{{$gym->address}}</td>
                                               <td>
													<!--<button data-toggle="tooltip" title="View" class="pd-setting-ed" style="float:left;width: 43%;">
														<a href="{{URL::to('/admin/gym_profile?id='.$gym->user_id)}}" title="View Gym List">
															<i class="fa fa-eye" aria-hidden="true"></i>
														</a>
													</button>-->
													<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
														<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
														<a href="{{URL::to('/admin/edit_gym_profile?id='.$gym->user_id)}}" title="Edit Gym">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
														</a>
													</button>
												</td>
                                                
                                                <!--<td class="datatable-ct"><i class="fa fa-check"></i></td>-->
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
        <!-- Static Table End -->
	</div>

	
	  <!-- Chart JS
		============================================ -->
    <!--<script src="http://perfectlinkfitness.com/assets/admin/js/chart/jquery.peity.min.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/peity/peity-active.js"></script>-->
@endsection