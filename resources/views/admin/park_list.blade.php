@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Park Lists")

@section('container')
<?php //echo '<pre>'; print_r($parkLists); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Gym List</h4>
					<div class="add-product">
						<a href="{{URL::to('/admin/add_park')}}">Add Park</a>
					</div>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Park Name</th>
									<th>Park Type</th>
									<th>Park Image</th>
									<th>Park Address</th>
									<th>Park Latitude</th>
									<th>Park Longitude</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($parkLists as $key=>$park)
									<tr>
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
										<td><img src="/assets/images/parks/{{$park->park_image}}" width="" style=""></td>
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
					<!--
					<div class="custom-pagination">
						<nav aria-label="Page navigation example">
							<ul class="pagination">
								<li class="page-item"><a class="page-link" href="#">Previous</a></li>
								<li class="page-item"><a class="page-link" href="#">1</a></li>
								<li class="page-item"><a class="page-link" href="#">2</a></li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item"><a class="page-link" href="#">Next</a></li>
							</ul>
						</nav>
					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>

@endsection