@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Equipment Lists")

@section('container')
<?php //echo '<pre>'; print_r($gymEquipmets); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Equipment List</h4>
					<div class="add-product">
						<a href="{{URL::to('/admin/add_gym_equipments')}}">Add Equipment</a>
					</div>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Equipment Type</th>
									<th>Equipment Tame</th>
									<th>Equipment Image</th>
									<!--<th>Description</th>-->
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($gymEquipmets as $key=>$equipmet)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$equipmet->equipment_type}}</td>
										<td>{{$equipmet->equipment_name}}</td>
										<td>{{$equipmet->equipment_image}}</td>
										<!--<td>{{$equipmet->description}}</td>-->
										<td>
											<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
												<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
												<a href="{{URL::to('/admin/edit_equipment?id='.$equipmet->id)}}" title="View Profile">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</a>
											</button>
										</td>
									</tr>
								@endforeach
							</tbody>
							<!--
							<tfoot>
								<tr>
									<th>No.</th>
									<th>Lawyer Name</th>
									<th>Company Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Setting</th>
								</tr>
							</tfoot> -->                    
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