@extends('admin.layouts.admin-inner')

@section('title',"Fitness App Admin Dashboard")

@section('breadcrumbs-title',"Add Accessorie")

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
						<li class="active"><a href="#description">Add Accessorie</a></li>
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div id="dropzone1" class="pro-ad addcoursepro">
											<form method="post" action="{{URL::to('/admin/add_accessorie')}}" enctype="multipart/form-data" class="">
												{{ csrf_field() }}
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">
															<input name="accessorie_title" type="text" class="form-control" placeholder="Accessorie Title" required>
														</div>
														
														<div class="form-group">
															<input name="sub_title" type="text" class="form-control" placeholder="Accessorie Sub Title" required>
														</div>
														
														<div class="form-group">
															<input name="amount" type="text" class="form-control" placeholder="Accessorie Amount" required>
														</div>
														
														<div class="form-group">
															<select name="per" class="form-control" required>
																<option value="">Select Per</option>
																<option value="gram">Gram</option>
																<option value="pound">Pound</option>
																<option value="kg">Kg</option>
																<option value="liter">Liter</option>
																<option value="piece">Piece</option>
															</select>
														</div>
														
														<div class="form-group alert-up-pd" style="clear:both;">
															<div style="">Cover Image</div>
															<input name="cover_image" class="" type="file" required />
														</div>
														
														<div class="form-group alert-up-pd">
															<div style="">Multiple Image</div>
															<input name="galary_image[]" class="" type="file" multiple required>
														</div>
													</div>
													
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group">Brief Description</div>
														<div class="form-group">
															<textarea id="summernote1" rows="6" name="brief_description" placeholder="Brief Description" required></textarea>
														</div>
														<div class="form-group">Full Description</div>
														<div class="form-group">
															<textarea id="summernote2" rows="6" name="full_description" placeholder="Full Description" required></textarea>
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