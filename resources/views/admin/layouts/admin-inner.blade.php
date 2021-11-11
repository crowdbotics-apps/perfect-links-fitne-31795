<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/bootstrap.min.css') }}">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/font-awesome.min.css') }}">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/owl.transitions.css') }}">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/animate.css') }}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/normalize.css') }}">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/meanmenu.min.css') }}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/main.css') }}">
    <!-- educate icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/educate-custon-icon.css') }}">
    <!-- morrisjs CSS
		============================================ -->
    <!--<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/morrisjs/morris.css') }}">-->
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/metisMenu/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/metisMenu/metisMenu-vertical.css') }}">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/calendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/calendar/fullcalendar.print.min.css') }}">
   
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/alerts.css') }}">
	
	<!--Image upload box css-->
	<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/dropzone/dropzone.css') }}">
	 <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/style.css') }}">
	
	 <!-- summernote CSS
		============================================ -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/summernote/summernote.css') }}">
    <!-- modernizr JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/vendor/modernizr-2.8.3.min.js') }}"></script>
	
	<link rel="stylesheet" href="{{ URL::asset('assets/admin/jquery.dataTables.min.css') }}"> 
	
	
	<!--For Export data in list table-->
	 <!-- x-editor CSS
		============================================ -->
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/editor/select2.css">
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/editor/datetimepicker.css">
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/editor/bootstrap-editable.css">
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/editor/x-editor-style.css">

   <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="http://perfectlinkfitness.com/assets/admin/css/data-table/bootstrap-editable.css">
	
	
	<!--End For Export data in list table-->
	<!---->
	
	
<!--Multiple Select-->
<?php 
	$methodName =  Route::getCurrentRoute()->getName();
	if($user->role=='SUBADMIN' AND $methodName!='add_gym' AND $methodName!='gym_list' AND $methodName!='add_park' AND $methodName!='park_list' AND $methodName!='edit_gym_profile' AND $methodName!='edit_park'){
		header("Location: http://perfectlinkfitness.com/logout");
		exit;
	} 
?>
	<style>.removeGroup{color:#bb0f0f!important;font-size: 18px!important;}</style>
	
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>


    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Start Left menu area -->
@section('sidebar')
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href="{{URL::to('/dashboard')}}">
					<img class="main-logo" src="{{ URL::asset('assets/admin/images/logo.png') }}" style="height: 120px;" alt="Logo" />
				</a>
                <strong>
					<a href="index.html">
						<img src="{{ URL::asset('assets/admin/images/logo.png') }}" alt="" />
					</a>
				</strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
			<?php //echo $user->role; ?>
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1" style="height:900px;">
					@if($user->role!='SUBADMIN')
                        <li class="active">
                            <a class="has-arrow" href="{{URL::to('/admin/dashboard')}}">
								<span class="educate-icon educate-home icon-wrap"></span>
								<span class="mini-click-non">Dashboard</span>
							</a>
                        </li>
						<li>
                            <a class="has-arrow" title="Our Services" href="{{URL::to('/admin/trainer_wallet_request')}}" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Wallet Request</span>
							</a>
						</li>
						<li>
                            <a class="has-arrow" title="Our Services" href="{{URL::to('/admin/update_membership_rate')}}" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Trainer Membership</span>
							</a>
						</li>
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Sale Reports</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/product_sold')}}">
										<span class="mini-sub-pro">Supplements Sold</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/accessories_sold')}}">
										<span class="mini-sub-pro">Accessorie Sold</span>
									</a>
								</li>
                            </ul>
                        </li>
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Subscription Reports</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/trainer_subscritpion_list')}}">
										<span class="mini-sub-pro">Trainer Subscription</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/gym_subscritpion_list')}}">
										<span class="mini-sub-pro">Gym Subscription</span>
									</a>
								</li>
                            </ul>
                        </li>
						<li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Category</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li>
									<a title="All Gym" href="{{URL::to('/admin/add_gym_category')}}">
										<span class="mini-sub-pro">Add Gym Category</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/category_list')}}">
										<span class="mini-sub-pro">Gym Category List</span>
									</a>
								</li>
                            </ul>
                        </li>
						<li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Parks</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li>
									<a title="All Gym" href="{{URL::to('/admin/add_park')}}">
										<span class="mini-sub-pro">Add Park</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/park_list')}}">
										<span class="mini-sub-pro">Park List</span>
									</a>
								</li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Equipment</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
								<li>
									<a title="All Gym" href="{{URL::to('/admin/add_gym_equipments')}}">
										<span class="mini-sub-pro">Add Gym Equipment</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/equipment_list')}}">
										<span class="mini-sub-pro">Gym Equipment List</span>
									</a>
								</li>
                            </ul>
                        </li>
						
						<li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Gym</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li>
									<a title="All Gym" href="{{URL::to('/admin/add_gym')}}">
										<span class="mini-sub-pro">Add Gym</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/gym_list')}}">
										<span class="mini-sub-pro">Gym List</span>
									</a>
								</li>
                            </ul>
                        </li>
						
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Manage Coupon</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/add_coupon')}}">
										<span class="mini-sub-pro">Add Coupon</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/coupon_list')}}">
										<span class="mini-sub-pro">Coupon List</span>
									</a>
								</li>
                            </ul>
                        </li>						
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Manage Supplements</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/add_product')}}">
										<span class="mini-sub-pro">Add Supplements</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/product_list')}}">
										<span class="mini-sub-pro">Supplements List</span>
									</a>
								</li>
                            </ul>
                        </li>						
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Manage Accessories</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/add_accessorie')}}">
										<span class="mini-sub-pro">Add Accessorie</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/accessories_list')}}">
										<span class="mini-sub-pro">Accessorie List</span>
									</a>
								</li>
                            </ul>
                        </li>
						<!--
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Manage Services</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
                                <li>
									<a title="All Courses" href="{{URL::to('/admin/our_services')}}">
										<span class="mini-sub-pro">Add Service</span>
									</a>
								</li>
                                <li>
									<a title="Add Courses" href="{{URL::to('/admin/services_list')}}">
										<span class="mini-sub-pro">Services List</span>
									</a>
								</li>
                            </ul>
                        </li>
						-->
						<li>
                            <a class="has-arrow" title="Our Services" href="javascript:void(0)" aria-expanded="false">
								<span class="educate-icon educate-course icon-wrap"></span> 
								<span class="mini-click-non">Page Content</span>
							</a>
							<ul class="submenu-angle collapse" aria-expanded="true">
								<li>
									<a title="Add Courses" href="{{URL::to('/admin/add_page')}}">
										<span class="mini-sub-pro">Add Page</span>
									</a>
								</li> 
								@foreach($pagesName as $page)
							    <li>
									<a title="All Courses" href="{{URL::to('/admin/update_page?id='.$page->id)}}">
										<span class="mini-sub-pro">{{ $page->page_name }}</span>
									</a>
								</li>
								@endforeach
                            </ul>
                        </li>
					@else
						<!--If Sub Admin-->
						<li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Gym</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li>
									<a title="All Gym" href="{{URL::to('/admin/add_gym')}}">
										<span class="mini-sub-pro">Add Gym</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/gym_list')}}">
										<span class="mini-sub-pro">Gym List</span>
									</a>
								</li>
                            </ul>
                        </li>
						<li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
								<span class="educate-icon educate-student icon-wrap"></span> 
									<i class="fas fa-dumbbell"></i>
								<span class="mini-click-non">Manage Parks</span>
							</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li>
									<a title="All Gym" href="{{URL::to('/admin/add_park')}}">
										<span class="mini-sub-pro">Add Park</span>
									</a>
								</li>
								<li>
									<a title="All Gym" href="{{URL::to('/admin/park_list')}}">
										<span class="mini-sub-pro">Park List</span>
									</a>
								</li>
                            </ul>
                        </li>
					@endif		
                    </ul>

                </nav>
            </div>
        </nav>
    </div>
@show
	<div class="all-content-wrapper">
		<div class="header-advance-area">
@section('header')
			<div class="header-top-area">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="header-top-wraper">
								<div class="row">
									<div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
										<div class="menu-switcher-pro">
											<button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
												<i class="educate-icon educate-nav"></i>
											</button>
										</div>
									</div>
									<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
										<div class="header-top-menu tabl-d-n"></div>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
										<div class="header-right-info">
											<ul class="nav navbar-nav mai-top-nav header-right-menu">
												<li class="nav-item dropdown">
													<!--<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
														<i class="educate-icon educate-message edu-chat-pro" aria-hidden="true"></i>
														<span class="indicator-ms"></span>
													</a>-->
												</li>
											
												<li class="nav-item">
													<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
														<img src="{{ URL::asset('assets/admin/images/avater.jpg') }}" alt="" />
														<span class="admin-name">{{ $user->fullName }}</span>
														<i class="fa fa-angle-down edu-icon edu-down-arrow"></i>
													</a>
													<ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
														<!--
															<li>
																<a href="{{URL::to('/admin/my_profile')}}">
																<span class="edu-icon edu-user-rounded author-log-ic"></span>My Profile</a>
															</li>
															<li>
																<a href="{{URL::to('/admin/settings')}}">
																<span class="edu-icon edu-settings author-log-ic"></span>Settings</a>
															</li>
														-->
														<li>
															<!--
																<a href="{{URL::to('/admin/logout')}}">
																	<span class="edu-icon edu-locked author-log-ic"></span>Log Out
																</a>
															-->
															<a href="{{ route('logout') }}"
															   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
															   <span class="edu-icon edu-locked author-log-ic"></span>Logout
															</a>
															<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: one;">
																{{ csrf_field() }}
															</form>
														<!--
														<li><a href="#"><span class="edu-icon edu-home-admin author-log-ic"></span>My Account</a></li>
														<li><a href="#"><span class="edu-icon edu-money author-log-ic"></span>User Billing</a></li>
														-->
														</li>
													</ul>
												</li>
												<li class="nav-item nav-setting-open">
													<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
														<i class="educate-icon educate-menu"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
@show
		<!-- Mobile Menu start -->
		
		<!-- Mobile Menu end -->

		<div class="breadcome-area">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="breadcome-list">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="breadcome-heading">
									<!--<button onclick="goBack()" class="btn btn-success">Go Back</button>-->
										<!--
										<form role="search" class="sr-input-func">
											<input type="text" placeholder="Search..." class="search-int form-control">
											<a href="#"><i class="fa fa-search"></i></a>
										</form>-->
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									
									<ul class="breadcome-menu">
										<li><a href="#">Home</a> <span class="bread-slash">/</span></li>
										<li><span class="bread-blod">@yield('breadcrumbs-title')</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<!--@yield('breadcrumbs')-->

	</div>
@yield('container')

	<!---->
@section('footer')
	<div class="footer-copyright-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="footer-copy-right">
						<p>Copyright © 2018. All rights reserved.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@show
</div>


    <!-- jquery
		============================================ -->
<?php if($methodName!='lawyer_profile_edit'){ ?>
    <script src="{{ URL::asset('assets/admin/js/vendor/jquery-1.12.4.min.js') }}"></script>
<?php } ?>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
//$( document ).ready(function() {
	$( ".galImgRmv" ).click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure?')) {
			var id = $(this).attr('id');
			var productImgId = $("input[name=productId_"+id+"]").val();
			var type = $("input[name=type_"+id+"]").val();
			//alert(id);
			$.ajax({
				type:'POST',
				url:"{{ route('remove_product_accessorie_image.post') }}",
				data:{id:productImgId,type:type},
				success:function(data){
					console.log(data);
					console.log(data.success);
					if(data.success=='SUCCESS'){
						$("#formId_"+id).css("display", "none");
						$("#img__"+id).css("display", "none");
						$(this).css("display", "none");
					}
				},
				error: function (data, textStatus, errorThrown) {
					console.log(data);

				}
			});
		}
	});
//}); 
</script>		
	<!--<script src="{{ URL::asset('assets/admin/js/jquery-3.3.1.js') }}"></script>-->
    <script src="{{ URL::asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>

	
<?php if($methodName=='lawyer_profile_edit'){ ?>	
<!--Multiple select-->
<script type="text/javascript" src="{{ URL::asset('js/multiple-select.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>

<!--End Multiple select--> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/callender.js') }}"></script>
<!--banner-->
<script type="text/javascript">
$('#myCarousel.carousel').carousel({
	interval: 8000,
	pause: false,
	loop: true
})

$.datepicker.setDefaults({
	showOn: "button",
	buttonImage: "http://marlo.local/assets/images/datepicker_icon.png",
	buttonText: "",
	buttonImageOnly: true,
	changeMonth: true,
    changeYear: true,
	yearRange: "c-90:c+0",
});
$(function() {
	$( "#dob" ).datepicker();

});
</script>
 
<?php } ?>
<?php
	if($methodName=='add_gym' OR $methodName=='edit_gym_profile'){
?>	
	<script type="text/javascript" src="{{ URL::asset('assets/admin/js/jquery-1.10.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/admin/js/jquery.timepicker.js') }}"></script>
	<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/jquery.timepicker.css') }}">
	<script>
		$.noConflict();
		jQuery(document).ready(function($){
			$('input.timepicker').timepicker({ timeFormat: 'hh:mm a' });
		});
	</script>
<?php } ?>

