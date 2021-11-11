@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Gym Category Lists")

@section('container')
<?php //echo '<pre>'; print_r($gymCategory); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Gym Category List</h4>
					<div class="add-product">
						<a href="{{URL::to('/admin/add_gym_category')}}">Add Category</a>
					</div>
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Category Name</th>
									<th>Category Logo</th>
									<th>Subscription Title</th>
									<th>Subscription Amount</th>
									<th>Subscription Month</th>
									<th>Subscription Details</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($gymCategory as $key=>$category)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$category->category_name}}</td>
										<td><img src="{{URL::asset('assets/images/gym_cover_image/'.$category->category_image)}}" width="100"></td>
										<td>{{$category->subscription_title}}</td>
										<td>{{$category->subscription_amount}}</td>
										<td>{{$category->subscription_month}}</td>
										<td>{!!html_entity_decode($category->subscription_details)!!}</td>
										<td>
										<!--
											<button data-toggle="tooltip" title="View" class="pd-setting-ed">
												<a href="{{URL::to('/admin/gym_profile?id='.$category->id)}}" title="View Profile">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
											</button>-->
											<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
												<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
												<a href="{{URL::to('/admin/edit_gym_category?id='.$category->id)}}" title="View Profile">
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