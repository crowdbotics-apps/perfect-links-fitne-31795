@extends('admin.layouts.admin-inner')
@section('title',"Fitness Admin Dashboard")
@section('breadcrumbs-title',"Dashboard")
@section('container')
	<div class="analytics-sparkle-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 res-mg-t-30 table-mg-t-pro-n tb-sm-res-d-n dk-res-t-d-n">
						<a href="{{URL::to('/admin/product_sold')}}">
							<h3 class="box-title">Total Sold Supplements</h3>
							<ul class="list-inline two-part-sp">
								<li>
									<div id="sparklinedash4"></div>
								</li>
								<li class="text-right sp-cn-r"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-success">{{$sold_product}}</span></li>
							</ul>
						</a>
					</div>
				</div>				
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 res-mg-t-30 table-mg-t-pro-n tb-sm-res-d-n dk-res-t-d-n">
					<a href="{{URL::to('/admin/accessories_sold')}}">
						<h3 class="box-title">Total Sold Accessories</h3>
						<ul class="list-inline two-part-sp">
							<li>
								<div id="sparklinedash3"></div>
							</li>
							<li class="text-right sp-cn-r"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-success">{{$sold_accessories}}</span></li>
						</ul>
					</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 res-mg-t-30 table-mg-t-pro-n tb-sm-res-d-n dk-res-t-d-n">
					<a href="{{URL::to('/admin/gym_list')}}">
						<h3 class="box-title">Total Registered Gym</h3>
						<ul class="list-inline two-part-sp">
							<li>
								<div id="sparklinedash"></div>
							</li>
							<li class="text-right sp-cn-r"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-success">{{$registered_gym}}</span></li>
						</ul>
					</a>
					</div>
				</div>
				<!--<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 tb-sm-res-d-n dk-res-t-d-n">
					<a href="{{URL::to('/admin/park_list')}}">
						<h3 class="box-title">Total Registered Parks</h3>
						<ul class="list-inline two-part-sp">
							<li>
								<div id="sparklinedash3"></div>
							</li>
							<li class="text-right graph-two-ctn"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-purple"></span></li>
						</ul>
					</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 tb-sm-res-d-n dk-res-t-d-n">
					<a href="{{URL::to('/admin/')}}">
						<h3 class="box-title">Total Registered Users</h3>
						<ul class="list-inline two-part-sp">
							<li>
								<div id="sparklinedash3"></div>
							</li>
							<li class="text-right graph-two-ctn"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-purple">{{$registered_user}}</span></li>
						</ul>
					</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="white-box analytics-info-cs mg-b-10 res-mg-b-30 tb-sm-res-d-n dk-res-t-d-n">
					<a href="{{URL::to('/admin/')}}">
						<h3 class="box-title">Total Registered Trainer</h3>
						<ul class="list-inline two-part-sp">
							<li>
								<div id="sparklinedash2"></div>
							</li>
							<li class="text-right graph-two-ctn"><i class="fa fa-level-up" aria-hidden="true"></i> <span class="counter text-purple">{{$registered_trainer}}</span></li>
						</ul>
					</a>
					</div>
				</div>-->
			
				<!--
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="analytics-sparkle-line reso-mg-b-30">
						<div class="analytics-content">
							<h5></h5>
							<h2>$<span class="counter">5000</span> <span class="tuition-fees">Tuition Fees</span></h2>
							<span class="text-success">20%</span>
							<div class="progress m-b-0">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:20%;"> <span class="sr-only">20% Complete</span> </div>
							</div>
						</div>
					</div>
				</div>-->
	
			</div>
		</div>
	</div>
	
	<div class="product-sales-area mg-tb-30">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="product-sales-chart">
						<div class="portlet-title">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="caption pro-sl-hd">
										<span class="caption-subject"><b></b></span>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="actions graph-rp graph-rp-dl">
										
									</div>
								</div>
							</div>
						</div>
						<div id="extra-area-chart" style="height: 356px;"></div>
					</div>
				</div>
				<!--
					  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					
					
					
				</div>-->
			</div>
		</div>
	</div>
@endsection