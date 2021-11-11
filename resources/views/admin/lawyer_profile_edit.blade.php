@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Lawyers")

@section('container')
<?php //echo '<pre>'; print_r($specialty); die;?>

<div class="single-pro-review-area mt-t-30 mg-b-15">
	<div class="container-fluid">
		<div class="row">
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
		@if(!$lawyerInfo->isEmpty())
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="product-payment-inner-st res-mg-t-30 analysis-progrebar-ctn">
					<ul id="myTabedu1" class="tab-review-design">
						<li class="active"><a href="#description">Profile</a></li>
						<!--<li><a href="#reviews"> Specialty </a></li>-->
						<!--<li><a href="#INFORMATION">Update Details</a></li>-->
					</ul>
					<div id="myTabContent" class="tab-content custom-product-edit st-prf-pro">
						<div class="product-tab-list tab-pane fade active in" id="description">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="row">
											<div class="col-lg-12" style="padding-left:40px; padding-right:40px;">
												<div class="content-profile">
													<!---->
<form name="form1" method="post" class="form1" action="{{URL::to('/formUpdateSubmit')}}" enctype="multipart/form-data">
					{{csrf_field()}}
					<div class="form-signup">
						<div class="col-sm-6">
							 <div class="file-upload">
									<div class="file-upload">
										<div class="drag-text">
										   <h3>Upload photo</h3>
										</div>
										<div class="document-upload-wrap">
											<input class="file-upload-input form-control" type='file' id="file" name="profileImage" onchange="readURL(this);" accept="document/*" />
										</div>
										<!--
										<div class="file-upload-content">
											<img class="file-upload-document" src="javascript:void(0);" alt="File Upload is Successfull" height="150px" width="150px" />
											<div class="document-title-wrap"></div>
										</div>-->
									</div>
									@if ($errors->has('profileImage'))
										<div class="form-error">{{ $errors->first('profileImage') }}</div>
									@endif
							</div>
						</div>
					</div>
					<div class="form-signup">
						<div class="col-sm-4"> 
							<label for="name">Name:</label>
							<input type="text" class="form-contact" id="name" name="fullName" value="{{ old('fullName') }}" placeholder="Full Name" required> 
							@if ($errors->has('fullName'))
								<div class="form-error">{{ $errors->first('fullName') }}</div>
							@endif
						</div>
						<div class="col-sm-4"> 
							<label for="name">Date Admitted:</label>
							<input type="text" class="form-contact" id="dob" name="dob" value="{{ old('dob') }}" placeholder="Date Admitted" required> 
							@if ($errors->has('dob'))
								<div class="form-error">{{ $errors->first('dob') }}</div>
							@endif
						</div>

						<div class="col-sm-4"> 
							<label for="name">Company Name (if any):</label>
							<input type="text" class="form-contact" id="cname" name="companyName" value="{{ old('companyName') }}" placeholder="Company Name (if any)"> 
							@if ($errors->has('companyName'))
								<div class="form-error">{{ $errors->first('companyName') }}</div>
							@endif
						</div>
					</div>

					<div class="form-signup">
						<div class="col-sm-4"> 
							<label for="name">Email address:</label>
							<input type="email" class="form-contact" id="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
							@if ($errors->has('email'))
								<div class="form-error">{{ $errors->first('email') }}</div>
							@endif
						</div>
						<div class="col-sm-4"> 
							<label for="name">Country Code #:</label>
							
							<!--	
								<input list="brow" class="form-contact" id="countryCode" name="countryCode" required>
								<datalist id="brow">
									<option value="">Select Country Code..</option>
									@foreach ($countries as $country)
										<option value="{{ $country->code }}" {{ old("countryCode") == "$country->code" ? "selected" : "" }}>{{ $country->name }}</option>
									@endforeach
								</datalist> 
							-->
							
							
								<select id="countryCode" class="form-contact"name="countryCode">
									<option value="">Select Country Code..</option>
									@foreach ($countries as $country)
										<option value="{{ $country->code }}" {{ old("countryCode") == "$country->code" ? "selected" : "" }}>{{ $country->code }} {{ $country->name }}</option>
									@endforeach
								</select>
							
							@if ($errors->has('countryCode'))
								<div class="form-error">{{ $errors->first('countryCode') }}</div>
							@endif							
						</div>
						<div class="col-sm-4"> 
							<label for="name">Mobile #:</label>
							<input type="text" class="form-contact" id="mobile" name="mobileNumber" value="{{ old('mobileNumber') }}" placeholder="Mobile #" required> 
							@if ($errors->has('mobileNumber'))
								<div class="form-error">{{ $errors->first('mobileNumber') }}</div>
							@endif
						</div>						
					</div>

					<div class="form-signup">
						<div class="col-sm-4"> 
							<label for="name">Specialty:</label>
							</br>
							<div class="form-contact-0">
								<!--<select id="speciality" name="speciality" multiple="multiple">-->
								<select id="select-multiple-specialty" name="speciality[]" class="form-contact" multiple=multiple >
									@foreach ($allSpeciality as $speciality)
										<option value="{{ $speciality->id }}" {{ old("speciality") == "$speciality->id" ? "selected" : "" }}>{{ $speciality->speciality_name }}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('speciality'))
								<div class="form-error">{{ $errors->first('speciality') }}</div>
							@endif
						</div>
						<!--
						<div class="col-sm-4">
							<label for="name">Mailing address:</label>
							<input type="text" class="form-contact" id="maddress" name="mailingAddress" value="{{ old('mailingAddress') }}" placeholder="Mailing address" required> 
							@if ($errors->has('mailingAddress'))
								<div class="form-error">{{ $errors->first('mailingAddress') }}</div>
							@endif
						</div>-->
						<div class="col-sm-4">
							<label for="name">Bar Attorney#:</label>
							<input type="text" class="form-contact" id="barAttorney" name="barAttorney" value="{{ old('barAttorney') }}" placeholder="Bar Attorney#">
							@if ($errors->has('barAttorney'))
								<div class="form-error">{{ $errors->first('barAttorney') }}</div>
							@endif
						</div>
							<div class="col-sm-4">
							<label for="name">Language: </label>
							<input type="text" class="form-contact" id="language" name="language" value="{{ old('language') }}" placeholder="Language" required> 
							@if ($errors->has('language'))
								<div class="form-error">{{ $errors->first('language') }}</div>
							@endif
						</div>
						
					</div>

					<div class="form-signup">
						<div class="col-sm-4">
							<label for="name">Address 1:</label>
							<input type="text" class="form-contact" id="address1" name="address1" value="{{ old('address1') }}" placeholder="Address 1" required>
							@if ($errors->has('address1'))
								<div class="form-error">{{ $errors->first('address1') }}</div>
							@endif
						</div>
						<div class="col-sm-4">
							<label for="name">Address 2:</label>
							<input type="text" class="form-contact" id="address2" name="address2" value="{{ old('address2') }}" placeholder="Address 2">
							@if ($errors->has('address2'))
								<div class="form-error">{{ $errors->first('address2') }}</div>
							@endif
						</div>
						<div class="col-sm-4">
							<label for="name">City:</label>
							<input type="text" class="form-contact" id="city" name="city" value="{{ old('city') }}" placeholder="City" required> 
							@if ($errors->has('city'))
								<div class="form-error">{{ $errors->first('city') }}</div>
							@endif
						</div>
						
						
					</div>

					<div class="form-signup">
						<div class="col-sm-4">
							<label for="name">State:</label>
							<input type="text" class="form-contact" id="state" name="state" value="{{ old('state') }}" placeholder="State" required>
							@if ($errors->has('state'))
								<div class="form-error">{{ $errors->first('state') }}</div>
							@endif
						</div>
						<div class="col-sm-4">
							<label for="name">Zip code:</label>
							<input type="text" class="form-contact" id="zip" name="zipCode" value="{{ old('zipCode') }}" placeholder="Zip code" required>
							@if ($errors->has('zipCode'))
								<div class="form-error">{{ $errors->first('zipCode') }}</div>
							@endif
						</div>
						<div class="col-sm-4">
							<label for="name">Undergraduate School: </label>
							<input type="text" class="form-contact" id="uschool" name="undergraduateSchool" value="{{ old('undergraduateSchool') }}" placeholder="Undergraduate School" required> 
							@if ($errors->has('undergraduateSchool'))
								<div class="form-error">{{ $errors->first('undergraduateSchool') }}</div>
							@endif
						</div>
						
					</div>

					<div class="form-signup">
						<div class="col-sm-4">
							<label for="name">Graduate School:</label>
							<input type="text" class="form-contact" id="gschool" name="graduateSchool" value="{{ old('graduateSchool') }}" placeholder="Graduate School" required>
							@if ($errors->has('graduateSchool'))
								<div class="form-error">{{ $errors->first('graduateSchool') }}</div>
							@endif
						</div>
					
						<div class="col-sm-4">
							<label for="name">Brief Bio:</label>
							<input type="text" class="form-contact" id="briefbio" name="briefBio" value="{{ old('briefBio') }}" placeholder="Brief Bio" required>
							@if ($errors->has('briefBio'))
								<div class="form-error">{{ $errors->first('briefBio') }}</div>
							@endif
						</div>
					</div>
					<div class="form-signup">
						<div class="col-sm-6">
							<label for="name">Consultation Fee:</label>
							<input type="text" class="form-contact" id="consultation" name="consultationFee" value="{{ old('consultationFee') }}" placeholder="(enter amount here)" required>
							@if ($errors->has('consultationFee'))
								<div class="form-error">{{ $errors->first('consultationFee') }}</div>
							@endif
						</div>
						<div class="col-sm-6">
							<label for="name">Retainer fee:</label>
							<input type="text" class="form-contact" id="retainer" name="retainerFee" value="{{ old('retainerFee') }}" placeholder="(enter amount here)" required>
							@if ($errors->has('retainerFee'))
								<div class="form-error">{{ $errors->first('retainerFee') }}</div>
							@endif
						</div>
					</div>
					<div class="form-signup">
						<div class="col-sm-6">
							<div class="file-upload">
								<div class="drag-text">
								   <h3>Upload Certificate</h3>
								</div>
								<div class="document-upload-wrap">
									<input class="file-upload-input form-control" type="file" id="file2" name="certificateImage" {{ old('certificateImage') }} accept="document/*" required />
								</div>
							</div>
							@if ($errors->has('certificateImage'))
								<div class="form-error">{{ $errors->first('certificateImage') }}</div>
							@endif
						</div>	 
						<div class="col-sm-6">	
							<div class="file-upload">
								<div class="drag-text">
								   <h3>Upload License</h3>
								</div>
								<div class="document-upload-wrap">
									<input class="file-upload-input form-control" type='file' id="file3" name="licenseImage" {{ old('licenseImage') }} accept="document/*" required />
								</div>
							</div>
							@if ($errors->has('licenseImage'))
								<div class="form-error">{{ $errors->first('licenseImage') }}</div>
							@endif
						</div>
					</div>
					
					<div class="form-signup">
						<div class="col-sm-12">
							<label for="name">Paypal Email address:<br>
								In order to receive payments directly from clients, please enter your paypal email address associated with your Paypal account
							</label>
							<input type="email" class="form-contact" id="paypal" name="paypalEmail" value="{{ old('paypalEmail') }}" placeholder="Paypal Email address" required> 
						</div>
					</div>

					<input class="submit-signup" value="Submit" type="submit">
					<p class="form-status1"></p>          
				</form>
													<!---->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Tab 2
						<div class="product-tab-list tab-pane fade" id="reviews">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="review-content-section">
										<div class="chat-discussion" style="height: auto">
											@foreach($specialty as $lawSpecilty)
											<div class="chat-message">
												<div class="message" style="margin-left:0;">
													<span class="message-content">{{$lawSpecilty}}</span>
												</div>
											</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
			@else
				<h1>No Result found</h1>
			@endif
		</div>
	</div>
</div>
@endsection