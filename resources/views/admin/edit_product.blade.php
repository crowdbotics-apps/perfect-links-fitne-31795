@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Add Product")

@section('container')
<style>
.plusButton{    
    width: 17%;
    float: left;
    color: #18520afc;
    padding: 2px;
    text-align: center;
    font-size: 27px;
    font-weight: bold;
	cursor:pointer;
}
</style>
<?php //echo '<pre>';print_r($gym_category); ?>
@if(session('alert-success'))
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="col-lg-3 col-md-12 col-sm-12 col-xs-3"></div>
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
		<div class="alert alert-success alert-st-one" role="alert">
			<i class="fa fa-check edu-checked-pro admin-check-pro" aria-hidden="true"></i>
			<p class="message-mg-rt"><strong>{{session('alert-success')}}</strong></p>
		</div>
	</div>
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-3"></div>
</div>
@endif

<div class="single-pro-review-area mt-t-30 mg-b-15">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-payment-inner-st">
					<ul id="myTabedu1" class="tab-review-design">
						<li class="active"><a href="#description">Add Product</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/edit_product')}}" enctype="multipart/form-data" class="">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
														<select name="product_category" class="form-control" required>
															<option value="">Select Product Category</option>
															<option {{ $product->product_category==1 ? 'selected':'' }} value="1">Protein</option>
															<option {{ $product->product_category==2 ? 'selected':'' }} value="2">Amino acids</option>
															<option {{ $product->product_category==3 ? 'selected':'' }} value="3">Weight control</option>
															<option {{ $product->product_category==4 ? 'selected':'' }} value="4">Vitamins</option>
															<option {{ $product->product_category==5 ? 'selected':'' }} value="5">Performance</option>
															<option {{ $product->product_category==6 ? 'selected':'' }} value="6">Total life changes</option>
															<option {{ $product->product_category==7 ? 'selected':'' }} value="7">Other</option>
														</select>
														</div>
														<div class="form-group">
															<input name="id" type="hidden" class="form-control" value="{{ $product->id }}">
															<input name="product_id" type="hidden" class="form-control" value="{{ $product->product_id }}">
															<input name="product_title" type="text" value="{{ $product->product_title }}" class="form-control" placeholder="Product Title" required>
														</div>
														
														<div class="form-group">
															<input name="sub_title" type="text" value="{{ $product->sub_title }}" class="form-control" placeholder="Product Sub Title" required>
														</div>
														
														<div class="form-group">
															<input name="amount" type="text" value="{{ $product->amount }}" class="form-control" placeholder="Product Amount" required>
														</div>
														
														<div class="form-group">
															<select name="per" class="form-control" required>
																<option value="">Select Per</option>
																<option {{ $product->per=='gram' ? 'selected':'' }} value="gram">Gram</option>
																<option {{ $product->per=='pound' ? 'selected':'' }} value="pound">Pound</option>
																<option {{ $product->per=='kg' ? 'selected':'' }} value="kg">Kg</option>
																<option{{ $product->per=='liter' ? 'selected':'' }}  value="liter">Liter</option>
																<option {{ $product->per=='piece' ? 'selected':'' }} value="piece">Piece</option>
															</select>
														</div>
														
														<div class="form-group alert-up-pd" style="clear:both;">
															<div style="">Cover Image</div>
															<input name="cover_image" class="" type="file" />
														</div>
														
														<div class="form-group alert-up-pd">
															<div style="">Multiple Image</div>
															<input name="galary_image[]" class="" type="file" multiple>
														</div>
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Brief Description</div>
														<div class="form-group">
															<textarea id="summernote1" rows="6" name="brief_description" placeholder="Brief Description" required>{{ $product->brief_description }}</textarea>
														</div>
														<div class="form-group">Full Description</div>
														<div class="form-group">
															<textarea id="summernote2" rows="6" name="full_description" placeholder="Full Description" required>{{ $product->full_description }}</textarea>
														</div>
													</div>
											
												</div>
												<div class="row">
													<div class="col-lg-12">
														<div class="payment-adress">
															<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
														</div>
													</div>
												</div>
											</form>
<div class="form-group alert-up-pd" style="position: absolute; margin-top: -345px;">
	<div style="margin-top:30px;">
		@foreach($product->galary_image as $key=>$galaryImage)
			<form style="float:left; margin-right:10px;" id="formId_{{$galaryImage->id}}">
				<img src="{{$galaryImage->image}}" width="100px" height="100px" id="img__{{$galaryImage->id}}">
				<input type="hidden" name="productId_{{$galaryImage->id}}" value="{{$galaryImage->id}}">
				<input type="hidden" name="type_{{$galaryImage->id}}" value="PRODUCT">
				<button class="galImgRmv" id="{{$galaryImage->id}}" >&#10005;</button>
			</form>
		@endforeach
	</div>
</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!---->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection