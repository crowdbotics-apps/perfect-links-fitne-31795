@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Banner List")

@section('container')
<?php //echo '<pre>'; print_r($banners); ?>
<div class="contacts-area mg-b-15">
	<div class="container-fluid">
		<div class="row">
		@foreach($banners as $banner)
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<div class="student-inner-std res-mg-b-30">
					<div class="student-img">
						<img src="{{ URL::asset('/assets/images/banner_image/'.$banner->image) }}" alt="">
					</div>
					<div class="student-dtl">
						<h2>{!!html_entity_decode($banner->title)!!}</h2>
						<p class="dp">{!!html_entity_decode($banner->brief_description)!!}</p>
						<p class="dp">
							<a href="{{URL::to('/admin/update_banner?id='.$banner->id)}}" title="Update Banner">
								<button type="button" class="btn btn-custon-four btn-primary" style="margin-top: 13px;padding: 8px 44px;">Update Banner</button>
							</a>
						</p>
					</div>
				</div> 
			</div>
		@endforeach
			<!--
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<div class="student-inner-std res-mg-b-30">
					<div class="student-img">
						<img src="img/student/2.jpg" alt="">
					</div>
					<div class="student-dtl">
						<h2>Alexam Angles</h2>
						<p class="dp">Computer Science</p>
						<p class="dp-ag"><b>Age:</b> 20 Years</p>
					</div>
				</div>
			</div>-->		
		</div>
	</div>
</div>
@endsection