<script>
$(function() {
    $('#monPlus').click(function(){
        var monDiv = $('<div class="form-group"><input name="monopen[]" type="text" class="form-control timepicker" placeholder="Monday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="monclose[]" type="text" class="form-control timepicker" placeholder="Monday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
	    //$('input.timepicker').timepicker({ timeFormat: 'hh:mm a' });
	    $('#monExt').append(monDiv);
	    //$('#monExt').append(monDiv).find('.timepicker').timepicker({ timeFormat: 'hh:mm a' });
    });    
	
	$('#tuesPlus').click(function(){
        var tuesDiv = $('<div class="form-group"><input name="tuesopen[]" type="text" class="form-control timepicker" placeholder="Tuesday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="tuesclose[]" type="text" class="form-control timepicker" placeholder="Tuesday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#tuesExt').append(tuesDiv);
    });
	
	$('#wedPlus').click(function(){
        var wedDiv = $('<div class="form-group"><input name="wedopen[]" type="text" class="form-control timepicker" placeholder="Wednesday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="wedclose[]" type="text" class="form-control timepicker" placeholder="Wednesday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#wedExt').append(wedDiv);
    });
	
	$('#thursPlus').click(function(){
        var thursDiv = $('<div class="form-group"><input name="thursopen[]" type="text" class="form-control timepicker" placeholder="Thursday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="thursclose[]" type="text" class="form-control timepicker" placeholder="Thursday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#thursExt').append(thursDiv);
    });
	$('#fridPlus').click(function(){
        var fridDiv = $('<div class="form-group"><input name="fridopen[]" type="text" class="form-control timepicker" placeholder="Friday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="fridclose[]" type="text" class="form-control timepicker" placeholder="Friday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#fridExt').append(fridDiv);
    });
	$('#satPlus').click(function(){
        var satDiv = $('<div class="form-group"><input name="satopen[]" type="text" class="form-control timepicker" placeholder="Saturday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="satclose[]" type="text" class="form-control timepicker" placeholder="Saturday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#satExt').append(satDiv);
    });
	
	$('#sundPlus').click(function(){
        var sunDiv = $('<div class="form-group"><input name="sundopen[]" type="text" class="form-control timepicker" placeholder="Sunday Open Time" style="width:40%;float:left;background-color: #f1c8c8;" required autocomplete="off"><input name="sundclose[]" type="text" class="form-control timepicker" placeholder="Sunday Close Time" style="width:40%;float:left;margin-left:10px;background-color: #f1c8c8;" required autocomplete="off"><div class="plusButton removeGroup" >X</div></div>');
       $('#sundExt').append(sunDiv);
    });
	
});

$(document).on("click", ".removeGroup", function() {
       $(this).parent('.form-group').remove(); 
});

function gymFormSubmit(){
	var chechBoxLength = $("#requredEqupment input[type='checkbox']:checked").length;
	if(chechBoxLength==0){
		alert("Please Select Equipments.");
		return false;
	}
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>	

	<script>
$(document).ready(function() {
    $('#lawyerTable').DataTable();
	//$( "#lawyerTable_filter input[type=search]" ).addClass( "search-int form-control" );
} );
</script>
<script>
function goBack() {
  window.history.back();
}
</script>	
    <!-- bootstrap JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <!-- wow JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/wow.min.js') }}"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/jquery-price-slider.js') }}"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/jquery.meanmenu.js') }}"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/owl.carousel.min.js') }}"></script>
    <!-- sticky JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/jquery.sticky.js') }}"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/jquery.scrollUp.min.js') }}"></script>
    <!-- counterup JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/counterup/waypoints.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/counterup/counterup-active.js') }}"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/scrollbar/mCustomScrollbar-active.js') }}"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/metisMenu/metisMenu-active.js') }}"></script>
    <!-- morrisjs JS
		============================================ -->
    <!--<script src="{{ URL::asset('assets/admin/js/morrisjs/raphael-min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/morrisjs/morris.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/morrisjs/morris-active.js') }}"></script>-->
    <!-- morrisjs JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/sparkline/jquery.charts-sparkline.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/sparkline/sparkline-active.js') }}"></script>
    <!-- calendar JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/calendar/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/calendar/fullcalendar-active.js') }}"></script>
    <!-- plugins JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/plugins.js') }}"></script>
    <!-- main JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/main.js') }}"></script>

	    <!-- dropzone JS
		============================================ -->
       <script src="{{ URL::asset('assets/admin/js/dropzone/dropzone.js') }}"></script>
	   
	   <!-- summernote JS
		============================================ -->
    <script src="{{ URL::asset('assets/admin/js/summernote/summernote.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/summernote/summernote-active.js') }}"></script>
	
	
	
	
	
	<!-- jquery	============================================ -->
    <!--<script src="http://perfectlinkfitness.com/assets/admin/js/vendor/jquery-1.12.4.min.js"></script>-->
    <!--<script src="http://perfectlinkfitness.com/assets/admin/js/bootstrap.min.js"></script>-->
 <!-- data table JS
		============================================ -->
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/bootstrap-table.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/tableExport.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/data-table-active.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/bootstrap-table-editable.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/bootstrap-editable.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/bootstrap-table-resizable.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/colResizable-1.5.source.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/data-table/bootstrap-table-export.js"></script>
    <!--  editable JS
		============================================ -->
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/jquery.mockjax.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/mock-active.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/select2.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/moment.min.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/bootstrap-datetimepicker.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/bootstrap-editable.js"></script>
    <script src="http://perfectlinkfitness.com/assets/admin/js/editable/xediable-active.js"></script>
	
	
	
	   
    <!-- tawk chat JS
		============================================ -->
    <!--<script src="{{ URL::asset('assets/admin/js/tawk-chat.js') }}"></script>-->
</body>

</html>