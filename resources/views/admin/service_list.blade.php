@extends('admin.layouts.admin-inner')

@section('title',"Marlo Admin Dashboard")

@section('breadcrumbs-title',"Services List")

@section('container')
<?php //echo '<pre>'; print_r($services); ?>
<div class="contacts-area mg-b-15">
	<div class="container-fluid">
		<div class="row">
		@foreach($services as $service)
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="height:805px;">
				<div class="student-inner-std res-mg-b-30">
					<div class="student-img">
						<img src="{{ URL::asset('/assets/images/our_services/'.$service->service_image) }}" alt="">
					</div>
					<div class="student-dtl">
						<h2>{!!html_entity_decode($service->service_name)!!}</h2>
						<p class="dp">{!!html_entity_decode($service->about_service)!!}</p>
						<p class="dp">
							<a href="{{URL::to('/admin/update_service?id='.$service->id)}}" title="Update Service">
								<button type="button" class="btn btn-custon-four btn-primary" style="margin-top: 13px;padding: 8px 44px;">Update Service</button>
							</a>
						</p>
					</div>
				</div> 
			</div>
		@endforeach
				
		</div>
	</div>
</div>
@endsection