<?php

namespace App\Http\Controllers;

#namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Auth;
use DB;
use Session;
class AdminController extends Controller
{   
    
	const PROFILE_IMAGE_PATH = '/uploads/profile_pic/';
	const PROFILE_IMAGE_FULL_PATH = '/uploads/profile_pic/';
	const EQUIPMENTS_IMAGE_FULL_PATH = '/assets/images/equipments/';
	const COVER_IMAGE_FULL_PATH = '/assets/images/gym_cover_image/';
	const GALARY_IMAGE_FULL_PATH = '/assets/images/gym_galary_image/';
	const TRAINER_VIDEO_FULL_PATH = '/uploads/trainer_video/';
	const TRAINER_GALARY_IMAGE_FULL_PATH = '/uploads/trainer_galary_image/';
	
	const ACCESSORIES_COVER_IMAGE_FULL_PATH = '/assets/images/accessorie_cover_image/';
	const ACCESSORIES_GALARY_IMAGE_FULL_PATH = '/assets/images/accessorie_gallery_image/';
	const PRODUCT_COVER_IMAGE_FULL_PATH = '/assets/images/product_cover_image/';
	const PRODUCT_GALARY_IMAGE_FULL_PATH = '/assets/images/product_gallery_image/';
	const PARK_IMAGE_FULL_PATH = '/assets/images/parks/';
	const PAYMENT_TRAINER_IMAGE_FULL_PATH = '/assets/images/';
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		$nom=10;
		$registered_gym = DB::table('gym_users')->count();
		$registered_user = DB::table('users')->where(['user_type'=>2])->count();
		$registered_trainer = DB::table('users')->where(['user_type'=>3])->count();
		$sold_product = DB::table('payments')->where(['product_type'=>'PRODUCT'])->count();
		$sold_accessories = DB::table('payments')->where(['product_type'=>'ACCESSORIES'])->count();
		$registered_parks = DB::table('parks')->count();
        return view('admin/dashboard',[
			'user'=>$user,
			'pagesName'=>$pagesName,
			'registered_gym'=>$registered_gym,
			'registered_user'=>$registered_user,
			'registered_trainer'=>$registered_trainer,
			'parks'=>$registered_parks,
			'sold_accessories'=>$sold_accessories,
			'sold_product'=>$sold_product
			
			]);
		
 
    }
	public function count_from_table(){ 
		$registered_gym = DB::table('gym_users')->count();
		$registered_user = DB::table('users')->where(['user_type'=>2])->count();
		$registered_trainer = DB::table('users')->where(['user_type'=>3])->count();
		return array (
			'registered_gym'=>$registered_gym,
			'registered_user'=>$registered_user,
			'registered_trainer'=>$registered_trainer
			);
	}
	static function pageContent(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'page_name' => 'required',
				'title' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'image' => 'image|mimes:jpeg,png,jpg|max:20489900',
			]);
		
			$image = $request->file('image');
			if(!empty($image)){
				$validatedData['image'] = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/page_img');
				$image->move($destinationPath, $validatedData['image']);
			} else {
				$validatedData['image'] = '';
			}
			$validatedData['created_at'] = date('Y-m-d h:m:i'); 
			
			$id = DB::table('pages')->insertGetId($validatedData);
					
			if($id){
				return redirect('admin/add_page')->with('alert-success', 'Pages added successfully.');
			} else {
				return redirect('admin/add_page')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else{
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_page', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	
	public function updateContent(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'id'=> 'required',
				'title' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'image' => 'image|mimes:jpeg,png,jpg|max:20489900',
			]);
			$image = $request->file('image');
			if(!empty($image)){
				$validatedData['image'] = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/page_img');
				$image->move($destinationPath, $validatedData['image']);
			}
			
			$validatedData['brief_description'] =htmlentities($request->brief_description); 
			$validatedData['full_description'] = htmlentities($request->full_description); 
			$id = DB::table('pages')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/update_page?id='.$request->id)->with('alert-success', 'Page Updated successfully.');
			} else {
				return redirect('admin/update_page?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$pageInfo = DB::table('pages')->where(['id'=>$request->id])->get();
			return view('admin/update_page', ['user'=>$user,'page'=>$pageInfo,'pagesName'=>$pagesName]);
		}
	}
	
	public function ourServices(Request $request){
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		return view('admin/add_services', ['user'=>$user,'pagesName'=>$pagesName]);
	}
	public function servicesList(Request $request){
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		$services = DB::table('services')->get();
		
			/* $id = DB::table('subscriptions')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/update_subscription?id='.$request->id)->with('alert-success', 'Subscription Updated successfully.');
			} else {
				return redirect('admin/update_subscription?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			} */
		return view('admin/service_list', ['user'=>$user,'pagesName'=>$pagesName,'services'=>$services]);
	}
	public function updateServices(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'id'=> 'required',
				'service_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'service_name' => 'required|max:255',
				'about_service' => ''
			]);
			$image = $request->file('service_image');
			if(!empty($image)){
				$validatedData['service_image'] = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/our_services');
				$image->move($destinationPath, $validatedData['service_image']);
			}
			
			$validatedData['service_name'] =htmlentities($request->service_name); 
			$validatedData['about_service'] = htmlentities($request->about_service); 
			$id = DB::table('services')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/update_service?id='.$request->id)->with('alert-success', 'Service Updated successfully.');
			} else {
				return redirect('admin/update_service?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$servicesInfo = DB::table('services')->where(['id'=>$request->id])->first();
			return view('admin/update_service', ['user'=>$user,'services'=>$servicesInfo,'pagesName'=>$pagesName]);
		}
	}
	public function addServices(Request $request){
		
	$validatedData =$this->validate($request,[
				'service_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'service_name' => 'required|max:255',
				'about_service' => ''
			]
		);
		
		$serviceImage = $request->file('service_image');
        $validatedData['image'] = time().'.'.$serviceImage->getClientOriginalExtension();
        $destinationPath = public_path('/assets/images/our_services');
        $serviceImage->move($destinationPath, $validatedData['image']);
		$validatedData['created_at'] = date('Y-m-d h:m:i'); 
		
		$id = DB::table('services')->insertGetId($validatedData);
				
		if($id){
			return redirect('admin/our_services')->with('alert-success', 'Service added successfully.');
		} else {
			return redirect('admin/our_services')->with('alert-failure', 'There is network error. Please try again.');
		}
	}
	public function addGymCategory(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'category_name'        => 'required|max:255',
				'subscription_title'   => '',
				'subscription_amount'  => '',
				'subscription_month'   => '',
				'subscription_details' => '',
				'category_image'       => ''
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			
			$image = $request->file('category_image');
			if(!empty($image)){
				$validatedData['category_image'] = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/gym_cover_image'); 
				$image->move($destinationPath, $validatedData['category_image']);
			}
			
			$id = DB::table('gym_category')->insertGetId($validatedData);
			if($id){
				return redirect('admin/add_gym_category')->with('alert-success', 'Gym Category added successfully.');
			} else {
				return redirect('admin/add_gym_category')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_gym_category', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	public function gymCategoryLists(){
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		return view('admin/gym_category_list', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'gymCategory'=>$gym_category
				]);
	}
	public function editGymCategory(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'id'=> 'required',
				'category_name'        => 'required|max:255',
				'subscription_title'   => '',
				'subscription_amount'  => '',
				'subscription_month'   => '',
				'subscription_details' => '',
				'category_image'       => ''
			]);
			
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$image = $request->file('category_image');
			if(!empty($image)){
				$validatedData['category_image'] = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/gym_cover_image'); 
				$image->move($destinationPath, $validatedData['category_image']);
			}
			
			$validatedData['subscription_details'] =htmlentities($request->subscription_details); 
			$id = DB::table('gym_category')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/edit_gym_category?id='.$request->id)->with('alert-success', 'Gym Category Updated successfully.');
			} else {
				return redirect('admin/edit_gym_category?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$categoryInfo = DB::table('gym_category')->where(['id'=>$request->id])->first();
			return view('admin/edit_gym_category', ['user'=>$user,'pagesName'=>$pagesName,'category'=>$categoryInfo]);
		}
	}
	
	public function addPark(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'park_name' => 'required|max:255',
				'park_type' => 'required',
				'lat' => 'required',
				'lon' => 'required',
				'park_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'park_address' => 'required',
				'about_park' => 'required'
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			
			$parkImage = $request->file('park_image');
			if(!empty($parkImage)){
				$validatedData['park_image'] = time().'.'.$parkImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/parks');
				$parkImage->move($destinationPath, $validatedData['park_image']);
			}
			
			$id = DB::table('parks')->insertGetId($validatedData);
			if($id){
				return redirect('admin/add_park')->with('alert-success', 'Park added successfully.');
			} else {
				return redirect('admin/add_park')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_park', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	
	public function parkLists(){
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		$parkLists = DB::table('parks')->get();
		/* return view('admin/park_list', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'parkLists'=>$parkLists
				]); */
		return view('admin/park_list_new', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'parkLists'=>$parkLists
				]);
	}
	
	
	public function editPark(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'id'=> 'required',
				'park_name' => 'required|max:255',
				'park_type' => 'required',
				'lat' => 'required',
				'lon' => 'required',
				'park_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'park_address' => 'required',
				'about_park' => 'required'				
			]);
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$parkImage = $request->file('park_image');
			if(!empty($parkImage)){
				$validatedData['park_image'] = time().'.'.$parkImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/parks');
				$parkImage->move($destinationPath, $validatedData['park_image']);
			}
			$validatedData['park_address'] =htmlentities($request->park_address); 
			$id = DB::table('parks')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/edit_park?id='.$request->id)->with('alert-success', 'Park Information Updated successfully.');
			} else {
				return redirect('admin/edit_park?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$parkInfo = DB::table('parks')->where(['id'=>$request->id])->get();
			return view('admin/edit_park', ['user'=>$user,'pagesName'=>$pagesName,'park'=>$parkInfo]);
		}
	}
	
	
	public function addGymEquipments(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'equipment_type' => 'required',
				'equipment_name' => 'required|max:255',
				'equipment_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'description' => '',
			]);
			$equipmentImage = $request->file('equipment_image');
			if(!empty($equipmentImage)){
				$validatedData['equipment_image'] = time().'.'.$equipmentImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/equipments');
				$equipmentImage->move($destinationPath, $validatedData['equipment_image']);
			}			
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$id = DB::table('gym_equipments')->insertGetId($validatedData);
			if($id){
				return redirect('admin/add_gym_equipments')->with('alert-success', 'Gym Equipments added successfully.');
			} else {
				return redirect('admin/add_gym_equipments')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_gym_equipments', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	
	public function euquipmentLists(){
		$user = Auth::user();
		$pagesName = $this->leftBarPageName();
		$gym_equipments = DB::table('gym_equipments')->get();
		return view('admin/gym_equipment_list', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'gymEquipmets'=>$gym_equipments
				]);
	}
	public function editEquipment(Request $request){
		if ($request->isMethod('post')) {
			$validatedData = $this->validate($request,[
				'equipment_type' => 'required',
				'equipment_name' => 'required|max:255',
				'equipment_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
				'description' => '',
			]);
			$equipmentImage = $request->file('equipment_image');
			if(!empty($equipmentImage)){
				$validatedData['equipment_image'] = time().'.'.$equipmentImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/equipments');
				$equipmentImage->move($destinationPath, $validatedData['equipment_image']);
			}			
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$validatedData['description'] =htmlentities($request->description); 
			$id = DB::table('gym_equipments')->where(['id'=>$request->id])->update($validatedData);
					
			if($id){
				return redirect('admin/edit_equipment?id='.$request->id)->with('alert-success', 'Equipment Information Updated successfully.');
			} else {
				return redirect('admin/edit_equipment?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$equipmentInfo = DB::table('gym_equipments')->where(['id'=>$request->id])->first();
			return view('admin/edit_gym_equipment', ['user'=>$user,'pagesName'=>$pagesName,'equipment'=>$equipmentInfo]);
		}
	}
	
	public function addGym(Request $request){
		if ($request->isMethod('post')) {
			$array = array();
			$validatedData =$this->validate($request,[
					'gym_category'=>'required',
					'gym_title' => 'required|max:255',
					'email'=>'required',
					'country_code'=>'required',
					'contact_number'=>'required',
					'address' => 'required|max:255',
					'cover_image' => 'image|mimes:jpeg,png,jpg',
					//'galary_image' => 'image|mimes:jpeg,png,jpg',
					//'video' => '',
					'brief_description' => 'required',
					'full_description' => '',
					'lat' => '',
					'lon' => ''
				]
			);

			$userRandNum = Str::random(12);
			$validatedData['user_id'] = strtoupper($userRandNum);
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/gym_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
/* 			
			$galaryImage = $request->file('galary_image');
			$destinationPath = public_path('/assets/images/gym_galary_image');
			  
			if(!empty($galaryImage)){
				foreach ($galaryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationPath, $filename);
				  $galaryImageName[]=$filename;
				}
				$validatedData['galary_image'] = implode(',',$galaryImageName);
			} */			
/* DB::enableQueryLog(); */
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$id = DB::table('gym_users')->insertGetId($validatedData);

/* 
$queries = DB::getQueryLog();
echo '<pre>';
print_r($queries);
die; */
			$galaryImage = $request->file('galary_image');
			$destinationPath = public_path('/assets/images/gym_galary_image');
			  
			if(!empty($galaryImage)){
				foreach ($galaryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationPath, $filename);
				  $galaryImageName[]=
						array(
							"gym_id"=>$validatedData['user_id'],
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$gymGalary = DB::table("gym_galary_images")->insert($galaryImageName);
			}
			/* $gymUserId = DB::table('gym_users')->where('id',$id)->pluck('user_id')->first();
			print_r($gymUserId);
			die; */
			$equipmentId = $request->equipmentId;
			foreach($equipmentId as $key=>$newVal){
				$addEquipment[] = array(
					'user_id'=>$validatedData['user_id'],
					'equipment_id'=>$newVal,
					'created_at'=>date('Y-m-d h:i:s')
				);
			}
			$equipmentsInfo = DB::table("gym_equipment_and_quantity")->insert($addEquipment);
/*  */
$monopen  = $request->monopen!='' ? $request->monopen:'';
$monclose = $request->monclose!='' ? $request->monclose:'';
$monday = array();
if(!empty($monopen) and !empty($monclose)){ 
	
	foreach (array_keys($monopen + $monclose) as $key) {
		$monday[$key] = $monopen[$key] .'-'. $monclose[$key];
	}
	$mondayData = json_encode($monday);
	$mondayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$mondayData,
		'day'=>'MONDAY',
		'created_at'=>date('Y-m-d h:i:s')
	);
	$mondayid = DB::table('gym_open_close_time')->insertGetId($mondayData);
}
$tuesopen = $request->tuesopen!='' ? $request->tuesopen:'';
$tuesclose = $request->tuesclose!='' ? $request->tuesclose:'';
$tuesday = array();
if(!empty($tuesopen) and !empty($tuesclose)){ 	
	
	foreach (array_keys($tuesopen + $tuesclose) as $key) {
		$tuesday[$key] = $tuesopen[$key] .'-'. $tuesclose[$key];
	}
	$tuesdayData = json_encode($tuesday);
	$tuesdayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$tuesdayData,
		'day'=>'TUESDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$tuesdayid = DB::table('gym_open_close_time')->insertGetId($tuesdayData);
}
$wedopen = $request->wedopen!='' ? $request->wedopen:'';
$wedclose = $request->wedclose!='' ? $request->wedclose:'';
$wednesday = array();
if(!empty($wedopen) and !empty($wedclose)){ 
	
	foreach (array_keys($wedopen + $wedclose) as $key) {
		$wednesday[$key] = $wedopen[$key] .'-'. $wedclose[$key];
	}
	//$wednesdayData = json_encode($wednesday);
	$wednesdayData = json_encode($wednesday);
	$wednesdayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$wednesdayData,
		'day'=>'WEDNESDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$wednesdayid = DB::table('gym_open_close_time')->insertGetId($wednesdayData);
}
$thursopen = $request->thursopen!='' ? $request->thursopen:'';
$thursclose = $request->thursclose!='' ? $request->thursclose:'';
$thursday = array();
if(!empty($thursopen) and !empty($thursclose)){ 
	
	foreach (array_keys($thursopen + $thursclose) as $key) {
		$thursday[$key] = $thursopen[$key] .'-'. $thursclose[$key];
	}
	$thursdayData = json_encode($thursday);
	$thursdayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$thursdayData,
		'day'=>'THURSDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$thursdayid = DB::table('gym_open_close_time')->insertGetId($thursdayData);
}
$fridopen = $request->fridopen!='' ? $request->fridopen:'';
$fridclose = $request->fridclose!='' ? $request->fridclose:'';
$friday = array();
if(!empty($fridopen) and !empty($fridclose)){
	
	foreach (array_keys($fridopen + $fridclose) as $key) {
		$friday[$key] = $fridopen[$key] .'-'. $fridclose[$key];
	}
	$fridayData = json_encode($friday);
	$fridayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$fridayData,
		'day'=>'FRIDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$mondayid = DB::table('gym_open_close_time')->insertGetId($fridayData);
}

$satopen = $request->satopen!='' ? $request->satopen:'';
$satclose = $request->satclose!='' ? $request->satclose:'';
$saturday = array();
if(!empty($satopen) and !empty($satclose)){ 
	foreach (array_keys($satopen + $satclose) as $key) {
		$saturday[$key] = $satopen[$key] .'-'. $satclose[$key];
	}
	$saturdayData = json_encode($saturday);
	$saturdayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$saturdayData,
		'day'=>'SATURDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$saturdayid = DB::table('gym_open_close_time')->insertGetId($saturdayData);
}
$sundopen = $request->sundopen!='' ? $request->sundopen:'';
$sundclose = $request->sundclose!='' ? $request->sundclose:'';
$sunday = array();
if(!empty($sundopen) and !empty($sundclose)){ 
	foreach (array_keys($sundopen + $sundclose) as $key) {
		$sunday[$key] = $sundopen[$key] .'-'. $sundclose[$key];
	}
	$sundayData = json_encode($sunday);
	$sundayData = array(
		'user_id'=>$validatedData['user_id'],
		'open_close_time'=>$sundayData,
		'day'=>'SUNDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$sundayid = DB::table('gym_open_close_time')->insertGetId($sundayData);
}

			if($id){
				Session::put('TEMP_CREATE_GYM', 'SECOND');
				$temp_user_id = Session::put('TEMP_USER_ID', $validatedData['user_id']);
				$equipment_quantity_status = Session::put('TEMP_EQUPMENT_QUANTIRY_STATUS', 'ACTIVE');
				$subscription_details_status = Session::put('TEMP_SUBSCRIPTION_DETAILS_STATUS', 'ACTIVE');
				return redirect('admin/add_gym')->with('alert-success', 'Gym added successfully. Please add equipment quantity in next tab.');
			} else {
				return redirect('admin/add_gym')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			if(Session::has('TEMP_USER_ID') AND Session::has('TEMP_EQUPMENT_QUANTIRY_STATUS')){
				$tempId = Session::get('TEMP_USER_ID');
				$equp = DB::table('gym_equipment_and_quantity')
                ->join('gym_equipments', 'gym_equipments.id', '=', 'gym_equipment_and_quantity.equipment_id')
                ->select('gym_equipments.*','gym_equipments.id as equpid','gym_equipment_and_quantity.*')
                ->where('gym_equipment_and_quantity.user_id', '=', $tempId) 
                ->get();
				
				$gym_category = DB::table('gym_category')->get();
				/* echo '<pre>';
				print_r($equp); */
				$countries = DB::table('countries')->get();
				$equipment = DB::table('gym_equipments')->get();
				return view('admin/add_gym', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'gym_equipments'=>$equipment,
					'gym_selected'=>$equp,
					'countries'=>$countries,
					'gym_category'=>$gym_category
					]);
			}
			if(Session::has('TEMP_USER_ID') AND Session::has('TEMP_SUBSCRIPTION_DETAILS_STATUS')){ 
				
				$gym_category = DB::table('gym_category')->get();
				$countries = DB::table('countries')->get();
				$equipment = DB::table('gym_equipments')->get();
				$subsc = array('subsc'=>'add');
				return view('admin/add_gym', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'gym_equipments'=>$equipment,
					//'gym_selected'=>$equp,
					'countries'=>$countries,
					'gym_subscription'=>$subsc,
					'gym_category'=>$gym_category
					]);
			
			} else {
				$equp = array();
				$subsc = array();
			    $equipment = DB::table('gym_equipments')->get();
				$gym_category = DB::table('gym_category')->get();
				$countries = DB::table('countries')->get();
				return view('admin/add_gym', [
					'user'=>$user,
					'pagesName'=>$pagesName,
					'gym_equipments'=>$equipment,
					'gym_selected'=>$equp,
					'gym_subscription'=>$subsc,
					'countries'=>$countries,
					'gym_category'=>$gym_category
					]);
			}
		}
	}
	
	public function addGymEuqupmentQuantity(Request $request){
		/* if ($request->isMethod('post')){
			TEMP_EQUPMENT_QUANTIRY_STATUS
			TEMP_SUBSCRIPTION_DETAILS_STATUS
		} */
		$productId = $request->id;
		$equipQuent = $request->equipQuent;
		$newArray = array_combine($productId, $equipQuent);
	 	foreach($newArray as $key=>$quantity){
			$equipmentQuantity = array(
				'quantity'=>$quantity,
				'updated_at'=>date('Y-m-d h:i:s')
			);
			$query = DB::table('gym_equipment_and_quantity')->where(['id'=>$key])->update($equipmentQuantity);
		} 
		if($query){
			Session::put('TEMP_CREATE_GYM', 'THIRD');
			$request->session()->forget('TEMP_EQUPMENT_QUANTIRY_STATUS');
			if(Session::has('TEMP_SUBSCRIPTION_DETAILS_STATUS')){
			} else {
				$request->session()->forget('TEMP_USER_ID');
			}
			return redirect('admin/add_gym')->with('alert-success', 'Equipments quantity added successfully.');
		} else {
			return redirect('admin/add_gym')->with('alert-failure', 'There is network error. Please try again.');
		}
	}

	public function addGymSubscriptionDetails(Request $request){
		/* if ($request->isMethod('post')){
			TEMP_EQUPMENT_QUANTIRY_STATUS
			TEMP_SUBSCRIPTION_DETAILS_STATUS
		} */
		$subscriptionDetails = array(
			'subscription_title'=>$request->subscription_title,
			'subscription_amount'=>$request->subscription_amount,
			'subscription_month'=>$request->subscription_month,
			'subscription_details'=>$request->subscription_details,
			'updated_at'=>date('Y-m-d h:i:s')
		);
		$tempId = Session::get('TEMP_USER_ID');
		$query = DB::table('gym_users')->where(['user_id'=>$tempId])->update($subscriptionDetails);
		if($query){
			Session::put('TEMP_CREATE_GYM', 'FIRST');
			$request->session()->forget('TEMP_SUBSCRIPTION_DETAILS_STATUS');
			if(Session::has('TEMP_EQUPMENT_QUANTIRY_STATUS')){
			
			} else {
				$request->session()->forget('TEMP_USER_ID');
			}
			return redirect('admin/add_gym')->with('alert-success', 'Subscription added successfully.');
		} else {
			return redirect('admin/add_gym')->with('alert-failure', 'There is network error. Please try again.');
		}
	}
	
	public function gymList(){
		$user = Auth::user();
		$gymList = DB::table('gym_users')
                ->join('gym_category', 'gym_users.gym_category', '=', 'gym_category.id')
                ->select('gym_users.*','gym_category.id as catid','gym_category.*')
                ->get();
		
		/* echo '<pre>';
		print_r($gymList);
		die; */
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		/* return view('admin/gym_list', [
					'user'=>$user,
					'gymList'=>$gymList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]); */
		return view('admin/gym_list_new', [
					'user'=>$user,
					'gymList'=>$gymList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]); 
	}
	
	/* Add Coupon */
	public function addCoupon(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'title' => 'required',
				'brief_description' => 'required',
				'coupon_code' => 'required',
				'coupon_type'=>'required',
				'coupon_category'=>'required'
			]);
			if($request->coupon_type=='amount'){
				$validatedData['amount'] = $request->amount_percentage;

			} 
			if($request->coupon_type=='percentage'){
				$validatedData['percentage'] = $request->amount_percentage;
			}
	
	$validatedData['created_at'] = date('Y-m-d');	
			$id = DB::table('coupon_code')->insertGetId($validatedData);
					
			if($id){
				return redirect('admin/add_coupon')->with('alert-success', 'Coupon added successfully.');
			} else {
				return redirect('admin/add_coupon')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else{
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_coupon', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	

	public function couponList(){
		$user = Auth::user();
		$couponList = DB::table('coupon_code')
                ->select('id','title','coupon_code','brief_description','coupon_type','coupon_category','amount','percentage')
				->where(['active_status'=>1])
                ->get();
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		return view('admin/coupon_list', [
					'user'=>$user,
					'couponList'=>$couponList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]);
	}
	public function removeCoupon(Request $request){
		$updateData = array('active_status' => 0);
		$id = DB::table('coupon_code')->where(['id'=>$request->id])->update($updateData);
		return redirect('admin/coupon_list')->with('alert-success', 'Coupon removed successfully.');
	}
	 
	/* Add Product */
	public function addProduct(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'product_category' => 'required',
				'product_title' => 'required',
				'sub_title' => 'required',
				'product_category' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'amount'=>'required',
				'per'=>'required'
			]);
	
	
	$productRandNum = Str::random(12);
			$validatedData['product_id'] = strtoupper($productRandNum);
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/product_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
			
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$id = DB::table('products')->insertGetId($validatedData);
			
			$galleryImage = $request->file('galary_image');
			$destinationGalleryPath = public_path('/assets/images/product_gallery_image');
			  
			if(!empty($galleryImage)){
				foreach ($galleryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationGalleryPath, $filename);
				  $galleryImageName[]=
						array(
							"product_id"=>$validatedData['product_id'],
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$productGalary = DB::table("product_gallery_images")->insert($galleryImageName);
			}
					
			if($id){
				return redirect('admin/add_product')->with('alert-success', 'Product added successfully.');
			} else {
				return redirect('admin/add_product')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else{
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_product', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	public function productList(){
		$user = Auth::user();
		$productList = DB::table('products')
                ->select('id','product_id','product_category','product_title','sub_title','brief_description','full_description','cover_image','amount','per')
                ->get();
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		return view('admin/product_list', [
					'user'=>$user,
					'productList'=>$productList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]);
	}
	
	public function editProduct(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'id'=> 'required',
				'product_id'=> 'required',
				'product_category' => 'required',
				'product_title' => 'required',
				'sub_title' => 'required',
				'product_category' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'amount'=>'required',
				'per'=>'required'
			]);
			
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/product_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$validatedData['brief_description'] =htmlentities($request->brief_description); 
			$validatedData['full_description'] =htmlentities($request->full_description); 
			$id = DB::table('products')->where(['id'=>$request->id])->update($validatedData);
					
			$galleryImage = $request->file('galary_image');
			$destinationGalleryPath = public_path('/assets/images/product_gallery_image');
			  
			if(!empty($galleryImage)){
				foreach ($galleryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationGalleryPath, $filename);
				  $galleryImageName[]=
						array(
							"product_id"=>$validatedData['product_id'],
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$productGalary = DB::table("product_gallery_images")->insert($galleryImageName);
			}
					
			if($id){
				return redirect('admin/edit_product?id='.$request->id)->with('alert-success', 'Product Information Updated successfully.');
			} else {
				return redirect('admin/edit_product?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$productsInfo = DB::table('products')->where(['id'=>$request->id])->select('id','product_id')->first();
			$productDetails = $this->productdetailsByProductId($productsInfo->product_id);
			/*  echo '<pre>';
			print_r($productDetails);die; */
			return view('admin/edit_product', ['user'=>$user,'pagesName'=>$pagesName,'product'=>$productDetails]);
		}
	}
	function productdetailsByProductId($productId){
		$productDetails = DB::table('products')->where(['active_status'=>1,'product_id'=>$productId])
					->select('id','product_id','currency','product_category','product_title','sub_title','brief_description','full_description','cover_image','amount','per')
					->first();
					
		$productDetails->cover_image = url('/').self::PRODUCT_COVER_IMAGE_FULL_PATH.$productDetails->cover_image;
		$productDetails->star_rating=4.5;
		$productDetails->brief_description = strip_tags($productDetails->brief_description);
		$productDetails->full_description = strip_tags($productDetails->full_description);					
			$galaryImg = DB::table('product_gallery_images')
				->select('id','image')
				->where(['product_id'=>$productId])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$prodimg){
						$galaryImg[$ky]->image = url('/').self::PRODUCT_GALARY_IMAGE_FULL_PATH.$prodimg->image;
					}
				} 
			$productDetails->galary_image = $galaryImg; 
		return $productDetails;
	}
	
	public function removeProductAccessorieImage(Request $request){
		$user = Auth::user();
		if($request->type=='PRODUCT'){
			$deleteRecord = DB::table('product_gallery_images')->where('id',$request->id)->delete();
		}
		if($request->type=='ACCESSORIE'){
			$deleteRecord = DB::table('accessories_gallery_images')->where('id',$request->id)->delete();
		}
		if($request->type=='GYM'){
			$deleteRecord = DB::table('gym_galary_images')->where('id',$request->id)->delete();
		}
		if($deleteRecord){
			return response()->json(['success'=>'SUCCESS']);
		} else{
			return response()->json(['success'=>'FAILURE']);
		}
		
	}
	/* Add Accessories */
	public function addAccessories(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'accessorie_title' => 'required',
				'sub_title' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'amount'=>'required',
				'per'=>'required'
			]);
	
	
	$accessorieRandNum = Str::random(12);
			$validatedData['accessorie_id'] = strtoupper($accessorieRandNum);
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/accessorie_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
			
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$id = DB::table('accessories')->insertGetId($validatedData);
			
			$galleryImage = $request->file('galary_image');
			$destinationGalleryPath = public_path('/assets/images/accessorie_gallery_image');
			  
			if(!empty($galleryImage)){
				foreach ($galleryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationGalleryPath, $filename);
				  $galleryImageName[]=
						array(
							"accessorie_id"=>$validatedData['accessorie_id'],
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$productGalary = DB::table("accessories_gallery_images")->insert($galleryImageName);
			}
					
			if($id){
				return redirect('admin/add_accessorie')->with('alert-success', 'Product added successfully.');
			} else {
				return redirect('admin/add_accessorie')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else{
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			return view('admin/add_accessorie', ['user'=>$user,'pagesName'=>$pagesName]);
		}
	}
	public function accessoriesList(){
		$user = Auth::user();
		$accessorieList = DB::table('accessories')
                ->select('id','accessorie_id','accessorie_title','sub_title','brief_description','full_description','cover_image','amount','per')
                ->get();
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		return view('admin/accessorie_list', [
					'user'=>$user,
					'accessorieList'=>$accessorieList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]);
	}
	
	public function editAccessorie(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'id'=> 'required',
				'accessorie_id'=> 'required',
				'accessorie_title' => 'required',
				'sub_title' => 'required',
				'brief_description' => 'required',
				'full_description' => 'required',
				'amount'=>'required',
				'per'=>'required'
			]);
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/accessorie_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
			
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$validatedData['brief_description'] =htmlentities($request->brief_description); 
			$validatedData['full_description'] =htmlentities($request->full_description); 
			$id = DB::table('accessories')->where(['id'=>$request->id])->update($validatedData);
					
			$galleryImage = $request->file('galary_image');
			$destinationGalleryPath = public_path('/assets/images/accessorie_gallery_image');
			  
			if(!empty($galleryImage)){
				foreach ($galleryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationGalleryPath, $filename);
				  $galleryImageName[]=
						array(
							"accessorie_id"=>$validatedData['accessorie_id'],
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$productGalary = DB::table("accessories_gallery_images")->insert($galleryImageName);
			}
					
			if($id){
				return redirect('admin/edit_accessorie?id='.$request->id)->with('alert-success', 'Accessorie Information Updated successfully.');
			} else {
				return redirect('admin/edit_accessorie?id='.$request->id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			$accessoriesInfo = DB::table('accessories')->where(['id'=>$request->id])->select('id','accessorie_id')->first();
			$accessoriesDetails = $this->accessoriesDetailsByAccessoriesId($accessoriesInfo->accessorie_id);
			return view('admin/edit_accessorie', ['user'=>$user,'pagesName'=>$pagesName,'accessorie'=>$accessoriesDetails]);
		}
	}
	
	
	function accessoriesDetailsByAccessoriesId($accessorieId){
		$accessoriesDetails = DB::table('accessories')->where(['active_status'=>1,'accessorie_id'=>$accessorieId])
					->select('id','accessorie_id','currency','accessorie_title','sub_title','brief_description','full_description','cover_image','amount','per')
					->first();
					
		$accessoriesDetails->cover_image = url('/').self::ACCESSORIES_COVER_IMAGE_FULL_PATH.$accessoriesDetails->cover_image;
		$accessoriesDetails->star_rating=4.5;
		$accessoriesDetails->brief_description = strip_tags($accessoriesDetails->brief_description);
		$accessoriesDetails->full_description = strip_tags($accessoriesDetails->full_description);	
			
			$galaryImg = DB::table('accessories_gallery_images')
				->select('id','image')
				->where(['accessorie_id'=>$accessoriesDetails->accessorie_id])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$accesimg){
						$galaryImg[$ky]->image = url('/').self::ACCESSORIES_GALARY_IMAGE_FULL_PATH.$accesimg->image;
					}
				} 
			$accessoriesDetails->galary_image = $galaryImg; 
		return $accessoriesDetails;
	}
	/*  */
	/* function leftBarPageName(){
		return array('page_name'=>'avnish');
	} */
	function leftBarPageName(){
		return DB::table('pages')->get();
	}
	
	
	function productSold(){
		$user = Auth::user();
		$soldProductList = DB::table('payments')->select('payments.id','payments.user_id','payments.order_id','payments.transaction_id','payments.product_type','payments.product_id','payments.quantity','payments.tax','payments.shipping','payments.shipping_address_id','payments.sub_total','payments.total','payments.payer_status','payments.payment_state','payments.order_status','payments.payment_method','payments.payer_email','payments.coupon_id','payments.coupon_code','payments.product_amount','payments.discounted_amount','payments.purchase_status','payments.payment_created_at','payments.payment_update_time','payments.created_at','payments.updated_at','products.currency','products.product_category','products.product_title','products.sub_title','products.brief_description','products.full_description','products.cover_image','products.amount','products.per','shipping_address.recipient_name','shipping_address.city','shipping_address.state','shipping_address.postal_code','shipping_address.country_code','shipping_address.phone_number','shipping_address.line1','shipping_address.line2','shipping_address.land_mark')
		->join('products','products.product_id','=','payments.product_id')
		->join('shipping_address','shipping_address.id','=','payments.shipping_address_id')
		->where(['product_type'=>'PRODUCT'])->orderBy('payments.created_at','DESC')->get();
		
		foreach($soldProductList as $key=>$accessorie){
			$soldProductList[$key]->tracking_details = $this->getTrackingDetails($accessorie->order_id);
		}
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
/* 		return view('admin/sold_product_list', [
					'user'=>$user,
					'soldProductList'=>$soldProductList,
					'pagesName'=>$pagesName,
					//'gym_category'=>$gym_category
				]); */
		return view('admin/sold_product_list_new', [
				'user'=>$user,
				'soldProductList'=>$soldProductList,
				'pagesName'=>$pagesName,
				//'gym_category'=>$gym_category
			]);
	}
	function getProductInfo($productType,$productId){
		if($productType=='PRODUCT'){
			$productInfo  = DB::table('products')->select('*')->where(['product_id'=>$productId])->first();
		}
		if($productType=='ACCESSORIES'){
			$productInfo  = DB::table('accessories')->select('*')->where(['accessorie_id'=>$productId])->first();
		}
		return $productInfo;
	}
	function addTrackingDetails(Request $request){
		$validatedData =$this->validate($request,[
			'order_id'=> 'required',
			'user_id' => 'required',
			'product_type' => 'required',
			'vendor_name' => 'required',
			'tracking_id' => 'required',
			'tracking_url' => 'required',
			'submited_date'=>'required',
		]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');
		$id = DB::table('tracking_details')->insertGetId($validatedData);
		if($id){
			if($request->product_type=='ACCESSORIES'){
/* Push Notification */		
		$accessorieInfo = $this->getProductInfo($request->product_type,$request->accessorie_id);
		$deviceInfo = $this->get_device_token($request->user_id);
		$tokenId = array($deviceInfo->device_token);
		$pushNotification = array(
			"title" => $accessorieInfo->accessorie_title,
			"body"  => "Order shipped.",
			"icon"  => url('/').self::ACCESSORIES_COVER_IMAGE_FULL_PATH.$accessorieInfo->cover_image,  
			"flag"  => 10,
			"data"  => array (
				"id"  =>  $request->tracking_id,
			) 
		);
		$this->push_notification($tokenId,$pushNotification);
/* End Push Notification */	
				
				
				return redirect('admin/accessories_sold')->with('alert-success', 'Shipping Details added successfully.');
			} else if($request->product_type=='PRODUCT'){
/* Push Notification */		
		$productInfo = $this->getProductInfo($request->product_type,$request->accessorie_id);
		$deviceInfo = $this->get_device_token($request->user_id);
		$tokenId = array($deviceInfo->device_token);
		$pushNotification = array(
			"title" => $productInfo->product_title,
			"body"  => "Order shipped.",
			"icon"  => url('/').self::PRODUCT_COVER_IMAGE_FULL_PATH.$productInfo->cover_image,  
			"flag"  => 9,
			"data"  => array (
				"id"  => $request->tracking_id,
			) 
		);
		$this->push_notification($tokenId,$pushNotification);
/* End Push Notification */	
				return redirect('admin/product_sold')->with('alert-success', 'Shipping Details added successfully.');
			}
		} else {
			if($request->product_type=='ACCESSORIES'){
				return redirect('admin/accessories_sold')->with('alert-failure', 'There is network error. Please try again.');
			} else if($request->product_type=='PRODUCT'){
				return redirect('admin/product_sold')->with('alert-failure', 'There is network error. Please try again.');
			}
		}
	}
	function getTrackingDetails($order_id){
		$trackingDetails = DB::table('tracking_details')->where(['order_id'=>$order_id])->first();
		return (array)$trackingDetails;
	}
	function addUserGymId(Request $request){
		
	}
	function accessoriesSold(){
		$user = Auth::user();
		$soldAccessorieList = DB::table('payments')->select('payments.id','payments.user_id','payments.order_id','payments.transaction_id','payments.product_type','payments.accessorie_id','payments.quantity','payments.tax','payments.shipping','payments.shipping_address_id','payments.sub_total','payments.total','payments.payer_status','payments.payment_state','payments.order_status','payments.payment_method','payments.payer_email','payments.coupon_id','payments.coupon_code','payments.product_amount','payments.discounted_amount','payments.purchase_status','payments.payment_created_at','payments.payment_update_time','payments.created_at','payments.updated_at','accessories.currency','accessories.accessorie_title','accessories.sub_title','accessories.brief_description','accessories.full_description','accessories.cover_image','accessories.amount','accessories.per','shipping_address.recipient_name','shipping_address.city','shipping_address.state','shipping_address.postal_code','shipping_address.country_code','shipping_address.phone_number','shipping_address.line1','shipping_address.line2','shipping_address.land_mark')
		->join('accessories','accessories.accessorie_id','=','payments.accessorie_id')
		->join('shipping_address','shipping_address.id','=','payments.shipping_address_id')
		->where(['product_type'=>'ACCESSORIES'])->orderBy('payments.created_at','DESC')->get();
		
		foreach($soldAccessorieList as $key=>$accessorie){
			$soldAccessorieList[$key]->tracking_details = $this->getTrackingDetails($accessorie->order_id);
		}
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		/* return view('admin/sold_accessorie_list', [
					'user'=>$user, 
					'soldAccessorieList'=>$soldAccessorieList,
					'pagesName'=>$pagesName
				]); */
			return view('admin/sold_accessorie_list_new', [
					'user'=>$user, 
					'soldAccessorieList'=>$soldAccessorieList,
					'pagesName'=>$pagesName
				]);
				
	}
	function gymSubscritpionList(){
		$user = Auth::user();
		$gymSubscritpionList = DB::table('subscription_payments')->select('subscription_payments.id','subscription_payments.user_id','subscription_payments.order_id','subscription_payments.transaction_id','subscription_payments.gym_id','subscription_payments.subscription_for','subscription_payments.membership_type','subscription_payments.start_date', 'subscription_payments.preferred_time','subscription_payments.months', 'subscription_payments.member_count','subscription_payments.amount','subscription_payments.tax','subscription_payments.shipping','subscription_payments.sub_total','subscription_payments.total','subscription_payments.payer_status','subscription_payments.payment_state','subscription_payments.order_status','subscription_payments.payment_method','subscription_payments.payer_email','subscription_payments.coupon_id','subscription_payments.coupon_code','subscription_payments.membership_amount','subscription_payments.discounted_amount','subscription_payments.purchase_status','subscription_payments.payment_created_at','subscription_payments.payment_update_time','subscription_payments.created_at','subscription_payments.updated_at','gym_users.gym_category','gym_users.gym_title','gym_users.email','gym_users.country_code','gym_users.contact_number','gym_users.cover_image','gym_users.currency')
		->join('gym_users','subscription_payments.gym_id','=','gym_users.user_id')
		->where(['subscription_payments.subscription_for'=>'GYM'])->orderBy('subscription_payments.created_at','DESC')->get();
		foreach($gymSubscritpionList as $key=>$subscritption){
			$months = '+'.$subscritption->months.' months';
			$ENDATE = strtotime($months,strtotime($subscritption->start_date));
			$gymSubscritpionList[$key]->end_date = date("Y-m-d",$ENDATE);
			$gymSubscritpionList[$key]->cover_image=url('/').self::COVER_IMAGE_FULL_PATH.$subscritption->cover_image;
		}
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();					
		/* return view('admin/gym_subscription_list', [
					'user'=>$user, 
					'subscriptionList'=>$gymSubscritpionList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]); */
		return view('admin/gym_subscription_list_new', [
					'user'=>$user, 
					'subscriptionList'=>$gymSubscritpionList,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category
				]);
		
	}
	
	function trainerSubscritpionList(){
		$user = Auth::user();
		$trainerSubscritpionList = DB::table('subscription_payments')->select('subscription_payments.id','subscription_payments.user_id','subscription_payments.order_id','subscription_payments.transaction_id','subscription_payments.trainer_id','subscription_payments.subscription_for','subscription_payments.membership_type','subscription_payments.start_date', 'subscription_payments.preferred_time','subscription_payments.months', 'subscription_payments.member_count','subscription_payments.amount','subscription_payments.tax','subscription_payments.shipping','subscription_payments.sub_total','subscription_payments.total','subscription_payments.payer_status','subscription_payments.payment_state','subscription_payments.order_status','subscription_payments.payment_method','subscription_payments.payer_email','subscription_payments.coupon_id','subscription_payments.coupon_code','subscription_payments.membership_amount','subscription_payments.discounted_amount','subscription_payments.purchase_status','subscription_payments.payment_created_at','subscription_payments.payment_update_time','subscription_payments.created_at','subscription_payments.updated_at','users.user_name',
		'users.user_type','users.name','users.email','users.profile_picture','users.currency')
		->join('users','subscription_payments.trainer_id','=','users.user_id')
		->where(['subscription_payments.subscription_for'=>'TRAINER'])->orderBy('subscription_payments.created_at','DESC')->get();
		
		foreach($trainerSubscritpionList as $key=>$subscritption){
			$days = '+'.$subscritption->months.' days';
			$ENDATE = strtotime($days,strtotime($subscritption->start_date));
			$trainerSubscritpionList[$key]->end_date = date("Y-m-d",$ENDATE);
			$trainerSubscritpionList[$key]->profile_picture=url('/').self::PROFILE_IMAGE_PATH.$subscritption->profile_picture;
		}
		$pagesName = $this->leftBarPageName(); 
		/* return view('admin/trainer_subscription_list', [
					'user'=>$user, 
					'subscriptionList'=>$trainerSubscritpionList,
					'pagesName'=>$pagesName
				]); */
			return view('admin/trainer_subscription_list_new', [
					'user'=>$user, 
					'subscriptionList'=>$trainerSubscritpionList,
					'pagesName'=>$pagesName
				]);
	}
	/* Edit Update Gym Profile */
	public function editGymProfile(Request $request){
		
		$user = Auth::user();
		if ($request->isMethod('post')) {
			$array = array();
			/* echo "avnish";
			die; */
			$validatedData =$this->validate($request,[
					'gym_category'=>'required',
					'gym_title' => 'required|max:255',
					//'email'=>'required',
					'country_code'=>'required',
					'contact_number'=>'required',
					'address' => 'required|max:255',
					'cover_image' => 'image|mimes:jpeg,png,jpg|max:20489900',
					//'galary_image' => 'image|mimes:jpeg,png,jpg',
					//'video' => '',
					'brief_description' => 'required|max:255',
					'full_description' => '',
					'lat' => 'required',
					'lon' => 'required',
				]
			);
			
			/* $user_id = $request->user_id; */
			$coverImage = $request->file('cover_image');
			if(!empty($coverImage)){
				$validatedData['cover_image'] = time().'.'.$coverImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/gym_cover_image');
				$coverImage->move($destinationPath, $validatedData['cover_image']);
			}		
		
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			/* $id = DB::table('gym_users')->insertGetId($validatedData); */
			$update = DB::table('gym_users')->where(['user_id'=>$request->user_id])->update($validatedData);
			
			$galaryImage = $request->file('galary_image');
			$destinationPath = public_path('/assets/images/gym_galary_image');
			  
			if(!empty($galaryImage)){
				foreach ($galaryImage as $key=>$file) {
				  $filename = time().$key.'.'.$file->getClientOriginalExtension();
				  $file->move($destinationPath, $filename);
				  $galaryImageName[]=
						array(
							"gym_id"=>$request->user_id,
							"image"=>$filename,
							"created_at"=>date('Y-m-d')
						);
				}
				$gymGalary = DB::table("gym_galary_images")->insert($galaryImageName);
			}
			
			/* 
			//Needs to be discuss with shivam
			$equipmentId = $request->equipmentId;
			foreach($equipmentId as $key=>$newVal){
				$addEquipment[] = array(
					'user_id'=>$request->user_id,
					'equipment_id'=>$newVal,
					'created_at'=>date('Y-m-d h:i:s')
				);
			}
			$equipmentsInfo = DB::table("gym_equipment_and_quantity")->insert($addEquipment); */
			
/* Start GYM Open Close Time first remove old then create new in update case */

DB::table('gym_open_close_time')->where('user_id', $request->user_id)->delete();

$monopen = $request->monopen;
$monclose = $request->monclose;
$monday = array();
if(!empty($monopen) and !empty($monclose)){ 
	
	foreach (array_keys($monopen + $monclose) as $key) {
		$monday[$key] = $monopen[$key] .'-'. $monclose[$key];
	}
	$mondayData = json_encode($monday);
	$mondayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$mondayData,
		'day'=>'MONDAY',
		'created_at'=>date('Y-m-d h:i:s')
	);
	$mondayid = DB::table('gym_open_close_time')->insertGetId($mondayData);
}
$tuesopen = $request->tuesopen;
$tuesclose = $request->tuesclose;
$tuesday = array();
if(!empty($tuesopen) and !empty($tuesclose)){ 	
	
	foreach (array_keys($tuesopen + $tuesclose) as $key) {
		$tuesday[$key] = $tuesopen[$key] .'-'. $tuesclose[$key];
	}
	$tuesdayData = json_encode($tuesday);
	$tuesdayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$tuesdayData,
		'day'=>'TUESDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$tuesdayid = DB::table('gym_open_close_time')->insertGetId($tuesdayData);
}
$wedopen = $request->wedopen;
$wedclose = $request->wedclose;
$wednesday = array();
if(!empty($wedopen) and !empty($wedclose)){ 
	
	foreach (array_keys($wedopen + $wedclose) as $key) {
		$wednesday[$key] = $wedopen[$key] .'-'. $wedclose[$key];
	}
	//$wednesdayData = json_encode($wednesday);
	$wednesdayData = json_encode($wednesday);
	$wednesdayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$wednesdayData,
		'day'=>'WEDNESDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$wednesdayid = DB::table('gym_open_close_time')->insertGetId($wednesdayData);
}
$thursopen = $request->thursopen;
$thursclose = $request->thursclose;
$thursday = array();
if(!empty($thursopen) and !empty($thursclose)){ 
	
	foreach (array_keys($thursopen + $thursclose) as $key) {
		$thursday[$key] = $thursopen[$key] .'-'. $thursclose[$key];
	}
	$thursdayData = json_encode($thursday);
	$thursdayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$thursdayData,
		'day'=>'THURSDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$thursdayid = DB::table('gym_open_close_time')->insertGetId($thursdayData);
}
$fridopen = $request->fridopen;
$fridclose = $request->fridclose;
$friday = array();
if(!empty($fridopen) and !empty($fridclose)){
	
	foreach (array_keys($fridopen + $fridclose) as $key) {
		$friday[$key] = $fridopen[$key] .'-'. $fridclose[$key];
	}
	$fridayData = json_encode($friday);
	$fridayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$fridayData,
		'day'=>'FRIDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$mondayid = DB::table('gym_open_close_time')->insertGetId($fridayData);
}

$satopen = $request->satopen;
$satclose = $request->satclose;
$saturday = array();
if(!empty($satopen) and !empty($satclose)){ 
	foreach (array_keys($satopen + $satclose) as $key) {
		$saturday[$key] = $satopen[$key] .'-'. $satclose[$key];
	}
	$saturdayData = json_encode($saturday);
	$saturdayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$saturdayData,
		'day'=>'SATURDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$saturdayid = DB::table('gym_open_close_time')->insertGetId($saturdayData);
}
$sundopen = $request->sundopen;
$sundclose = $request->sundclose;
$sunday = array();
if(!empty($sundopen) and !empty($sundclose)){ 
	foreach (array_keys($sundopen + $sundclose) as $key) {
		$sunday[$key] = $sundopen[$key] .'-'. $sundclose[$key];
	}
	$sundayData = json_encode($sunday);
	$sundayData = array(
		'user_id'=>$request->user_id,
		'open_close_time'=>$sundayData,
		'day'=>'SUNDAY',
		'created_at'=>date('Y-m-d h:i:s')
		);
	$sundayid = DB::table('gym_open_close_time')->insertGetId($sundayData);
}
			/* End GYM Open Close Time first  */			
			if($update){
				Session::put('TEMP_UPDATE_GYM', 'FIRST');
				return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-success', 'Gym Profile updated  successfully');
			} else {
				return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-failure', 'There is network error. Please try again.');
			}
		} else{
			
//DB::enableQueryLog();
		$gymProfile = DB::table('gym_users')
					->join('gym_category', 'gym_users.gym_category', '=', 'gym_category.id')
					->select('gym_users.*','gym_category.id as catid','gym_category.category_name','gym_category.category_image')
					->where(['gym_users.user_id'=>$request->id])->first(); 
/* $queries = DB::getQueryLog();
echo '<pre>';
print_r($queries);
 */
		$gymOpenTime  = DB::table('gym_open_close_time')->select('*')->where(['user_id'=>$request->id])->get();
		$gymEquipmets = DB::table('gym_equipment_and_quantity')
                ->join('gym_equipments', 'gym_equipments.id', '=', 'gym_equipment_and_quantity.equipment_id')
                ->select('gym_equipments.*','gym_equipments.id as equpid','gym_equipment_and_quantity.*')
                ->where('gym_equipment_and_quantity.user_id', '=', $request->id) 
                ->get();
		$pagesName = $this->leftBarPageName();
		$gym_category = DB::table('gym_category')->get();
		$countries = DB::table('countries')->get();
		$equipment = DB::table('gym_equipments')->get();
		$gym_galary_images = DB::table('gym_galary_images')->select('*')->where(['gym_id'=>$request->id])->get();
		/* echo '<pre>';
		print_r($gym_galary_images);
		die; */
		$selectedEqu = array();
		foreach($gymEquipmets as $k=>$eqp){
			$selectedEqu[] = $eqp->equipment_id;
		}
		
		return view('admin/edit_gym_profile', [
					'user'=>$user,
					'gymProfile'=>$gymProfile,
					'gymOpenTime'=>$gymOpenTime,
					'gymEquipmets'=>$gymEquipmets,
					'gym_galary_images'=>$gym_galary_images,
					'pagesName'=>$pagesName,
					'gym_category'=>$gym_category,
					'countries'=>$countries,
					'gym_equipments'=>$equipment,
					'selectedEqu'=>$selectedEqu
				]);
		}
	}
	
	public function updateGymEuqupmentQuantity(Request $request){
		/* if ($request->isMethod('post')){
			TEMP_EQUPMENT_QUANTIRY_STATUS
			TEMP_SUBSCRIPTION_DETAILS_STATUS
		} */
		$productId = $request->id;
		$equipQuent = $request->equipQuent;
		$newArray = array_combine($productId, $equipQuent);
	 	foreach($newArray as $key=>$quantity){
			$equipmentQuantity = array(
				'quantity'=>$quantity,
				'updated_at'=>date('Y-m-d h:i:s')
			);
			$query = DB::table('gym_equipment_and_quantity')->where(['id'=>$key])->update($equipmentQuantity);
		} 
		if($query){
			Session::put('TEMP_UPDATE_GYM', 'SECOND');
			return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-success', 'Equipments quantity updated successfully');
		} else {
			return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-failure', 'There is network error. Please try again.');
		}
	}
	public function updateGymSubscriptionDetails(Request $request){
		$subscriptionDetails = array(
			'subscription_title'=>$request->subscription_title,
			'subscription_amount'=>$request->subscription_amount,
			'subscription_month'=>$request->subscription_month,
			'subscription_details'=>$request->subscription_details,
			'updated_at'=>date('Y-m-d h:i:s')
		);
		$query = DB::table('gym_users')->where(['user_id'=>$request->user_id])->update($subscriptionDetails);
		if($query){
			Session::put('TEMP_UPDATE_GYM', 'THIRD');
			return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-success', 'Subscription updated successfully.');
		} else {
			return redirect('admin/edit_gym_profile?id='.$request->user_id)->with('alert-failure', 'There is network error. Please try again.');
		}
	}
	
	//	Session::put('TEMP_UPDATE_GYM', 'FIRST');
	function get_device_token($userId){
		$userInfo = DB::table('users')
					->select('name','email','profile_picture','device_type','device_token')
					->where(['user_id'=>$userId])->first();
		$userImg = url('/').self::PROFILE_IMAGE_PATH . $userInfo->profile_picture;
		$userInfo->profile_picture = $userImg;
		return $userInfo;	
	}

	public function push_notification($token_id,$notification){ 
	    $url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array (
			'registration_ids' => $token_id,
			'notification' => $notification
	    );
		$fields = json_encode ( $fields );
	    $headers = array (
	            'Authorization: key=' . config('constants.AUTHORIZATION_SERVER_KEY'),
	            'Content-Type: application/json'
	    );
	    $ch = curl_init ();
	    curl_setopt ( $ch, CURLOPT_URL, $url );
	    curl_setopt ( $ch, CURLOPT_POST, true );
	    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
	    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

	    $result = curl_exec ( $ch );
	    /* echo $result; */
	    $result=json_decode($result);
	    /*print_r($result);*/
	  if($result->success>0){
	    	return true;
	    } else{
	    	return false;
	    }
	    curl_close ( $ch );

	}
	
	public function updateMembershipRate(Request $request){
		if ($request->isMethod('post')) {
			$validatedData =$this->validate($request,[
				'single_rate' => 'required',
				'group_rate' => 'required',
				'group_count' => 'required',
			]);
			$id = DB::table('trainer_membership_rate')->where(['id'=>1])->update($validatedData);
			if($id){
				$updateTrainerRates = array('single_membership_rate'=>$request->single_rate,'group_membership_rate'=>$request->group_rate);
				$updateTrainerMembersip = DB::table('users')->where(['user_type'=>3])->update($updateTrainerRates);
				if($updateTrainerMembersip){
					return redirect('admin/update_membership_rate')->with('alert-success', 'Membership rate updated successfully.');
				}else {
					return redirect('admin/update_membership_rate')->with('alert-failure', 'There is network error. Please try again.');
				}
			} else {
				return redirect('admin/update_membership_rate')->with('alert-failure', 'There is network error. Please try again.');
			}
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			/* $pageInfo = $pageInfo = DB::table('pages')->where(['id'=>$request->id])->get(); */
			$subscription = DB::table('trainer_membership_rate')->where(['id'=>1])->first();
			return view('admin/update_membership_rate', ['user'=>$user,'pagesName'=>$pagesName,'subscription'=>$subscription]);
		}
	}
	
	/* Wallect Request From Trainer */
	public function trainerWalletRequest(Request $request){
		if ($request->isMethod('post')) {
		} else {
			$user = Auth::user();
			$pagesName = $this->leftBarPageName();
			/* $pageInfo = $pageInfo = DB::table('pages')->where(['id'=>$request->id])->get(); */
			//$walletRequest = DB::table('wallet_request')->get();
			
			$walletRequest = DB::table('wallet_request')
                ->join('users', 'wallet_request.trainer_id', '=', 'users.user_id')
                ->select('wallet_request.*','users.name','users.email','users.profile_picture','users.country_code','users.phone_number','users.gender','users.address','users.single_membership_rate','users.group_membership_rate','users.trainer_category','users.paypal_id')->orderBy('wallet_request.requested_date','DESC')->get();
			return view('admin/trainer_wallet_request', ['user'=>$user,'pagesName'=>$pagesName,'walletRequest'=>$walletRequest]);
		}
	}
	public function subscriptionDetailsByWalletRequest($trainerId,$requestedDateTime){
		$user = Auth::user();
		$requestedDateTime = date('Y-m-d h:i:s',$requestedDateTime);		
		$subscriptionList = DB::table('subscription_payments')
                ->join('users', 'subscription_payments.user_id', '=', 'users.user_id')
                ->select('subscription_payments.*','users.name','users.email','users.profile_picture','users.country_code','users.phone_number','users.gender','users.address')
				->where(['subscription_payments.trainer_id'=>$trainerId,'subscription_payments.wallet_status'=>1,'subscription_payments.wallet_admin_request_date'=>$requestedDateTime])
				->orderBy('subscription_payments.created_at','DESC')->get();
		/* echo '<pre>';
		print_r($subscriptionList); */
	
		$pagesName = $this->leftBarPageName();
		//return view('admin/trainer_wallet_request', ['user'=>$user,'pagesName'=>$pagesName,'subscription'=>$subscription]);
		
		foreach($subscriptionList as $key=>$subscritption){
			$months = '+'.$subscritption->months.' days';
			$ENDATE = strtotime($months,strtotime($subscritption->start_date));
			$subscriptionList[$key]->end_date = date("Y-m-d",$ENDATE);
			$subscriptionList[$key]->profile_picture=url('/').self::PROFILE_IMAGE_PATH.$subscritption->profile_picture;
		}
		$pagesName = $this->leftBarPageName(); 
		return view('admin/trainer_wallet_subscription_list', [
				'user'=>$user, 
				'subscriptionList'=>$subscriptionList,
				'pagesName'=>$pagesName
			]);
		
	}
	
	public function addTrainerPaymentDetails(Request $request){
		$validatedData =$this->validate($request,[
			'trainer_id'=> 'required',
			'request_date_time'=> 'required',
			'request_id' => 'required',
			'payment_info' => 'required',
			'payment_date' => 'required',
		]);
		$updated_at = date('Y-m-d h:i:s');
		//const PAYMENT_TRAINER_IMAGE_FULL_PATH = '/assets/images/';
		$updateInfo = array('status'=>2,'payment_info'=>$request->payment_info,'payment_date'=>$request->payment_date,'updated_at'=>$updated_at);
			$payImage = $request->file('payment_image');
			if(!empty($payImage)){
				$updateInfo['payment_image'] = time().$request->trainer_id.'.'.$payImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images');
				$payImage->move($destinationPath, $updateInfo['payment_image']);
			}	
		$id = DB::table('wallet_request')->where(['id'=>$request->request_id,'trainer_id'=>$request->trainer_id])->update($updateInfo);
		if($id){
			$subscriptionPayment = array('wallet_status'=>2);
			$data = DB::table('subscription_payments')->where(['wallet_admin_request_date'=>$request->request_date_time,'trainer_id'=>$request->trainer_id])->update($subscriptionPayment);
		/* Push Notification */		
				$deviceInfo = $this->get_device_token($request->trainer_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => 'Payment Sent by Admin',
					"body"  => "Payment Sent by Admin",
					"icon"  => '',  
					"flag"  => 18,
					"data"  => array (
						"id"  =>  $request->trainer_id,
					) 
				);
				$this->push_notification($tokenId,$pushNotification);
		/* End Push Notification */	
		return redirect('admin/trainer_wallet_request')->with('alert-success', 'Payment Details added successfully.');
		} else {
			return redirect('admin/trainer_wallet_request')->with('alert-success', 'Failed Please try Again.');
		}
	}
}










