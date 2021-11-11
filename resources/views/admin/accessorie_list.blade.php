@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Accessorie Lists")

@section('container')
<?php //echo '<pre>'; print_r($accessorieList); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Accessorie List</h4>
					<div class="add-product">
						<a href="{{URL::to('/admin/add_accessorie')}}">Add Accessorie</a>
					</div>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Accessorie Image</th>
									<th>Accessorie Title</th>
									<th>Accessorie Id</th>
									<th>Amount</th>
									<th>Per</th>
									<th>Description</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($accessorieList as $key=>$accessorie)
									<tr>
										<td style="text-align: center;">{{$key+1}}</td>
										<td><img src="{{URL::asset('assets/images/accessorie_cover_image/'.$accessorie->cover_image)}}" width="100"></td>
										<td>{{$accessorie->accessorie_title}}</td>
										<td>{{$accessorie->accessorie_id}}</td>
										<td>{{$accessorie->amount}}</td>
										<td>{{$accessorie->per}}</td>
										<td>{!!html_entity_decode($accessorie->brief_description)!!}</td>
										
										<td style="text-align: center;">
											<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
												<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
												<a href="{{URL::to('/admin/edit_accessorie?id='.$accessorie->id)}}" title="View Product Details">
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

@endsection