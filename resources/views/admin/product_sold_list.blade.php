@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Sold Supplements Lists")

@section('container')
<?php //echo '<pre>'; print_r($soldProductList); ?>

<div class="product-status mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-status-wrap drp-lst">
					<h4>Sold Supplements List</h4> 
					
					<div class="asset-inner">
						<table id="lawyerTable" class="display" style="width:100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Supplements Image</th>
									<th>Supplements Category</th>
									<th>Title</th>
									<th>Sub Title</th>
									<th>Product Id</th>
									<th>Quantity</th>
									<th>Amount</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								@foreach($soldProductList as $key=>$product)
									<tr>
										<td style="text-align: center;">{{$key+1}}</td>
										<td><img src="{{URL::asset('assets/images/product_cover_image/'.$product->cover_image)}}" width="100"></td>
										<td>
											@if($product->product_category==1)
												Protein
											@elseif($product->product_category==2)
												Amino acids
											@elseif($product->product_category==3)
												Weight control
											@elseifif($product->product_category==4)
												Vitamins
											@elseifif($product->product_category==5)
												Performance
											@elseifif($product->product_category==6)
												Total life changes
											@elseifif($product->product_category==7)
												Other
											@else
											@endif
										</td>
										<td>{{$product->product_title}}</td>
										<td>{{$product->sub_title}}</td>
										<td>{{$product->product_id}}</td>
										<td>{{$product->quantity}}</td>
										<td>{{$product->sub_total}}</td>
										<td style="text-align: center;">
											<button data-toggle="tooltip" title="Edit" class="pd-setting-ed">
												<!--<i class="fa fa-trash-o" aria-hidden="true"></i>-->
												<a href="{{URL::to('/admin/edit_product?id='.$product->id)}}" title="View Product Details">
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