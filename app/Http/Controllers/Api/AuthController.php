<?php
namespace App\Http\Controllers\Api;
use App\User;
use App\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Quotation;
use Illuminate\Support\Str;

/* For Paypal */
use Illuminate\Support\Facades\Redirect;
use Validator;
use Session;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
/* End For Paypal */

/* Start FFMpeg */
use FFMpeg;
/* End FFMpeg */


class AuthController extends Controller
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
	const WALL_FULL_PATH = '/assets/images/wall/';
	const PARK_EXPERIENCE_FULL_PATH = '/assets/park_experience/';
	
	const CHALLANGE_FULL_PATH = '/assets/challange/';
	const CHALLANGE_THUMBNAIL_PATH = '/';
	const WALL_THUMBNAIL_PATH = '/';
	const PARK_GALARY_PATH = '/';

   public function register(Request $request)
   {
if($request->user_type==2){
    $validatedData = $request->validate([
           'name'=>'required|max:55',
           'email'=>'email|required|unique:users',
           'user_name'=>'required|unique:users',  
           'password'=>'confirmed',
           'social_id'=>'',
		   'provider'=>'',
           'profile_picture'=>'',
           'user_type'=>'required|integer|min:2|max:4',
           'country_code'=>'required|max:6',
           'phone_number'=>'required|max:14',
           'gender'=>'required|max:6',
           'dob'=>'required',
           'height'=>'',
           'weight'=>'',
           'about'=>'',
           'address'=>'',
           'lat'=>'',
           'lon'=>'',
           'device_type'=>'',
           'device_token'=>''
       ]); 
}	
if($request->user_type==3){
	$validatedData = $request->validate([
           'name'=>'required|max:55',
           'email'=>'email|required|unique:users',
           'password'=>'confirmed',
           'social_id'=>'',
		   'provider'=>'',
           'profile_picture'=>'',
           'user_type'=>'required|integer|min:2|max:4',
           'country_code'=>'required|max:6',
           'phone_number'=>'required|max:14',
           'gender'=>'required|max:6',
           'dob'=>'required',
           'height'=>'',
           'weight'=>'',
           'about'=>'',
           'address'=>'',
           'lat'=>'',
           'lon'=>'',
           'device_type'=>'',
           'device_token'=>''
       ]); 
}   
	    /*  
			if($validatedData->fails()){
				echo "avnish";
			} 
			die; 
	    */
	   
       $validatedData['password'] = bcrypt($validatedData['password']);
       $file = $request->file('profile_picture');
 
		$user_id = Str::random(12);
		$validatedData['user_id'] = strtoupper($user_id);
		if($request->user_type==3){
			$validatedData['trainer_category']=$request->trainer_category;
		}
/*
print_r($validatedData);
DB::enableQueryLog(); 
*/
	if(!empty($file)){
       $fileNewName = time().mt_rand().'.'.$file->getClientOriginalExtension();
       //Move Uploaded File
       $destinationPath = 'uploads/profile_pic/';
       $file->move($destinationPath,$fileNewName);
       $validatedData['profile_picture'] = $fileNewName;
	   $user = User::create($validatedData);
       $user['profile_picture'] = url('/').self::PROFILE_IMAGE_PATH . $user['profile_picture'];
	} else {
		 $validatedData['profile_picture'] = null;
		 $user = User::create($validatedData);
         $user['profile_picture'] = null;
	}
	
/* $query = DB::getQueryLog();
print_r($query);
die; */  
			//$otp = rand(1000,1000);
			$otp = 1111;
			$otpData = array(
				'email'=>$request->email,
				'otp'=>$otp,
				'created_at'=>date('Y-m-d h:i:s')
			);
	        $create_otp = Otp::create($otpData);
       $accessToken = $user->createToken('authToken')->accessToken;
	   	   
	   $user['user_type']=(int)$user['user_type'];
	   if($user['user_type']!=3){
		   unset($user['trainer_category']);
	   } 
	   if($user['user_type']==3){
			$user['star_rating'] = 4.5;
			$user['trainer_category'] = (int)$user['trainer_category'];
			$user['cover_image'] = $user['profile_picture'];
			$trainerGalaryImages = DB::table('trainer_galary_images')
					->select('id','title','image')
					->where(['user_id'=>$user['user_id']])->get();
					if(!empty($trainerGalaryImages)){
						foreach($trainerGalaryImages as $ky=>$trgalimag){
							$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
						}
					} 
				$user['galary_image'] = $trainerGalaryImages;
			$user['distance']=2;
			$trainerVideos = DB::table('trainer_videos')
					->select('id','title','video','duration','thumbnail')
					->where(['user_id'=>$user['user_id']])->get();
					if(!empty($trainerVideos)){
						foreach($trainerVideos as $ky=>$trvid){
							$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
							$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
						}
					} 
				$user['videos'] = $trainerVideos;
				
			$trainerAchievements = DB::table('trainer_achievements')
					->select('id','achievement')
					->where(['user_id'=>$user['user_id']])->get();
			$user['achievements'] = $trainerAchievements;
			$user['location_service'] = $this->trainerAttchedPark($user['user_id']); 
			$diff = (date('Y') - date('Y',strtotime($user['dob'])));
			$user['age'] = $diff;
			unset($user['address']);  
$user['currency']               = "$";
$user['preffered_date_time']    = null;
$user['single_membership_rate'] = null;
$user['group_membership_rate']  = null;
$user['email_verified_at']      = null;
$user['status']                 = 1; 
$user['active_status']          = 1;
$user['profile_status']         = 0;
			
	   }
	   //unset($user['id']);
       return response(['STATUS'=>'true','message'=>'','response'=>$user,'access_token'=>$accessToken]);
   }
   
   public function login(Request $request)
   {
        $loginData = $request->validate([
            'email'=>'email|required',
            'password'=>'required',
            'device_type'=>'required',
            'device_token'=>'',
			'lat'=>'required',
			'lon'=>'required'
        ]);
        
        $updateDeviceInfo = DB::table('users')
            ->where('email',$request->email)
            ->update(['device_type'=>$request->device_type,'device_token'=>$request->device_token,'lat'=>$request->lat,'lon'=>$request->lon]);
        if(!auth()->attempt($loginData)){
            return response(['STATUS'=>'false','message'=>'Invalide Credentials']);
        }
        $userInfo = auth()->user();
        $coverImg = $userInfo['profile_picture'];
        $userInfo['profile_picture'] = url('/').self::PROFILE_IMAGE_PATH . $userInfo['profile_picture'];
		if($userInfo['user_type']!=3){
		   unset($userInfo['trainer_category']);
	    }
//print_r($userInfo);die;
        /* If trainer then fetch below data */
	if($userInfo['user_type']==3){
		$userInfo['star_rating'] = 4.5;
		$userInfo['cover_image']=url('/').self::COVER_IMAGE_FULL_PATH.$coverImg;
		$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$userInfo['user_id']])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
			$userInfo['galary_image'] = $trainerGalaryImages;
		$userInfo['distance']=2;
		$trainerVideos = DB::table('trainer_videos')
				->select('id','title','video','duration','thumbnail')
				->where(['user_id'=>$userInfo['user_id']])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			$userInfo['videos'] = $trainerVideos;
			$userInfo['home_service_locations'] = $this->getHomeServiceInfoById($userInfo['user_id']);
			
		$trainerAchievements = DB::table('trainer_achievements')
				->select('id','achievement')
				->where(['user_id'=>$userInfo['user_id']])->get();
		$userInfo['achievements'] = $trainerAchievements;
		$userInfo['location_service'] = $this->trainerAttchedPark($userInfo['user_id']); 
		$diff = (date('Y') - date('Y',strtotime($userInfo['dob'])));
		$userInfo['age'] = $diff;
		unset($userInfo['address']);
	}
	//unset($userInfo['id']);
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['STATUS'=>'true','message'=>'Login Successful','response'=>$userInfo,'access_token'=>$accessToken]);
   }

   public function socialLogin(Request $request)
   {
        $socialId = $request->validate([
            'social_id'=>'required',
            'device_type'=>'required',
            'device_token'=>'',
			'lat'=>'',
			'lon'=>'',
			'provider'=>'required'
        ]);
		
        
        $user = User::select('*')->where(['social_id'=>$request->social_id,'provider'=>$request->provider])->first();
        if(!empty($user)){
			$updateDeviceInfo = DB::table('users')
        ->where('email',$request->email)
        ->update(['device_type'=>$request->device_type,'device_token'=>$request->device_token,'lat'=>$request->lat,'lon'=>$request->lon]);
			$user['profile_picture'] = url('/').self::PROFILE_IMAGE_PATH . $user['profile_picture'];
			$accessToken = $user->createToken('authToken')->accessToken;
		if($user['user_type']==3){
			$user['location_service'] = $this->trainerAttchedPark($user['user_id']); 
			$user['home_service_locations'] = $this->getHomeServiceInfoById($user['user_id']);
		}
			return response(['STATUS'=>'true','message'=>'Login Successful','response'=>$user,'access_token'=>$accessToken]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Invalide Credentials']);
		}
    }

	public function updateOtpStatus(Request $request)
	{
		$count = DB::table('otps')->where(['email'=>$request->email,'otp'=>$request->otp])->count();
		$upadtedate = date('Y-m-d h:i:s');
		if($count==1){
			$updateOtpStatus = DB::table('otps')->where('email',$request->email)->update(['otp_status'=>1,'updated_at'=>$upadtedate]);
			if($updateOtpStatus){
				 return response(['STATUS'=>'true','message'=>'Otp verified.']);
			} else{
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}
		} else {
			return response(['STATUS'=>'false','message'=>'Otp not matched.']);
		}
	}
	
	
   public function userDeatils()
   {
        $user = auth()->user();
		if($user['user_type']!=3){
		   unset($user['trainer_category']);
	    }
		if($user['user_type']==3){
			$trainerAchievements = DB::table('trainer_achievements')
					->select('id','achievement')
					->where(['user_id'=>$user['user_id']])->get();
			$user['achievements'] = $trainerAchievements;
			
			$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$user['user_id']])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
			$user['galary_image'] = $trainerGalaryImages;
		
		$trainerVideos = DB::table('trainer_videos')
				->select('id','title','video','duration','thumbnail')
				->where(['user_id'=>$user['user_id']])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			$user['videos'] = $trainerVideos;
			$user['star_rating']=4.5;
			$user['cover_image']=url('/').self::COVER_IMAGE_FULL_PATH.$user['profile_picture'];
			$user['location_service'] = $this->trainerAttchedPark($user['user_id']); 
			$user['home_service_locations'] = $this->getHomeServiceInfoById($user['user_id']);
		}
        $user['profile_picture'] = url('/').self::PROFILE_IMAGE_PATH . $user['profile_picture'];
		
        return response(['STATUS'=>'true','message'=>'','response'=>$user]);
   }
   
    public function gymCaregory(){
		$catrgories = DB::table('gym_category')->where(['status'=>1])
						->select('id','category_name','category_image')
						->get();
		foreach($catrgories as $key=>$val){
			$catId = $val->id;
			$count = DB::table('gym_users')->where(['gym_category'=>$catId])->count();
			$catrgories[$key]->category_image = url('/').self::COVER_IMAGE_FULL_PATH . $val->category_image;
			$catrgories[$key]->options = $count;
		}
        if(!empty($catrgories)){
			return response(['STATUS'=>'true','message'=>'Gym Categories.','response'=>$catrgories]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Category.']);
		}
   }
    public function gymLists(Request $request){

		$today = date("l");
		$currentDay = strtoupper($today);
		$socialId = $request->validate([
            'category_id'=>'required',
			'lat'=>'required',
			'lon'=>'required',
			'radius'=>'required'
        ]);
		/* $gymLists = DB::table('gym_users')->where(['active_status'=>1,'gym_category'=>$request->category_id])
					->select('user_id as gym_id','gym_title','cover_image','brief_description')
					->get(); */
		$gymLists = $this->search_gym_near_by_users($request->lat,$request->lon,$request->radius,$request->category_id);
		//print_r($gymLists);die; 
		foreach($gymLists as $key=>$gymList){
			$gymLists[$key]->cover_image=url('/').self::COVER_IMAGE_FULL_PATH.$gymList->cover_image;
			$gymLists[$key]->brief_description = strip_tags($gymList->brief_description);
			$gymLists[$key]->star_rating=4.5;
			$gymLists[$key]->distance = $this->distance($request->lat,$request->lon,$gymLists[$key]->lat,$gymLists[$key]->lon);
			$open_close = DB::table('gym_open_close_time')->where(['user_id'=>$gymList->gym_id,'day'=>$currentDay])->first();
			/* $gymLists[$key]->gym_equipment = DB::table('gym_equipment_and_quantity')
                ->join('gym_equipments', 'gym_equipment_and_quantity.equipment_id', '=', 'gym_equipments.id')
                ->select('gym_equipment_and_quantity.*','gym_equipments.id as equipment_id','gym_equipments.*')
				->where(['gym_equipment_and_quantity.user_id'=>$gymList->gym_id])
                ->get(); */
				$openclose = json_decode($open_close->open_close_time);
				$openclose = implode(', ',$openclose);
			$gymLists[$key]->open_close = $openclose;
		}
		
        if(!empty($gymLists)){
			return response(['STATUS'=>'true','message'=>'Gym Lists.','response'=>$gymLists]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Gym.']);
		}
    }
   
    public function gymDetails(Request $request){
		$user = auth()->user();
		$gymLists = DB::table('gym_users')->where(['active_status'=>1,'user_id'=>$request->gym_id])
		->select('gym_users.user_id as gym_id','gym_users.*')
		->get();
		foreach($gymLists as $key=>$gymList){
			/* $subscription = array(
					'subscription_title'=>$gymList->subscription_title,
					'subscription_amount'=>$gymList->subscription_amount,
					'subscription_month'=>$gymList->subscription_month,
					'subscription_details'=>$gymList->subscription_details
				); */
			
			$subscription = array(
				array(
					'subscription_title'=>"1 Month",
					'subscription_amount'=>$gymList->subscription_amount,
					'subscription_month'=>1,
					'subscription_details'=>$gymList->subscription_details
				),
				array(
					'subscription_title'=>"3 Months",
					'subscription_amount'=>$gymList->subscription_amount,
					'subscription_month'=>3,
					'subscription_details'=>$gymList->subscription_details
				),
				array(
					'subscription_title'=>"6 Months",
					'subscription_amount'=>$gymList->subscription_amount,
					'subscription_month'=>6,
					'subscription_details'=>$gymList->subscription_details
				),
				array(
					'subscription_title'=>"12 Months",
					'subscription_amount'=>$gymList->subscription_amount,
					'subscription_month'=>12,
					'subscription_details'=>$gymList->subscription_details
				)
			);
			$gymLists[$key]->subscription = $subscription;
			
			
			unset($gymLists[$key]->subscription_title,$gymLists[$key]->subscription_amount,$gymLists[$key]->subscription_month,$gymLists[$key]->subscription_details);
			$gymLists[$key]->star_rating = 4.5;
			$gymLists[$key]->cover_image=url('/').self::COVER_IMAGE_FULL_PATH.$gymList->cover_image;
			//$gymLists[$key]->galary_image=url('/').self::GALARY_IMAGE_FULL_PATH.$gymList->galary_image;
			$gymLists[$key]->distance = $this->distance($user->lat,$user->lon,$gymList->lat,$gymList->lon);
			//$gymLists[$key]->open_close = DB::table('gym_open_close_time')->where(['user_id'=>$request->gym_id])->get();
			
			$open_close = DB::table('gym_open_close_time')->where(['user_id'=>$request->gym_id])->first();
			/* $gymLists[$key]->gym_equipment = DB::table('gym_equipment_and_quantity')
                ->join('gym_equipments', 'gym_equipment_and_quantity.equipment_id', '=', 'gym_equipments.id')
                ->select('gym_equipment_and_quantity.*','gym_equipments.id as equipment_id','gym_equipments.*')
				->where(['gym_equipment_and_quantity.user_id'=>$gymList->gym_id])
                ->get(); */
				$openclose = json_decode($open_close->open_close_time);
				$openclose = implode(', ',$openclose);
			$gymLists[$key]->open_close = $openclose;
			
			$gymLists[$key]->brief_description = strip_tags($gymList->brief_description);
			$gymLists[$key]->full_description = strip_tags($gymList->full_description);
			
			$galaryImg = DB::table('gym_galary_images')
				->select('id','image')
				->where(['gym_id'=>$request->gym_id])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$gmig){
						$galaryImg[$ky]->image = url('/').self::GALARY_IMAGE_FULL_PATH.$gmig->image;
					}
				} 
			$gymLists[$key]->galary_image = $galaryImg; 
			$equip = DB::table('gym_equipment_and_quantity')
                ->join('gym_equipments', 'gym_equipment_and_quantity.equipment_id', '=', 'gym_equipments.id')
                ->select('gym_equipment_and_quantity.*','gym_equipments.id as equipment_id','gym_equipments.*')
				->where(['gym_equipment_and_quantity.user_id'=>$request->gym_id])
                ->get(); 
			foreach($equip as $ky=>$equipm){
				$equip[$ky]->equipment_image = url('/').self::EQUIPMENTS_IMAGE_FULL_PATH.$equipm->equipment_image;
			}
				$gymLists[$key]->gym_equipment = $equip;
				
				$gymLists[$key]->user_list = $this->userListByGymId($request->gym_id);
		}
		
        if(!empty($gymLists)){
			//$gymListWithoutNull = $this->filter_null((array)$gymLists[0]);
			return response(['STATUS'=>'true','message'=>'Gym Lists.','response'=>$gymLists[0]]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Gym.']);
		}
    }
	function userListByGymId($gymId){
		
		$subscribedUsers = DB::table('subscription_payments')
		   ->join('users', 'subscription_payments.user_id', '=', 'users.user_id')
		   ->select('subscription_payments.id as subId','subscription_payments.user_id','subscription_payments.start_date','subscription_payments.gym_id','subscription_payments.months','users.name','users.profile_picture')->where(['gym_id'=>$gymId])->get();
		/* $subscribedUsers = DB::table('subscription_payments')->where(['gym_id'=>$gymId])->select('subscription_payments.user_id','subscription_payments.start_date','subscription_payments.gym_id','subscription_payments.months')->get();  */
		$gymActiveUser = array();
		foreach($subscribedUsers as $key=>$subscription){
			$months = '+'.$subscription->months.' months';
			$ENDATE = strtotime($months,strtotime($subscription->start_date));
			$endDate = date("Y-m-d",$ENDATE);
			if($endDate > date('Y-m-d')){
				$gymActiveUser[] = array (
					'id'=>$subscription->subId,
					'user_id'=>$subscription->user_id,
					'gym_id'=>$subscription->gym_id,
					'start_date'=>$subscription->start_date,
					'end_date'=>$endDate,
					'months'=>$subscription->months,
					'name'=>$subscription->name,
					'profile_picture'=>url('/').self::PROFILE_IMAGE_PATH . $subscription->profile_picture
				);
				//$users = array('user_id'=>$subscription->user_id);
			}
		}
		/* $ids = array_column($gymActiveUser, 'user_id');
		$ids = array_unique($ids);
		$gymActiveUser = array_filter($gymActiveUser, function ($key, $value) use ($ids) {
			return in_array($value, array_keys($ids));
		}, ARRAY_FILTER_USE_BOTH); */
 
		$temp = array_unique(array_column($gymActiveUser, 'user_id'));
		$gymActiveUser = array_intersect_key($gymActiveUser, $temp);
		return $gymActiveUser;
	}
	public function trainerLists(Request $request){
		
		/* $trainerLists = DB::table('users')->where(['active_status'=>1,'user_type'=>3,'profile_status'=>1,'trainer_category'=>$request->trainer_category])->select('user_id','name','profile_picture','about')->get(); */
		if($request->trainer_category==2){
			$trainerLists = $this->search_trainer_near_by_users($request->lat,$request->lon,$request->radius,$request->trainer_category);
		
			foreach($trainerLists as $key=>$traiList){
				$trainerLists[$key]->profile_picture = url('/').self::PROFILE_IMAGE_FULL_PATH.$traiList->profile_picture;
				$trainerLists[$key]->star_rating=4.5;
				$trainerLists[$key]->distance = $this->distance($request->lat,$request->lon,$traiList->lat,$traiList->lon);
				$trainerLists[$key]->location_service = $this->trainerAttchedPark($traiList->user_id); 
				/* Location Service For Home Service */
				$trainerLists[$key]->home_service_locations = $this->getHomeServiceInfoById($traiList->user_id);
			}
		} else if($request->trainer_category==1 OR $request->trainer_category==3){
			if($request->park_id==0){
				$parkType1 = 1;	$parkType2 = 2;	$both = 3;
				$parks = $this->search_park_near_by_users($request->lat,$request->lon,$request->radius,$parkType1,$parkType2,$both);
				//print_r($parks);die;
				$park_ids = array();
				foreach($parks as $key=>$parkIds){
					$park_ids[]= $parkIds->id;
				}
				$trainerLists = DB::table('users')
						->join('trainer_park', 'users.user_id', '=', 'trainer_park.user_id')
						->select('users.user_id','users.name','users.profile_picture','users.about')
						->whereIn('trainer_park.park_id',$park_ids)
						->get();
				 
				foreach($trainerLists as $key=>$traiList){
					$trainerLists[$key]->profile_picture = url('/').self::PROFILE_IMAGE_FULL_PATH.$traiList->profile_picture;
					$trainerLists[$key]->star_rating=4.5;
					$trainerLists[$key]->distance = $this->distance($request->lat,$request->lon,$traiList->lat,$traiList->lon);;
					/* Location Service - Home Service */
					$trainerLists[$key]->home_service_locations = $this->getHomeServiceInfoById($traiList->user_id);
					//$trainerLists[$key]->location_service = $this->trainerAttchedPark($traiList->user_id); 
					$trainerLists[$key]->location_service = $this->parkAttachedTrainerByParkId($park_ids); 
				}
			} else {
				//search park by id
				$park_ids = explode(',',$request->park_id);
				//print_r($park_ids);die;
				$trainerLists = DB::table('users')
						->join('trainer_park', 'users.user_id', '=', 'trainer_park.user_id')
						->select('users.user_id','users.name','users.profile_picture','users.about')
						->whereIn('trainer_park.park_id',$park_ids)
						->get();
				 
				foreach($trainerLists as $key=>$traiList){
					$trainerLists[$key]->profile_picture = url('/').self::PROFILE_IMAGE_FULL_PATH.$traiList->profile_picture;
					$trainerLists[$key]->star_rating=4.5;
					$trainerLists[$key]->distance = $this->distance($request->lat,$request->lon,$traiList->lat,$traiList->lon);;
					/* Location Service - Home Service */
					$trainerLists[$key]->home_service_locations = $this->getHomeServiceInfoById($traiList->user_id);
					//$trainerLists[$key]->location_service = $this->trainerAttchedPark($traiList->user_id); 
					$trainerLists[$key]->location_service = $this->parkAttachedTrainerByParkId($park_ids); 
				}
			}
		}
		 
        if(!empty($trainerLists)){
			return response(['STATUS'=>'true','message'=>'Trainer Lists.','response'=>$trainerLists]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Trainer List.']);
		}
    }
	function parkAttachedTrainerByParkId($parkIds){
		$parks = DB::table('trainer_park')
                ->join('parks', 'trainer_park.park_id', '=', 'parks.id')
                ->select('trainer_park.id','parks.id as park_id','parks.park_name','parks.park_type','parks.lat','parks.lon','parks.park_image','parks.park_address','trainer_park.id')
				//->where(['trainer_park.user_id'=>$userId])
				->whereIn('trainer_park.park_id',$parkIds)
                ->get(); 
			foreach($parks as $key=>$park){
				$parks[$key]->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
			}
		return $parks;
	}
	function get_trainer_admin_rates(){
		$result = DB::table('trainer_membership_rate')->where(['id'=>1])->select('single_rate','group_rate','group_count')->first();
		return $result;
	}
	public function trainerDetails(Request $request){
		$user = auth()->user();
		$trainerDeatils = DB::table('users')->where(['active_status'=>1,'user_id'=>$request->trainer_user_id])
		->select('user_id','name','email','email_verified_at','profile_picture','country_code','phone_number','gender','dob','height','weight','about','preffered_date_time','achievements','currency','single_membership_rate','group_membership_rate','lat','lon','device_type','active_status','star_rating','trainer_category')
		->first();
		
		$trainerAdminRates = $this->get_trainer_admin_rates();
		$trainerDeatils->group_count = $trainerAdminRates->group_count;
		
		$trainerDeatils->star_rating=4.5;
		$trainerDeatils->profile_picture=url('/').self::PROFILE_IMAGE_PATH.$trainerDeatils->profile_picture;
		$trainerDeatils->cover_image=$trainerDeatils->profile_picture;
		//$trainerDeatils->galary_image=url('/').self::GALARY_IMAGE_FULL_PATH.$galary_image;
		$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$request->trainer_user_id])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
			$trainerDeatils->galary_image = $trainerGalaryImages;
		
		$trainerDeatils->distance = $this->distance($user->lat,$user->lon,$trainerDeatils->lat,$trainerDeatils->lon);
		
		$trainerVideos = DB::table('trainer_videos')
				->select('id','title','video','duration','thumbnail')
				->where(['user_id'=>$request->trainer_user_id])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			$trainerDeatils->videos = $trainerVideos;
		$trainerAchievements = DB::table('trainer_achievements')
				->select('id','achievement')
				->where(['user_id'=>$request->trainer_user_id])->get();
		$trainerDeatils->achievements = $trainerAchievements;
			
		$diff = (date('Y') - date('Y',strtotime($trainerDeatils->dob)));
		$trainerDeatils->age = $diff;
		$trainerDeatils->location_service = $this->trainerAttchedPark($trainerDeatils->user_id); 
		
		$trainerDeatils->home_service_locations = $this->getHomeServiceInfoById($trainerDeatils->user_id);
					
		
//print_r($trainerDeatils);die;
        if(!empty($trainerDeatils)){
			return response(['STATUS'=>'true','message'=>'Trainer Information.','response'=>$trainerDeatils]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Trainer Info.']);
		}
    }
	/* User and Trainer Profile update */
	public function userTrainerProfileUpdate(Request $request){
	$request->validate([
			'name'=>'',
			'gender'=>'',
			'country_code'=>'',
			'phone_number'=>'',
			'dob'=>'',
			'height'=>'',
			'weight'=>'',
			'about'=>'',
			'address'=>'',
			'preffered_date_time'=>'',
			'trainer_category'=>'',
            'profile_picture'=>'',
			'paypal_id'=>'',
		    'lat'=>'', 
		    'lon'=>'',
	]);
   
	$validatedData['name'] = $request->name;
	$validatedData['gender'] = $request->gender;
	$validatedData['country_code'] = $request->country_code;
	$validatedData['phone_number'] = $request->phone_number;
	$validatedData['dob'] = $request->dob;
	$validatedData['height'] = $request->height;
	$validatedData['weight'] = $request->weight;
	$validatedData['about'] = $request->about;
	$validatedData['address'] = $request->address;
	$validatedData['preffered_date_time'] = $request->preffered_date_time;
	$validatedData['trainer_category'] = $request->trainer_category;
	$validatedData['paypal_id'] = $request->paypal_id;

	$validatedData['lat'] = $request->lat;
	$validatedData['lon'] = $request->lon;
	$validatedData['updated_at'] = date('Y-m-d h:i:s');
	
			$file = $request->file('profile_picture');
			if(!empty($file)){
				$fileNewName = time().mt_rand().'.'.$file->getClientOriginalExtension();
				//Move Uploaded File
				$destinationPath = 'uploads/profile_pic/';
				$file->move($destinationPath,$fileNewName);
				$validatedData['profile_picture'] = $fileNewName;
			}
	/* DB::enableQueryLog(); */
		$updateProfile = DB::table('users')
			->where('user_id',$request->user_id)->update($validatedData);
/* 	$query = DB::getQueryLog();
 print_r($query);
die; */
			
		if($updateProfile){
/* DB::enableQueryLog(); */
			$userTypeInfo = $this->getUserTypeByUserId($request->user_id);
/* 	$query = DB::getQueryLog();
 print_r($query);
die; */			
			if($userTypeInfo->user_type==3){
				$updatedInfo = $this->getTrainerInfo($request->user_id);
				$updatedInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$updatedInfo->profile_picture; 
				$updatedInfo->location_service = $this->trainerAttchedPark($updatedInfo->user_id); 
				
			} else {
/* DB::enableQueryLog();  */
				$updatedInfo = $this->getUserInfo($request->user_id);
/* $query = DB::getQueryLog();
 print_r($query);
die;  */
				$updatedInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$updatedInfo->profile_picture; 
			}
/* print_r($updatedInfo);die; */
			return response(['STATUS'=>'true','message'=>'Profile Updated Successfully.','response'=>$updatedInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
}
/* 	public function userTrainerProfileUpdatexx(Request $request){
	    $array = array(
			'name'=>$request->name,
			'gender'=>$request->gender,
			'country_code'=>$request->country_code,
			'phone_number'=>$request->phone_number,
			'dob'=>$request->dob,
			'height'=>$request->height,
			'weight'=>$request->weight,
			'about'=>$request->about,
			'address'=>$request->address,
			'preffered_date_time'=>$request->preffered_date_time,
			'trainer_category'=>$request->trainer_category,
			'updated_at'=>date('Y-m-d h:i:s')
	    );
		
		if($request->lat!='' AND $request->lon!=''){
			$array = array(
				'lat'=>$request->lat,
				'lon'=>$request->lon
			);
		}
		$file = $request->file('profile_picture');
		if(!empty($file)){
		   $fileNewName = time().mt_rand().'.'.$file->getClientOriginalExtension();
		   //Move Uploaded File
		   $destinationPath = 'uploads/profile_pic/';
		   $file->move($destinationPath,$fileNewName);
		   $array['profile_picture'] = $fileNewName;
		}
		
		$updateProfile = DB::table('users')
			->where('user_id',$request->user_id)->update($array);
		if($updateProfile){
			$userTypeInfo = $this->getUserTypeByUserId($request->user_id);
			if($userTypeInfo->user_type==3){
				$updatedInfo = $this->getTrainerInfo($request->user_id);
			} else {
				$updatedInfo = $this->getUserInfo($request->user_id);
			}
			return response(['STATUS'=>'true','message'=>'Profile Updated Successfully.','response'=>$updatedInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
    } */
	/* Add Video */
	public function trainerVideo(Request $request){
		$validatedData =$this->validate($request,[
				'user_id'=>'required',
				'video'=>'required'
			]);
		$validatedData['title'] = $request->title;
		$file = $request->file('video');
		$validatedData['created_at'] = date('Y-m-d h:i:s');	
			
		if(!empty($file)){
		   $videoFileNewName = time().mt_rand().'.'.$file->getClientOriginalExtension();
		   //Move Uploaded File
		   $destinationPath = 'uploads/trainer_video/';
		   $file->move($destinationPath,$videoFileNewName);
		   $validatedData['video'] = $videoFileNewName;
		   
/* Start Create Thumbnail */
	$file = url('/').self::TRAINER_VIDEO_FULL_PATH . $videoFileNewName;
	$thumbnailName = time().$request->user_id.'thumbnail.png';
	$thumbnail = $this->createThumbnail($file,$thumbnailName);
	$validatedData['duration']  = $thumbnail['duration'];
	$validatedData['thumbnail'] = $thumbnail['thumbnail_name'];
/* End Create Thumbnail */
		   
		   
		   $video = DB::table('trainer_videos')->insertGetId($validatedData);
		}
	
	$trainerVideos = DB::table('trainer_videos')
				->select('id','video','title','duration','thumbnail')
				->where(['user_id'=>$request->user_id])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			
		if($video){
			return response(['STATUS'=>'true','message'=>'Video Added Successfully.','response'=>$trainerVideos]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}		
	} 	
	/* Add Help Message */
	public function helpMessage(Request $request){
		$validatedData =$this->validate($request,[
				'user_id'=>'required',
				'subject'=>'required',
				'message'=>'required'
			]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');	
		$helpMeaasge = DB::table('help_message')->insertGetId($validatedData);
		
		if($helpMeaasge){
			return response(['STATUS'=>'true','message'=>'Message Sent Successfully.']);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
		
	}
	/* Update Lat Lon */
	public function updateLocation(Request $request){
		$validatedData =$this->validate($request,[
				'user_id'=>'required',
				'lat'=>'required',
				'lon'=>'required'
			]);	   

	   $array = array(
			'lat'=>$request->lat,
			'lon'=>$request->lon,
			'updated_at'=>date('Y-m-d h:i:s')
	    );
		
		$updateLocation = DB::table('users')
			->where('user_id',$request->user_id)->update($array);
		if($updateLocation){
			return response(['STATUS'=>'true','message'=>'Location Updated Successfully.']);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
    }
	/* Add Galary Image */
	public function trainerGalaryImages(Request $request){
		$validatedData =$this->validate($request,[
				'user_id'=>'required',
				'image'=>'required'
			]);
		$validatedData['title'] = $request->title;
		$file = $request->file('image');
		$validatedData['created_at'] = date('Y-m-d h:i:s');	
		if(!empty($file)){
		   $galaryFileNewName = time().mt_rand().'.'.$file->getClientOriginalExtension();
		   //Move Uploaded File
		   $destinationPath = 'uploads/trainer_galary_image/';
		   $file->move($destinationPath,$galaryFileNewName);
		   $validatedData['image'] = $galaryFileNewName;
		   $images = DB::table('trainer_galary_images')->insertGetId($validatedData);
		}
	$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$request->user_id])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
		if($images){
			return response(['STATUS'=>'true','message'=>'Galary Image Added Successfully.','response'=>$trainerGalaryImages]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}		
	} 
	/* Add Achievement */
	public function trainerAddAchievement(Request $request){
		$validatedData =$this->validate($request,[
				'user_id'=>'required',
				'achievement'=>'required',
			]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');		
		$id = DB::table('trainer_achievements')->insertGetId($validatedData);
		if($id){
			$trainerAchievement = DB::table('trainer_achievements')
				->select('id','achievement')
				->where(['user_id'=>$request->user_id])->get();
				 
			return response(['STATUS'=>'true','message'=>'Achievement Added Successfully.','response'=>$trainerAchievement]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
/* Remove Achievement */
	public function removeTrainerAchievement(Request $request){
		$reslut = DB::table('trainer_achievements')->where(['user_id'=>$request->user_id,'id'=>$request->id])->delete();
		if($reslut){
			$trainerAchievement = DB::table('trainer_achievements')
				->select('id','achievement')
				->where(['user_id'=>$request->user_id])->get();

			return response(['STATUS'=>'true','message'=>'Achievement Deleted Successfully.','response'=>$trainerAchievement]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}	
	/* Add or Update Trainer membership rate  */
    public function trainerMembershipRate(Request $request){
		$validatedData =$this->validate($request,[
			'single_membership_rate'=>'required',
			'group_membership_rate'=>'required',
		]);
		$validatedData['updated_at'] = date('Y-m-d h:i:s');
		$validatedData['profile_status'] = 1;
		
		$updateProfile = DB::table('users')
			->where('user_id',$request->user_id)->update($validatedData);
		if($updateProfile){
			return response(['STATUS'=>'true','message'=>'Membership Rate Updated Successfully.']);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
    }
	/* Remove galary image */
	public function removeTrainerGalaryImages(Request $request){
		$reslut = DB::table('trainer_galary_images')->where(['user_id'=>$request->user_id,'id'=>$request->id])->delete();
		if($reslut){
			$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$request->user_id])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
			return response(['STATUS'=>'true','message'=>'Image Deleted Successfully.','response'=>$trainerGalaryImages]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	/* Remove Video */
	public function removeTrainerVideo(Request $request){
		$reslut = DB::table('trainer_videos')->where(['user_id'=>$request->user_id,'id'=>$request->id])->delete();
		if($reslut){
			$trainerVideos = DB::table('trainer_videos')
				->select('id','video','title','duration','thumbnail')
				->where(['user_id'=>$request->user_id])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			return response(['STATUS'=>'true','message'=>'Video Deleted Successfully.','response'=>$trainerVideos]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	/* Coupon Codes */
	public function couponCodes(Request $request){
		if($request->type==0){
			$couponLists = DB::table('coupon_code')->where(['active_status'=>1])
					->select('id','currency','title','coupon_code','brief_description','coupon_type','coupon_category','amount','percentage')
					//->where(['coupon_category'=>$request->type]) 
					->get();
			foreach($couponLists as $key=>$coupon){
				$couponLists[$key]->brief_description = strip_tags($coupon->brief_description);
				$couponLists[$key]->coupon_category = (int)$coupon->coupon_category;
			}
		}else{
			$couponLists = DB::table('coupon_code')->where(['active_status'=>1])
					->select('id','currency','title','coupon_code','brief_description','coupon_type','coupon_category','amount','percentage')
					->where(['coupon_category'=>$request->type]) 
					->get();
			foreach($couponLists as $key=>$coupon){
				$couponLists[$key]->brief_description = strip_tags($coupon->brief_description);
				$couponLists[$key]->coupon_category = (int)$coupon->coupon_category;
			}
		}
        if(!empty($couponLists)){
			return response(['STATUS'=>'true','message'=>'Coupon Lists.','response'=>$couponLists]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Coupon.']);
		}
    }
	
	/* Accessories Lists */
	public function accessoriesLists(){
		
		$accessoriesLists = DB::table('accessories')->where(['active_status'=>1])
					->select('id','accessorie_id','currency','accessorie_title','sub_title','brief_description','full_description','cover_image','amount','per')
					->get();
		
		foreach($accessoriesLists as $key=>$accessoriesList){
			$accessoriesLists[$key]->cover_image = url('/').self::ACCESSORIES_COVER_IMAGE_FULL_PATH.$accessoriesList->cover_image;
			$accessoriesLists[$key]->star_rating=4.5;
			
			
				$accessoriesLists[$key]->brief_description = strip_tags($accessoriesList->brief_description);
				$accessoriesLists[$key]->full_description = strip_tags($accessoriesList->full_description);
			
			/* $galaryImg = DB::table('accessories_gallery_images')
				->select('id','image')
				->where(['accessorie_id'=>$accessoriesList->accessorie_id])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$accesimg){
						$galaryImg[$ky]->image = url('/').self::ACCESSORIES_GALARY_IMAGE_FULL_PATH.$accesimg->image;
					}
				} 
			$accessoriesLists[$key]->galary_image = $galaryImg;  */
			
		}
		
        if(!empty($accessoriesLists)){
			return response(['STATUS'=>'true','message'=>'Accessories Lists.','response'=>$accessoriesLists]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Accessories.']);
		}
    }
	/* Accessories Details */
	public function accessoriesDetails(Request $request){
		$accessoriesDetails = $this->accessoriesDetailsByAccessoriesId($request->accessorie_id);
		if(!empty($accessoriesDetails)){
			return response(['STATUS'=>'true','message'=>'Accessories Details.','response'=>$accessoriesDetails]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Accessories.']);
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
	/* Product Lists */
	public function productLists(Request $request){ 
		
		if($request->type==0){
			$productsLists = DB::table('products')->where(['active_status'=>1])
						->select('id','product_id','currency','product_category','product_title','sub_title','brief_description','full_description','cover_image','amount','per')
						->get();
		} else{
			$productsLists = DB::table('products')->where(['active_status'=>1])
						->select('id','product_id','currency','product_category','product_title','sub_title','brief_description','full_description','cover_image','amount','per')
						->where(['product_category'=>$request->type])
						->get();
		}
		foreach($productsLists as $key=>$productList){
			$productsLists[$key]->cover_image = url('/').self::PRODUCT_COVER_IMAGE_FULL_PATH.$productList->cover_image;
			$productsLists[$key]->star_rating=4.5;
			$productsLists[$key]->brief_description = strip_tags($productList->brief_description);
			$productsLists[$key]->full_description = strip_tags($productList->full_description);
			/* $galaryImg = DB::table('product_gallery_images')
				->select('id','image')
				->where(['product_id'=>$productList->product_id])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$prodimg){
						$galaryImg[$ky]->image = url('/').self::PRODUCT_GALARY_IMAGE_FULL_PATH.$prodimg->image;
					}
				} 
			$productsLists[$key]->galary_image = $galaryImg;  */
		}
        if(!empty($productsLists)){
			return response(['STATUS'=>'true','message'=>'Products Lists.','response'=>$productsLists]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Products.']);
		}
    }
	
	/* Product Details */
	public function productDetails(Request $request){
		$productDetails = $this->productdetailsByProductId($request->product_id);
		/* $productDetails = DB::table('products')->where(['active_status'=>1,'product_id'=>$request->product_id])
					->select('id','product_id','currency','product_category','product_title','sub_title','brief_description','full_description','cover_image','amount','per')
					->first();
					
		$productDetails->cover_image = url('/').self::PRODUCT_COVER_IMAGE_FULL_PATH.$productDetails->cover_image;
		$productDetails->star_rating=4.5;
		$productDetails->brief_description = strip_tags($productDetails->brief_description);
		$productDetails->full_description = strip_tags($productDetails->full_description);					
			$galaryImg = DB::table('product_gallery_images')
				->select('id','image')
				->where(['product_id'=>$request->product_id])->get();
				if(!empty($galaryImg)){
					foreach($galaryImg as $ky=>$prodimg){
						$galaryImg[$ky]->image = url('/').self::PRODUCT_GALARY_IMAGE_FULL_PATH.$prodimg->image;
					}
				} 
			$productDetails->galary_image = $galaryImg;  */
		if(!empty($productDetails)){
			return response(['STATUS'=>'true','message'=>'Product Details.','response'=>$productDetails]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Product.']);
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
	/* Home screen count */
	public function homeScreenCount(){
		
		$gymCount = DB::table('gym_users')->where(['active_status'=>1])->count();
		$discountCouponCount = DB::table('coupon_code')->where(['active_status'=>1])->count();
		$mealsCount = 10;
		$productCount = DB::table('products')->where(['active_status'=>1])->count();
		$accessoriesCount = DB::table('accessories')->where(['active_status'=>1])->count();
		
		$trainerCount = DB::table('users')->where(['user_type'=>3,'profile_status'=>1])->count();
		$freeRunTrainerCount = DB::table('users')->where(['user_type'=>3,'trainer_category'=>1,'profile_status'=>1])->count();
		$homeServiceTrainerCount = DB::table('users')->where(['user_type'=>3,'trainer_category'=>2, 'profile_status'=>1])->count();
		$parkLinkTrainerCount = DB::table('parks')->count();
		$trainerTypeCount = array(
			'free_run_count'=>$freeRunTrainerCount,
			'home_service_count'=>$homeServiceTrainerCount,
			'park_link_count'=>$parkLinkTrainerCount
		);
		$countArray = array(
			'gym_count'=>$gymCount,
			'discount_coupon_count'=>$discountCouponCount,
			'meals_count'=>$mealsCount,
			'protein_count'=>$productCount,
			'accessories_count'=>$accessoriesCount,
			'trainer_count'=>$trainerCount,
			'tariners_type_count'=>$trainerTypeCount
		);
		
		return response(['STATUS'=>'true','message'=>'Home Screen Counts.','response'=>$countArray]);
	}
	
	/* Change Password From App Inner screen */
	public function changePassword(Request $request){
		$validatedData =$this->validate($request,[
				'email'=>'email|required',
				'current_password'=>'required',
				'new_password'=>'required'
			]);


 $verifyPassword = array(
            'email'=>$request->email,
            'password'=>$request->current_password
        );
        if(!auth()->attempt($verifyPassword)){
            return response(['STATUS'=>'false','message'=>'Current Password Does Not Matched.']);
        } else {
			$updatePassword['password'] = bcrypt($validatedData['new_password']);
			$updatePassword['updated_at'] = date('Y-m-d h:i:s');
		
			$updateProfile = DB::table('users')
				->where('email',$validatedData['email'])->update($updatePassword);
			if($updateProfile){				
				return response(['STATUS'=>'true','message'=>'Password Changed Successfully.']);
			} else {
				return response(['STATUS'=>'false','message'=>'Network Issue.']);
			}	
		}
    }
	
	
	public function termsOfUseForUser(){
		$termsOfUseForUser = DB::table('pages')->where(['id'=>1])
					->select('id','title','brief_description','full_description','page_name')
					->first();
		echo utf8_encode($termsOfUseForUser->full_description);				
	}	
	public function privacyPolicy(){
		$privacyPolicy = DB::table('pages')->where(['id'=>2])
					->select('title','full_description')
					->first();
		echo utf8_encode($privacyPolicy->full_description);
	}	
	public function aboutUs(){
		$aboutUs = DB::table('pages')->where(['id'=>3])
					->select('id','title','brief_description','full_description','page_name')
					->first();
		echo utf8_encode($aboutUs->full_description);
	}	
	public function termsOfUseForTrainer(){
		$termsOfUseForTrainer = DB::table('pages')->where(['id'=>4])
					->select('id','title','brief_description','full_description','page_name')
					->first();
		echo utf8_encode($termsOfUseForTrainer->full_description);
	}
	
	public function resetPasswordForm(){
		echo "Avnish";
	}
   public function filter_null($array){
	    foreach ($array as $key => $value) {
			if (is_null($value)) {
				$array[$key] = "";
			}
		}
		return $array;
   }
   
   
   public function getUserInfo($userId){
	    $userDeatils = DB::table('users')->where(['active_status'=>1,'user_id'=>$userId])
		->select('user_id','user_name','user_type','name','email','email_verified_at','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','device_token','active_status')
		->first();
		$userDeatils->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $userDeatils->profile_picture;
		return $userDeatils;
   }
   public function getTrainerInfo($userId){
	   $user = auth()->user();
	   $trainerDeatils = DB::table('users')->where(['active_status'=>1,'user_id'=>$userId])
		->select('user_id','user_type','name','email','email_verified_at','trainer_category','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','active_status','currency','single_membership_rate','group_membership_rate','paypal_id')
		->first();

		$trainerDeatils->star_rating=4.5;
		$trainerDeatils->cover_image=url('/').self::COVER_IMAGE_FULL_PATH.$trainerDeatils->profile_picture;
		//$trainerDeatils->galary_image=url('/').self::GALARY_IMAGE_FULL_PATH.$galary_image;
		$trainerGalaryImages = DB::table('trainer_galary_images')
				->select('id','title','image')
				->where(['user_id'=>$userId])->get();
				if(!empty($trainerGalaryImages)){
					foreach($trainerGalaryImages as $ky=>$trgalimag){
						$trainerGalaryImages[$ky]->image = url('/').self::TRAINER_GALARY_IMAGE_FULL_PATH.$trgalimag->image;
					}
				} 
			$trainerDeatils->galary_image = $trainerGalaryImages;
		
		$trainerDeatils->distance = $this->distance($user->lat,$user->lon,$trainerDeatils->lat,$trainerDeatils->lon);;
		//$trainerDeatils->distance=2;
		
		$trainerVideos = DB::table('trainer_videos')
				->select('id','title','video','duration','thumbnail')
				->where(['user_id'=>$userId])->get();
				if(!empty($trainerVideos)){
					foreach($trainerVideos as $ky=>$trvid){
						$trainerVideos[$ky]->video = url('/').self::TRAINER_VIDEO_FULL_PATH.$trvid->video;
						$trainerVideos[$ky]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$trvid->thumbnail;
					}
				} 
			$trainerDeatils->videos = $trainerVideos;
		$trainerAchievements = DB::table('trainer_achievements')
				->select('id','achievement')
				->where(['user_id'=>$userId])->get();
		$trainerDeatils->achievements = $trainerAchievements;
			
		$diff = (date('Y') - date('Y',strtotime($trainerDeatils->dob)));
		$trainerDeatils->age = $diff;
		return $trainerDeatils;
   }
	function getUserTypeByUserId($userId){
		$result = DB::table('users')->where(['user_id'=>$userId])->select('user_type')->first();
		return $result;
	}
	function getUserIdByEmailId($email){
		$userDeatils = DB::table('users')->where(['email'=>$email])->select('user_id','user_type')->first();
		return $userDeatils;
	}
	
	public function addFriends(Request $request){
		$user = auth()->user();
		$friendsEmail = explode(',',$request->friends_email);
		if($request->friends_email!=''){
			foreach($friendsEmail as $key=>$email){
				$countAdreadyUser = DB::table('users')->where(['email'=>$email,'user_type'=>2])->where('user_id', '!=' , $request->user_id)->count();
				if($countAdreadyUser!=0){
					$friendInfo = $this->getUserIdByEmailId($email);
					$count = DB::table('gym_friends')->where(['user_id'=>$request->user_id,'firends_email'=>$email])->count();
					if($count==0){
						$friendData = array(
							'user_id'=>$request->user_id,
							'to_user_id'=>$friendInfo->user_id,
							'firends_email'=>$email,
							'created_at'=>date('Y-m-d h:i:s')
						);
						$id[]= DB::table('gym_friends')->insertGetId($friendData);
					}
				}
			}
			if(!empty($id)){
				return response(['STATUS'=>'true','message'=>'Friend List Updated Successfully.']);
			} else {
				return response(['STATUS'=>'true','message'=>'Friend List Already Updated.']);
			}
		} else {
			return response(['STATUS'=>'fails','message'=>'Email Address is Empty.']);
		}
    }
	public function sendFriendRequest(Request $request){
		$user = auth()->user();
		if($request->email!=''){
			$count = DB::table('gym_friends')->where(['user_id'=>$request->user_id,'firends_email'=>$request->email])->count();
			if($count==0){
				//request_status = 1 Friend
				//request_status = 2 Request Sent
				$friendInfo = $this->getUserIdByEmailId($request->email);
				$friendData = array(
					'user_id'=>$request->user_id,
					'to_user_id'=>$friendInfo->user_id,
					'firends_email'=>$request->email, 
					'request_status'=>2,
					'created_at'=>date('Y-m-d h:i:s')
				);
				$id = DB::table('gym_friends')->insertGetId($friendData);
				$notification = array(
					"user_id" =>$request->user_id, 
					"friend_id" =>$friendInfo->user_id,
					"type" =>"FRIEND",
					"message" =>"You have received friend request",
					"created_at" =>date('Y-m-d h:i:s')
				);
				$notificationSent = DB::table('fitness_notification')->insertGetId($notification);
		
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($request->user_id);
				$deviceInfo = $this->get_device_token($friendInfo->user_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 1,
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 1,
					"id"  => $request->user_id,
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */		
				
				return response(['STATUS'=>'true','message'=>'Friend Request Sent Successfully.']);
			} else{
				return response(['STATUS'=>'true','message'=>'Request Already Sent.']);
			}
		}  else {
			return response(['STATUS'=>'fails','message'=>'Email Address is Empty.']);
		}
	}
	
	function getFriendRequestDetails($requestId){
		$requestInfo = DB::table('gym_friends')->where('id',$requestId)->first();
		return $requestInfo;
	}
	
	public function acceptFriendRequest(Request $request){
		$user = auth()->user();
		$count = DB::table('gym_friends')->where(['id'=>$request->id])->count();
			if($count!=0){
				//request_status = 1 request accepted and become friend
				$updateRequestStatus = DB::table('gym_friends')->where('id',$request->id)->update(['request_status'=>1]);
				$requestInfo = $this->getFriendRequestDetails($request->id);
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($requestInfo->to_user_id);
				$deviceInfo = $this->get_device_token($requestInfo->user_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Accepetd your friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 2,
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 2,
					"id"    => $requestInfo->to_user_id
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */	
				
				
				return response(['STATUS'=>'true','message'=>'Friend Request Accepted Successfully.']);
			} else{
				return response(['STATUS'=>'true','message'=>'Request Withdraw.']);
			}
	}
	
	public function friendRequestReject(Request $request){
		$user = auth()->user();
		$count = DB::table('gym_friends')->where(['id'=>$request->id])->count();
			if($count > 0){
				//request_status = 4 request rejected by friend
				//$updateRequestStatus = DB::table('gym_friends')->where('id',$request->id)->update(['request_status'=>4]);
				$requestInfo = $this->getFriendRequestDetails($request->id);
				$deleteRecord = DB::table('gym_friends')->where('id',$request->id)->delete();
				
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($requestInfo->to_user_id);
				$deviceInfo = $this->get_device_token($requestInfo->user_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Rejected your friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 3,
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 3,
					"id"    => $requestInfo->to_user_id
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */	
				
				return response(['STATUS'=>'true','message'=>'Friend Request Rejected Successfully.']);
			} else{
				return response(['STATUS'=>'true','message'=>'Request Withdraw.']);
			}
	}
	
	public function cancelFriendRequest(Request $request){
		$user = auth()->user();
		$count = DB::table('gym_friends')->where(['id'=>$request->id])->count();
			if($count!=0){
				//delete entry
				$deleteRecord = DB::table('gym_friends')->where('id',$request->id)->delete();
				return response(['STATUS'=>'true','message'=>'Friend Request Cancelled Successfully.']);
			} else{
				return response(['STATUS'=>'true','message'=>'Request Cancelled Already.']);
			}
	}
	
	public function getUserInfoByEmail($email){
	    $userDeatils = DB::table('users')->where(['email'=>$email])
		->select('user_id','user_type','name','email','email_verified_at','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','device_token','active_status')
		->first();
		$userDeatils->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $userDeatils->profile_picture;
		return $userDeatils;
    }
	public function getUserInfoByUserId($userId){
	    $userDeatils = DB::table('users')->where(['user_id'=>$userId])
		->select('user_id','user_type','name','email','email_verified_at','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','device_token','active_status')
		->first();
		$userDeatils->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $userDeatils->profile_picture;
		return $userDeatils;
    }
	
	
	
	public function getChallangeFriendsLists(Request $request){
		$user = auth()->user();
		$friendsList = array();
/* DB::enableQueryLog();  */
		$friends = DB::table('gym_friends')
				->select('user_id','to_user_id','firends_email')
				->where(['user_id'=>$request->user_id,'request_status'=>1])
				->orWhere(['to_user_id'=>$request->user_id])
				->where(['request_status'=>1])
				->get();
		
/* $query = DB::getQueryLog();
print_r($query);
die; */  		
		if(!empty($friends)){
			$i=0;
			foreach($friends as $key=>$val){
				if($val->user_id==$request->user_id){
					//Here $val->to_user_id is friend user id
					$friendsList[] = $this->getUserInfoByUserId($val->to_user_id);
					$request_status = $this->firiendStatus($request->user_id,$val->to_user_id);
 
					$friendsList[$key]->request_status = $request_status->request_status;
					$friendsList[$key]->request_id = $request_status->request_id;
				} else if($val->to_user_id==$request->user_id){
					//Here $val->user_id is friend user id
					$friendsList[] = $this->getUserInfoByUserId($val->user_id);
					$request_status = $this->firiendStatus($request->user_id,$val->user_id);
					
					$friendsList[$key]->request_status = $request_status->request_status;
					$friendsList[$key]->request_id = $request_status->request_id; 
				}
				$i++;
			}
			
		} else{
			return response(['STATUS'=>'true','message'=>'Friend List Empty.']); 
		}
	/* print_r((object)$friendsList);
	die; */
		if(!empty($friendsList)){
			return response(['STATUS'=>'true','message'=>'Friend List.','response'=>$friendsList]);
		} else {
			return response(['STATUS'=>'true','message'=>'Friend List Empty.']);
		}
	}
	
	
	/* Friend List */
	public function getFriendsLists(Request $request){
		$user = auth()->user();
		$friendsList = array();
/* DB::enableQueryLog();	 */
		$friends = DB::table('gym_friends')
				->select('user_id','to_user_id','firends_email')
				->where(['user_id'=>$request->user_id])
				->orWhere(['to_user_id'=>$request->user_id])->get();
		
/* 
$query = DB::getQueryLog();
print_r($query);
die; */ 			
		if(!empty($friends)){
			foreach($friends as $key=>$val){
				if($val->user_id==$request->user_id){
					//Here $val->to_user_id is friend user id
					$friendsList[] = $this->getUserInfoByUserId($val->to_user_id);
					$request_status = $this->firiendStatus($request->user_id,$val->to_user_id);
 
					$friendsList[$key]->request_status  = $request_status->request_status;
					$friendsList[$key]->request_id = $request_status->request_id;
				} else if($val->to_user_id==$request->user_id){
					//Here $val->user_id is friend user id
					$friendsList[] = $this->getUserInfoByUserId($val->user_id);
					$request_status = $this->firiendStatus($request->user_id,$val->user_id);
					$friendsList[$key]->request_status  = $request_status->request_status;
					$friendsList[$key]->request_id = $request_status->request_id;
				}
				
			}
		} else{
			return response(['STATUS'=>'true','message'=>'Friend List Empty.']); 
		}
	/* print_r((object)$friendsList);
	die; */
		if(!empty($friendsList)){
			return response(['STATUS'=>'true','message'=>'Friend List.','response'=>$friendsList]);
		} else {
			return response(['STATUS'=>'true','message'=>'Friend List Empty.']);
		}
	}
	/* Used In Search Friend */
	public function getFriendInfoByEmail(Request $request){
		$user = auth()->user();
		if($request->email!=''){
			$userDeatils = DB::table('users')->where(['email'=>$request->email])->where('user_id', '!=', $user->user_id)->orWhere(['user_name'=>$request->email])->select('user_id','user_type','user_name','name','email','email_verified_at','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','device_token','active_status')->first();
		} else {
			$userDeatils = array();
		}
		if(!empty($userDeatils)){
			$diff = (date('Y') - date('Y',strtotime($userDeatils->dob)));
			$userDeatils->age = $diff;
			$userDeatils->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $userDeatils->profile_picture;
			return response(['STATUS'=>'true','message'=>'Friend Information.','response'=>$userDeatils]);
		} else {
			return response(['STATUS'=>'true','message'=>'Friend Information Empty.']);
		}
    }
	function firiendStatus($userId,$friendId){
		$result = array();
		$result = (object)$result;
		$count = DB::table('gym_friends')->where(['user_id'=>$userId,'to_user_id'=>$friendId])->count();
		if($count!=0){
			$result  = DB::table('gym_friends')->where(['user_id'=>$userId,'to_user_id'=>$friendId])->select('id as request_id','request_status')->first();
			//Request Sent
			if($result->request_status==2){
				$result->request_status = 2;
			}
		} else {
			$count2 = DB::table('gym_friends')->where(['user_id'=>$friendId,'to_user_id'=>$userId])->count();
			if($count2!=0){
				$result = DB::table('gym_friends')->where(['user_id'=>$friendId,'to_user_id'=>$userId])->select('id as request_id','request_status')->first();
				//Request Received
				if($result->request_status==2){
					$result->request_status = 3;
				}
			} else {
				$result->request_status = 0;
				$result->request_id = 0;
			} 
		}
		return $result;
	}
	/* Gym Join */
	public function getFriendInfoByUserId(Request $request){
		$user = auth()->user();
		$myId = $user->user_id;
		//echo $myId;
		if($request->user_id!=''){
			$userDeatils = DB::table('users')->where(['user_id'=>$request->user_id])
			->select('user_id','user_type','user_name','name','email','email_verified_at','social_id','provider','profile_picture','country_code','phone_number','gender','dob','height','weight','about','address','preffered_date_time','achievements','lat','lon','device_type','device_token','active_status')
			->first();
			$diff = (date('Y') - date('Y',strtotime($userDeatils->dob)));
			$userDeatils->age = $diff;

			$request_status = $this->firiendStatus($myId,$request->user_id);
			$userDeatils->request_status = $request_status->request_status;
			$userDeatils->request_id = $request_status->request_id;
			
			$userDeatils->subscribed_gym = $this->subscribesGymByUserId($request->user_id);
		/*  */
			$userParkInfo = $this->userVisitedParkList($request->user_id);
			$userDeatils->park_info = $userParkInfo;
			$walList      = $this->getUserWallList($request->user_id);
			$chalangeList = $this->getUserChallangeList($request->user_id);
			$parkExpList  = $this->getUserParkExperienceList($request->user_id);
			$userInfoGalary = array_merge_recursive((array)$walList,(array)$chalangeList,(array)$parkExpList);
			foreach($userInfoGalary as $ke=>$newval){
				$narr[] = $userInfoGalary[$ke];
			}
			$userDeatils->gallery = $narr[0];
		/*  */
		} else {
			$userDeatils = array();
		}
		if(!empty($userDeatils)){
			$userDeatils->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $userDeatils->profile_picture;
			return response(['STATUS'=>'true','message'=>'Friend Information.','response'=>$userDeatils]);
		} else {
			return response(['STATUS'=>'true','message'=>'Friend Information Empty.']);
		}
    }
	
	function subscribesGymByUserId($user_id){
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at')
				->where(['user_id'=>$user_id,'subscription_for'=>'GYM'])
				->get();
			$myorder = array();
			$myorderGym = array();
			$trainerSubscription = array();
			$gymSubscription = array();
//print_r($orderInfo);
//die;
			$count = 0;
			foreach($orderInfo as $key=>$order){
					$gymInfo = $this->getGymInfo($order->gym_id);
					$subscriptionDetails = (object) array_merge((array) $order,(array) $gymInfo);
					$months = '+'.$subscriptionDetails->months.' months';
					$ENDATE = strtotime($months,strtotime($subscriptionDetails->start_date));
					$endDate = date("Y-m-d",$ENDATE);
					$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
				
					//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorderGym['order_id']        = $subscriptionDetails->order_id;
					$myorderGym['transaction_id']  = $subscriptionDetails->transaction_id;
					$myorderGym['order_status']    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
		$today = date("l");		
		$currentDay = strtoupper($today);
		$open_close = DB::table('gym_open_close_time')->where(['user_id'=>$order->gym_id,'day'=>$currentDay])->first();
		$openclose = json_decode($open_close->open_close_time);
		$openclose = implode(', ',$openclose);
							
					$myorderGym['shipment_detail'] = (object) array(
						"id"                  =>$subscriptionDetails->gym_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"product_category"    =>$subscriptionDetails->gym_category,
						"product_title"       =>$subscriptionDetails->gym_title, 
						"sub_title"           =>null,
						"brief_description"   =>strip_tags($subscriptionDetails->brief_description),
						"full_description"    =>strip_tags($subscriptionDetails->full_description),
						"cover_image"         =>$subscriptionDetails->cover_image, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						//"subscription_amount"=>$subscriptionDetails->subscription_amount,
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft,
						"per"                 =>null,
						"distance"            =>4,
						"lat"                 =>$subscriptionDetails->lat,
						"lon"                 =>$subscriptionDetails->lon,
						"open_close"          =>$openclose
					);
			//print_r($myorder);
			/* die; */
					$gymSubscription[$count++] = $myorderGym;
				
			}	
//die;			
			//print_r($trainerSubscription);die;
			//print_r($gymSubscription);
			$userSubscriptions = array_merge($trainerSubscription,$gymSubscription);
		return $userSubscriptions;
	}
   
	public function receivedFriendRequest(Request $request){
		$today = date('Y-m-d h:i:s');
		$nowDateTime = strtotime($today);
		$friends = DB::table('gym_friends')->where(['firends_email'=>$request->email])->select('user_id','request_status','created_at')->get();
		//print_r($friends);	 
		foreach($friends as $key=>$friend){
			$friends[$key] = DB::table('users')->where(['user_id'=>$friend->user_id])->select('user_id','name','email')->first();
			$createdAt = strtotime($friend->created_at);
			$friends[$key]->request_time = $nowDateTime - $createdAt;
			$friends[$key]->request_status = $friend->request_status;
		}
		return response(['STATUS'=>'true','message'=>'Request Lists.','response'=>$friends]);
	}

   	public function parkList(Request $request){
		if($request->radius!=''){
			if($request->park_type==1){
				$parkType1 = 1;	$parkType2 = 3;
				$parks = $this->search_park_near_by_users($request->lat,$request->lon,$request->radius,$parkType1,$parkType2);
			}
			if($request->park_type==2){
				$parkType1 = 2;	$parkType2 = 3;
				$parks = $this->search_park_near_by_users($request->lat,$request->lon,$request->radius,$parkType1,$parkType2);
			}
			if($request->park_type==3){
				$parkType1 = 1;	$parkType2 = 2; $both = 3;
				$parks = $this->search_park_near_by_users($request->lat,$request->lon,$request->radius,$parkType1,$parkType2,$both);
			}
			
		} else {
			if($request->park_type==1){
				//park_type =1 Park Link
				//park_type =3 Both
				$parks = DB::table('parks')->where(['status'=>1])
						->select('id','park_name','park_type','lat','lon','park_image','park_address')
						->where(['park_type'=>1])
						->orWhere(['park_type'=>3])
						->get();
			}
			if($request->park_type==2){
				//park_type =2 Free Run
				//park_type =3 Both
				$parks = DB::table('parks')->where(['status'=>1])
						->select('id','park_name','park_type','lat','lon','park_image','park_address')
						->where(['park_type'=>2])
						->orWhere(['park_type'=>3])
						->get();
			}
		}
        if(!empty($parks)){
			foreach($parks as $ky=>$park){
				$parks[$ky]->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
				$parks[$ky]->park_id = $park->id;
			}
			return response(['STATUS'=>'true','message'=>'Park Lists.','response'=>$parks]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Park List.']);
		}
    }
	public function addParkToTrainerProfile(Request $request){
		$user = auth()->user();
		$parkIds = explode(',',$request->park_id);
		if($request->park_id!=''){
			foreach($parkIds as $key=>$parkId){
				$count = DB::table('trainer_park')->where(['user_id'=>$request->user_id,'park_id'=>$parkId])->count();
				if($count==0){
					$parkData = array(
						'user_id'=>$request->user_id,
						'park_id'=>$parkId,
						'created_at'=>date('Y-m-d h:i:s')
					);
					$id[]= DB::table('trainer_park')->insertGetId($parkData);
				}
			}
			$parkList = $this->trainerAttchedPark($request->user_id); 
			if(!empty($id)){
				return response(['STATUS'=>'true','message'=>'Park Added to Your Profile Successfully.','response'=>$parkList]);
			} else {
				return response(['STATUS'=>'true','message'=>'Park Already Added.','response'=>$parkList]);
			}
		} else {
			return response(['STATUS'=>'fails','message'=>'Please Select at Least One park.']);
		}
    }
	/* Remove Trainer Attached Park */
	public function removeTrainerPark(Request $request){
		$reslut = DB::table('trainer_park')->where(['user_id'=>$request->user_id,'park_id'=>$request->park_id])->delete();
		if($reslut){
			$ParkList = $this->trainerAttchedPark($request->user_id);
			return response(['STATUS'=>'true','message'=>'Park Deleted Successfully.','response'=>$ParkList]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	
	function trainerAttchedPark($userId){
		$parks = DB::table('trainer_park')
                ->join('parks', 'trainer_park.park_id', '=', 'parks.id')
                ->select('trainer_park.id','parks.id as park_id','parks.park_name','parks.park_type','parks.lat','parks.lon','parks.park_image','parks.park_address','trainer_park.id')
				->where(['trainer_park.user_id'=>$userId])
                ->get(); 
			foreach($parks as $key=>$park){
				$parks[$key]->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
			}
		return $parks;
	}
	/* Add Shipping Address */
	function addShippingAddress(Request $request){
		$validatedData =$this->validate($request,[
			'user_id'        =>'required',
			'recipient_name' =>'required',
			'city'           =>'required',
			'state'          =>'required',
			'postal_code'    =>'required',
			'country_code'   =>'required',
			'phone_number'   =>'required',
			'line1'          =>'required',
			'line2'          =>'',
			'land_mark'      => ''
		]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');	
		$shippingAddress = DB::table('shipping_address')->insertGetId($validatedData);
		$shippingAddress = $this->shippingAddress($request->user_id);
		if($shippingAddress){
			return response(['STATUS'=>'true','message'=>'Shipping Address Added Successfully.','response'=>$shippingAddress]);
		} else{
			return response(['STATUS'=>'false','message'=>'Please Fill the Required Fields.']);
		}
	}
	/* Get Shipping Address */
	
	public function getShippingAddress(Request $request){
		//$shippingAddress = $this->shippingAddress($request->user_id);
		$shippingAddress = DB::table('shipping_address')
				->select('id','user_id','recipient_name','city','state','postal_code','country_code','phone_number','line1','line2','land_mark')
				->where(['user_id'=>$request->user_id,'status'=>1])
				->get();
        if(!empty($shippingAddress)){
			return response(['STATUS'=>'true','message'=>'Shipping Address Lists.','response'=>$shippingAddress]);
        } else {
			return response(['STATUS'=>'false','message'=>'Empty Address Lists.']);
		}
	}	 
	/* Remove Shipping Address */
	public function removeShippingAddress(Request $request){
/* DB::enableQueryLog(); */
		//$reslut = DB::table('shipping_address')->where(['user_id'=>$request->user_id,'id'=>$request->id])->delete();
		$reslut = DB::table('shipping_address')->where(['user_id'=>$request->user_id,'id'=>$request->id])
		->update(['status'=>0]);
/*		$query = DB::getQueryLog();
 print_r($query);
die;  */
		if($reslut){
			$shippingAddress = $this->shippingAddress($request->user_id);
			return response(['STATUS'=>'true','message'=>'Shipping Address Deleted Successfully.','response'=>$shippingAddress]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	
	public function updateShippingAddress(Request $request){
		$validatedData =$this->validate($request,[
			'id'            =>'required|int',
			'user_id'       =>'required',
			'recipient_name'=>'required',
			'city'          =>'required',
			'state'         =>'required',
			'postal_code'   =>'required',
			'country_code'  =>'required',
			'phone_number'  =>'required',
			'line1'         =>'required',
			'line2'         =>'',
			'land_mark'     =>''
		]);
		
		$updateShippingAddress = DB::table('shipping_address')
            ->where(['id'=>$request->id,'user_id'=>$request->user_id])
            ->update([
				'recipient_name' => $request->recipient_name,
				'city'           => $request->city,
				'state'          => $request->state,
				'postal_code'    => $request->postal_code,
				'country_code'   => $request->country_code,
				'phone_number'   => $request->phone_number,
				'line1'          => $request->line1,
				'line2'          => $request->line2,
				'land_mark'      => $request->land_mark,
				'updated_at'     => date('Y-m-d h:i:s')
			]);
		
		if($updateShippingAddress){
			$shippingAddress = $this->getShippingAddressById($request->id,$request->user_id);
			return response(['STATUS'=>'true','message'=>'Shipping Address Updated Successfully.','response'=>$shippingAddress]);
		} else{
			return response(['STATUS'=>'false','message'=>'Please Fill the Required Fields.']);
		}
	}
	
	function shippingAddress($userId){
		$shippingAddress = DB::table('shipping_address')
				->select('id','user_id','recipient_name','city','state','postal_code','country_code','phone_number','line1','line2','land_mark')
				->where(['user_id'=>$userId])
				->get();
		return $shippingAddress;
	}	
	function getShippingAddressById($id,$userId){
		$shippingAddress = DB::table('shipping_address')
				->select('id','user_id','recipient_name','city','state','postal_code','country_code','phone_number','line1','line2','land_mark')
				->where(['id'=>$id,'user_id'=>$userId])
				->get();
		return $shippingAddress;
	}
	/* End Shipping Address */
	
	/* For Paypal */
	public function paymentForm(){
		return view('paypal-pay');
	}
	function getOrderInfoFromTempTable($orderId,$userId){
		$productInfo  = DB::table('temp_payments')->select('*')->where(['user_id'=>$userId,'order_id'=>$orderId])->first();
		return $productInfo;
	}
	function getOrderInfoFromTempSubscriptionTable($orderId,$userId){
		$productInfo  = DB::table('temp_subscription_payments')->select('*')->where(['user_id'=>$userId,'order_id'=>$orderId])->first();
		return $productInfo;
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
	function getShippingInfo($shippingId,$userId){
		$shippingAddressInfo = DB::table('shipping_address')->select('*')->where(['id'=>$shippingId,'user_id'=>$userId])->first();
		return $shippingAddressInfo;
	}
	function getCouponInfo($couponId){
		$couponInfo  = DB::table('coupon_code')->select('*')->where(['id'=>$couponId])->first();
		if(!empty($couponInfo)){
			return $couponInfo;
		} else{
			$blank = (object) array(
				"amount"=>'',
				"percentage"=>''
			);
			return $blank;
		}
	}	
	public function creatPayment(Request $request)
    {
		$order_id     	= Str::random(12);
		$user_id      	= $request->user_id;
		$product_type 	= $request->product_type;
		$product_id   	= $request->product_id;
		
		$quantity     	= $request->quantity;
		$shipping_id  	= $request->shipping_address_id;
		
		$shippingAmount = 0;
		$taxAmount      = 0;
		
		$productInfo    = $this->getProductInfo($product_type,$product_id);

		$productAmount  = $productInfo->amount;
		$subtotalAmount = $productAmount*$quantity;
		
	/* Start Discount Coupon Addition */
	$couponInfo   	= $this->getCouponInfo($request->discount_id);
	$couponData = array();

		if($couponInfo->amount!='' AND $couponInfo->amount!=null){
			$subtotalAmountAfterDiscount = $subtotalAmount-$couponInfo->amount;
			$couponData 	= array(
				"coupon_id"=>$couponInfo->id,
				"coupon_code"=>$couponInfo->coupon_code,
				"product_amount"=>$subtotalAmount,
				"discounted_amount"=>$couponInfo->amount,
			);
		} 
		if($couponInfo->percentage!='' AND $couponInfo->percentage!=null){
			$subtotalAmountAfterDiscount = $subtotalAmount - ($subtotalAmount * ($couponInfo->percentage / 100));
			$discounted_amount           = $subtotalAmount-$subtotalAmountAfterDiscount;
			$couponData 	= array(
				"coupon_id"=>$couponInfo->id,
				"coupon_code"=>$couponInfo->coupon_code,
				"product_amount"=>$subtotalAmount,
				"discounted_amount"=>$discounted_amount,
			);
		} 
		if(empty($couponData)){
			$subtotalAmountAfterDiscount = $subtotalAmount;
			$couponData 	= array();
		} 	

		$subtotalAmount = $subtotalAmountAfterDiscount;

		$totalAmount    = $shippingAmount + $taxAmount + $subtotalAmount;
		if(!empty($couponData)){
			$productAmount = $totalAmount/$quantity;
		}
	/* End Discount Coupon Addition */	

		if($product_type == 'PRODUCT'){
			$productTitle   = $productInfo->product_title;
			
			$tempTableData 	= array(
				"user_id"			  =>$user_id,
				"order_id"			  =>$order_id,
				"product_type"		  =>$product_type,
				"product_id"		  =>$product_id,
				"amount"		      =>$totalAmount,
				"quantity"			  =>$quantity,
				"shipping_address_id" =>$shipping_id,
				"created_at"		  =>date('Y-m-d h:i:s')
			);
			if(!empty($couponData)){
				$tempTableData = array_merge($tempTableData,$couponData);
			}
		}	
		if($product_type == 'ACCESSORIES'){
			$productTitle   = $productInfo->accessorie_title;
			$tempTableData 	= array(
				"user_id"			  =>$user_id,
				"order_id"			  =>$order_id,
				"product_type"		  =>$product_type,
				"accessorie_id"		  =>$product_id,
				"amount"			  =>$totalAmount,
				"quantity"			  =>$quantity,
				"shipping_address_id" =>$shipping_id,
				"created_at"          =>date('Y-m-d h:i:s')
			);
			if(!empty($couponData)){
				$tempTableData = array_merge($tempTableData,$couponData);
			}
		}
		
		$tempId = DB::table('temp_payments')->insertGetId($tempTableData);
		
		/* If data inserted into temporary table then proceed for paypal payment else return error */
		if($tempId){
			$shippingInfo = $this->getShippingInfo($shipping_id,$user_id);		
			$clientId       = config('constants.PAYPAL_CLIENT_ID');
			$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
			$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					$clientId,$clientSecretId
				)
			);
		
		$apiContext->setConfig( 
			array( 
				'mode' => 'live', 
				'log.LogEnabled' => true, 
				'log.FileName' => 'PayPal.log', 
				'log.LogLevel' => 'DEBUG', 
				// PLEASE USE INFO LEVEL FOR LOGGING IN LIVE ENVIRONMENTS 
				'cache.enabled' => true, 
			) 
		);
		
		
			$payer = new Payer();
			$payer->setPaymentMethod("paypal");
			/* Shipping address */
		/*  $shippingAddress = new ShippingAddress();
			$shippingAddress->setState($shippingInfo->state);
			$shippingAddress->setCity($shippingInfo->city);
			$shippingAddress->setPostalCode($shippingInfo->postal_code);
			$shippingAddress->setCountryCode($shippingInfo->country_code);
			$shippingAddress->setPhone($shippingInfo->phone_number);
			$shippingAddress->setDefaultAddress(true);
			$shippingAddress->setPreferredAddress(true);
			$shippingAddress->setLine1($shippingInfo->line1);
			
			$payerInfo = new PayerInfo();
			$payerInfo->setShippingAddress($shippingAddress);
			$payer->setPayerInfo($payerInfo); 
		*/
			/* End Shipping address */
			$item1 = new Item();
			$item1->setName($productTitle)
					->setCurrency('USD')
					->setQuantity($quantity)
					->setSku($order_id) // Similar to `item_number` in Classic API
					->setPrice($productAmount); 
			/* $item2 = new Item();
			$item2->setName('Granola bars')
					->setCurrency('USD')
					->setQuantity(5)
					->setSku("321321") // Similar to `item_number` in Classic API
					->setPrice(2); */

			$itemList = new ItemList();
			/* $itemList->setItems(array($item1, $item2)); */
			$itemList->setItems(array($item1));
			
			/*  */
			$details = new Details();
			$details->setShipping($shippingAmount)
				->setTax($taxAmount)
				->setSubtotal($subtotalAmount);
			
			$amount = new Amount();
			$amount->setCurrency("USD")
					->setTotal($totalAmount)
					->setDetails($details);
				
			$transaction = new Transaction();
			$transaction->setAmount($amount)
						->setItemList($itemList)
						->setDescription("Payment description")
						->setInvoiceNumber(uniqid());
		
		
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("http://perfectlinkfitness.com/api/execute-payment?subtotalAmount=".$subtotalAmount."&orderId=".$order_id."&shippingId=".$shipping_id."&userId=".$user_id)
				->setCancelUrl("http://perfectlinkfitness.com/cancel-payment");
		
			$payment = new Payment();
			$payment->setIntent("sale")
					->setPayer($payer)
					->setRedirectUrls($redirectUrls)
					->setTransactions(array($transaction));
			
			$payment->create($apiContext);
			return redirect($payment->getApprovalLink());
		} else {
			$txn_id = 'TRASACTIONDECLINED';
			return view('paypal/success', ['txn_id'=>$txn_id]);
		}
    }
	public function cancelPayment(){
		$txn_id = 'TRASACTIONDECLINED';
		return view('paypal/success', ['txn_id'=>$txn_id]);
	}
    public function executePayment(){
		$clientId       = config('constants.PAYPAL_CLIENT_ID');
		$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$clientId,$clientSecretId
			)
		);
		
		$apiContext->setConfig( 
			array( 
				'mode' => 'live', 
				'log.LogEnabled' => true, 
				'log.FileName' => 'PayPal.log', 
				'log.LogLevel' => 'DEBUG', 
				// PLEASE USE INFO LEVEL FOR LOGGING IN LIVE ENVIRONMENTS 
				'cache.enabled' => true, 
			) 
		);
		
		
		$userId     = request('userId');
		$orderId    = request('orderId');
		$shippingId = request('shippingId');
		$PayerID    = request('PayerID');
		$token      = request('token');
		
		$subtotalAmount = request('subtotalAmount');
		$shippingAmount = 0;
		$taxAmount      = 0;
		$totalAmount    = $shippingAmount + $taxAmount + $subtotalAmount;
		if (empty($PayerID) || empty($token)) {
            $txn_id = 'TRASACTIONERROR';
			return view('paypal/success', ['txn_id'=>$txn_id]);
        } else {
			$paymentId = request('paymentId');
			$payment = Payment::get($paymentId, $apiContext);
			
			$execution = new PaymentExecution();
			$execution->setPayerId(request('PayerID'));
			
			$transaction = new Transaction();
			$amount = new Amount();
			$details = new Details();

			$details->setShipping($shippingAmount)
					->setTax($taxAmount)
					->setSubtotal($subtotalAmount);

			$amount->setCurrency('USD');
			$amount->setTotal($totalAmount);
			$amount->setDetails($details);
			$transaction->setAmount($amount);
			
			$execution->addTransaction($transaction);
			$result = $payment->execute($execution, $apiContext);

			if($result->getState() == 'approved') {
				$shippingInfo  = $this->getShippingInfo($shippingId,$userId);
				$tempTableData = $this->getOrderInfoFromTempTable($orderId,$userId);
				$trans         = $result->getTransactions();
				// item info
				$Subtotal         = $trans[0]->getAmount()->getDetails()->getSubtotal();
				$Tax              = $trans[0]->getAmount()->getDetails()->getTax();
				$payer            = $result->getPayer();
				// payer info
				$PaymentMethod    = $payer->getPaymentMethod();
				$PayerStatus      = $payer->getStatus();
				$PayerMail        = $payer->getPayerInfo()->getEmail();
				$relatedResources = $trans[0]->getRelatedResources();
				$sale             = $relatedResources[0]->getSale();
				// sale info
				$saleId           = $sale->getId();
			
				$CreateTime       = $sale->getCreateTime();
				$UpdateTime       = $sale->getUpdateTime();
				$State            = $sale->getState();
				$Total            = $sale->getAmount()->getTotal();
			
			if($tempTableData->product_type=='PRODUCT'){
				$paymentTable = array(
					"user_id"               => $userId,
					"order_id"              => $orderId,
					"transaction_id"        => $saleId,
					"product_type"          => $tempTableData->product_type,
					"product_id"            => $tempTableData->product_id,
					"quantity"              => $tempTableData->quantity,
					"tax"                   => $Tax,
					"shipping"              => $shippingAmount,
					"sub_total"             => $Subtotal,
					"total"                 => $Total,
					"payer_status"          => $PayerStatus,
					"payment_state"         => $State,
					"payment_method"        => $PaymentMethod,
					"payer_email"           => $PayerMail,
					
					"coupon_id"=>$tempTableData->coupon_id,
					"coupon_code"=>$tempTableData->coupon_code,
					"product_amount"=>$tempTableData->product_amount,
					"discounted_amount"=>$tempTableData->discounted_amount,
					
					"shipping_address_id"   => $shippingId,
					"purchase_status"       => "1",
					"payment_created_at"    => $CreateTime,
					"payment_update_time"   => $UpdateTime,
					"created_at"            => date('Y-m-d h:i:s')
				);
			}			
			if($tempTableData->product_type=='ACCESSORIES'){
				$paymentTable = array(
					"user_id"               => $userId,
					"order_id"              => $orderId,
					"transaction_id"        => $saleId,
					"product_type"          => $tempTableData->product_type,
					"accessorie_id"         => $tempTableData->accessorie_id,
					"quantity"              => $tempTableData->quantity,
					"tax"                   => $Tax,
					"shipping"              => $shippingAmount,
					"sub_total"             => $Subtotal,
					"total"                 => $Total,
					"payer_status"          => $PayerStatus,
					"payment_state"         => $State,
					"payment_method"        => $PaymentMethod,
					"payer_email"           => $PayerMail,
										
					"coupon_id"=>$tempTableData->coupon_id,
					"coupon_code"=>$tempTableData->coupon_code,
					"product_amount"=>$tempTableData->product_amount,
					"discounted_amount"=>$tempTableData->discounted_amount,
					
					"shipping_address_id"   => $shippingId,
					"purchase_status"       => "1",
					"payment_created_at"    => $CreateTime,
					"payment_update_time"   => $UpdateTime,
					"created_at"            => date('Y-m-d h:i:s')
				);
			}
				$paymentFinal = DB::table('payments')->insertGetId($paymentTable);
				
/* Push Notification */
if($tempTableData->product_type=='ACCESSORIES' AND $paymentFinal>0){
		$accessorieInfo = $this->getProductInfo($tempTableData->product_type,$tempTableData->accessorie_id);
		$deviceInfo = $this->get_device_token($userId);
		$tokenId = array($deviceInfo->device_token);
		$userInfo = $this->getUserInfo($userId);
		$pushNotification = array(
			"title" => $accessorieInfo->accessorie_title,
			"body"  => "Purchased successfully.",
			"icon"  => $accessorieInfo->cover_image,  
			"flag"  => 7,
		);
		$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 7,
					"id"    => $accessorieInfo->accessorie_id,
				);
		$this->push_notification($tokenId,$pushNotification,$notificationData);
}
if($tempTableData->product_type=='PRODUCT' AND $paymentFinal>0){
	$productInfo = $this->getProductInfo($tempTableData->product_type,$tempTableData->product_id);
		$deviceInfo = $this->get_device_token($userId);
		$tokenId = array($deviceInfo->device_token);
		$userInfo = $this->getUserInfo($userId); 
		$pushNotification = array(
			"title" => $productInfo->product_title,
			"body"  => "Purchased successfully.",
			"icon"  => $productInfo->cover_image,  
			"flag"  => 8,
		);
		$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 8,
					"id"    => $productInfo->product_id,
				);
		$this->push_notification($tokenId,$pushNotification,$notificationData);
		
}
/* End Push Notification */	
				
			/* 	echo '<pre>';
				print_r($paymentTable); 
				print_r($shippingInfo); */
				return view('paypal/success', ['txn_id'=>$saleId]);
			}
		
		}
	}
    public function cancel()
    {
        dd('Your payment is canceled. You can create cancel page here.');
		return view('paypal/cancel', ['txn_id'=>'CANCELED']);
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
		 dd('Your payment is success.');
    }
	/* End For Paypal */
	public function myOrder(Request $request){
		$orders = DB::table('payments')->select('transaction_id','product_type')->where(['user_id'=>$request->user_id])->get();
		$orderDetails = array();
		if(!empty($orders)){
			foreach($orders as $key=>$order){
				$orderDetails[$key] = $this->orderInfoByTransactionIdAndOrderType($order->product_type,$order->transaction_id);
			}
			foreach($orderDetails as $k=>$orderInfo){
				unset($orderDetails[$k]->tracking_detail);
				unset($orderDetails[$k]->shipping_detail);
				unset($orderDetails[$k]->payment_detail);
			}
		} else{
			$orderDetails = array();
		}
		$subscription = $this->subscriptionInfoById($request->user_id);
		$orderAndSubscriptionDetails = array_merge($orderDetails,$subscription);
		
		/* usort($orderAndSubscriptionDetails,function($first,$second){
			return strtotime($first->purchase_date) < strtotime($second->purchase_date);
		}); */
		array_multisort(array_map('strtotime',array_column($orderAndSubscriptionDetails,'purchase_date')),
            SORT_DESC, $orderAndSubscriptionDetails);
		
		if(!empty($orderAndSubscriptionDetails)){
			return response(['STATUS'=>'true','message'=>'My Orders.','response'=>$orderAndSubscriptionDetails]);
		} else{
			return response(['STATUS'=>'true','message'=>'Empty order Lists','response'=>$orderAndSubscriptionDetails]);
		} 
	}
	
	function subscriptionInfoById($userId){
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at','created_at')
				->where(['user_id'=>$userId])
				->get();
			$myorder = array();
			$myorderGym = array();
			$trainerSubscription = array();
			$gymSubscription = array();
			
//print_r($orderInfo);
//die;
$count = 0;
			foreach($orderInfo as $key=>$order){
				if($order->subscription_for=='TRAINER'){
					$trainerInfo = $this->getTrainerInfo($order->trainer_id);
					$trainerInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$trainerInfo->profile_picture; 
					$subscriptionDetails = (object) array_merge((array) $order,(array) $trainerInfo);
					
					unset($subscriptionDetails->achievements);
					unset($subscriptionDetails->galary_image);
					unset($subscriptionDetails->videos);
					unset($subscriptionDetails->distance);
/* print_r($subscriptionDetails);
die; */
					$days = '+'.$subscriptionDetails->months.' days';
					$ENDATE = strtotime($days,strtotime($subscriptionDetails->start_date));
					$endDate = date("Y-m-d",$ENDATE);
					$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
				
					//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorder['order_id']        = $subscriptionDetails->order_id;
					$myorder['transaction_id']  = $subscriptionDetails->transaction_id;
					$myorder['purchase_date']  = $subscriptionDetails->created_at;
					$myorder['order_status']    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
					
					$myorder['shipment_detail'] = (object) array(
						"id"                  =>$subscriptionDetails->trainer_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"product_category"    =>$subscriptionDetails->trainer_category,
						"product_title"       =>$subscriptionDetails->name, 
						"sub_title"           =>null,
						"brief_description"   =>$subscriptionDetails->about,
						"full_description"    =>$subscriptionDetails->about,
						"cover_image"         =>$subscriptionDetails->profile_picture, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						//"subscription_amount"=>$subscriptionDetails->subscription_amount, 
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft,
						"per"                 =>null 
					);
					$trainerSubscription[$count++] = $myorder;
					 //print_r($myorder);
			
				} else if($order->subscription_for=='GYM'){
					$gymInfo = $this->getGymInfo($order->gym_id);
					$subscriptionDetails = (object) array_merge((array) $order,(array) $gymInfo);
					$months = '+'.$subscriptionDetails->months.' months';
					$ENDATE = strtotime($months,strtotime($subscriptionDetails->start_date));
					$endDate = date("Y-m-d",$ENDATE);
					$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
				
					//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorderGym['order_id']        = $subscriptionDetails->order_id;
					$myorderGym['transaction_id']  = $subscriptionDetails->transaction_id;
					$myorderGym['purchase_date']   = $subscriptionDetails->created_at;
					$myorderGym['order_status']    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
					
					$myorderGym['shipment_detail'] = (object) array(
						"id"                  =>$subscriptionDetails->gym_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"product_category"    =>$subscriptionDetails->gym_category,
						"product_title"       =>$subscriptionDetails->gym_title, 
						"sub_title"           =>null,
						"brief_description"   =>strip_tags($subscriptionDetails->brief_description),
						"full_description"    =>strip_tags($subscriptionDetails->full_description),
						"cover_image"         =>$subscriptionDetails->cover_image, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						//"subscription_amount"=>$subscriptionDetails->subscription_amount,
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft,
						"per"                 =>null 
					);
			//print_r($myorder);
			/* die; */
					$gymSubscription[$count++] = $myorderGym;
				}
			}	
//die;			
			//print_r($trainerSubscription);die;
			//print_r($gymSubscription);
			$userSubscriptions = array_merge($trainerSubscription,$gymSubscription);
			
			/* print_r($userSubscriptions);
			die; */  
			
		return $userSubscriptions;
	}
	
	public function getOrderInfoByTransactionId(Request $request){
		$productType = DB::table('payments')->select('product_type')->where(['transaction_id'=>$request->transaction_id])->first(); 
		$orderDetails = $this->orderInfoByTransactionIdAndOrderType($productType->product_type,$request->transaction_id);
		if(!empty($orderDetails)){
			return response(['STATUS'=>'true','message'=>'Product Info.','response'=>$orderDetails]);
		} else{
			return response(['STATUS'=>'false','message'=>'Transaction Error']);
		} 
	}
	function orderInfoByTransactionIdAndOrderType($orderType,$transationId){
		$myorder =  (object) array();
		if($orderType=='PRODUCT'){
			
			$orderInfo = DB::table('payments')
				->select('user_id','product_id','order_id','transaction_id','quantity','product_type','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','product_amount','discounted_amount','shipping_address_id','payment_created_at','created_at')
				->where(['transaction_id'=>$transationId])
				->first();
			$productInfo = $this->productdetailsByProductId($orderInfo->product_id);  
		//print_r($productInfo);
			$orderDetails = (object) array_merge((array) $orderInfo,(array) $productInfo);
		//print_r($orderDetails);die;	
			        $myorder->order_id        = $orderDetails->order_id;
					$myorder->transaction_id  = $orderDetails->transaction_id;
					$myorder->purchase_date   = $orderDetails->created_at;
					$myorder->order_status    = $orderDetails->order_status==1?'Active':'Cancelled';
					$myorder->shipment_detail = (object) array(
						"id"                =>$orderDetails->product_id,
						"product_type"      =>$orderDetails->product_type,
						"product_category"  =>$orderDetails->product_category,
						"product_title"     =>$orderDetails->product_title, 
						"sub_title"         =>$orderDetails->sub_title,
						"brief_description" =>$orderDetails->brief_description,
						"full_description"  =>$orderDetails->full_description,
						"cover_image"       =>$orderDetails->cover_image, 
						"amount"            =>$orderDetails->amount, 
						"currency"          =>$orderDetails->currency, 
						"per"               =>$orderDetails->per 
					);
					$myorder->tracking_detail = (object) array(
						'tracking_id'=> null,
						'tracking_url'=> null,
					);
					$shipping_detail = $this->getShippingAddressById($orderDetails->shipping_address_id,$orderDetails->user_id);
					$myorder->shipping_detail = $shipping_detail[0];
					$myorder->payment_detail  = (object) array(
						'total_amount_paid'  => $orderDetails->total_amount_paid,
						'payer_status'       => $orderDetails->payer_status,
						'payment_method'     => $orderDetails->payment_method,
						'payment_created_at' => $orderDetails->payment_created_at,
						'payer_email'        => $orderDetails->payer_email,
						'quantity'           => $orderDetails->quantity,
						'is_coupon_applied'  => $orderDetails->coupon_id!=null?true:false,
						'coupon_id'          => $orderDetails->coupon_id,
						'coupon_code'        => $orderDetails->coupon_code,
						//'product_amount'     => $orderDetails->product_amount,
						'product_amount'     => $orderDetails->amount,
						'discounted_amount'  => $orderDetails->discounted_amount
					);
			return $myorder; 
			
		} else if($orderType=='ACCESSORIES'){
			$orderInfo = DB::table('payments')
				->select('user_id','accessorie_id','order_id','transaction_id','quantity','product_type','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','product_amount','discounted_amount','shipping_address_id','payment_created_at','created_at')
				->where(['transaction_id'=>$transationId])
				->first();
			$accessorieInfo = $this->accessoriesDetailsByAccessoriesId($orderInfo->accessorie_id);  
			$orderDetails = (object) array_merge((array) $orderInfo,(array) $accessorieInfo);
			
			        $myorder->order_id        = $orderDetails->order_id;
					$myorder->transaction_id  = $orderDetails->transaction_id;
					$myorder->purchase_date   = $orderDetails->created_at;
					$myorder->order_status    = $orderDetails->order_status==1?'Active':'Cancelled';
					$myorder->shipment_detail = (object) array(
						"id"                =>$orderDetails->accessorie_id,
						"product_type"      =>$orderDetails->product_type,
						"product_category"  =>null,
						"product_title"     =>$orderDetails->accessorie_title, 
						"sub_title"         =>$orderDetails->sub_title,
						"brief_description" =>$orderDetails->brief_description,
						"full_description"  =>$orderDetails->full_description,
						"cover_image"       =>$orderDetails->cover_image, 
						"amount"            =>$orderDetails->amount, 
						"currency"          =>$orderDetails->currency, 
						"per"               =>$orderDetails->per 
					);
					$myorder->tracking_detail = (object) array(
						'tracking_id'=> null,
						'tracking_url'=> null,
					);
					$shipping_detail = $this->getShippingAddressById($orderDetails->shipping_address_id,$orderDetails->user_id);
					$myorder->shipping_detail = $shipping_detail[0];
					$myorder->payment_detail  = (object) array(
						'total_amount_paid'  => $orderDetails->total_amount_paid,
						'payer_status'       => $orderDetails->payer_status,
						'payment_method'     => $orderDetails->payment_method,
						'payment_created_at' => $orderDetails->payment_created_at,
						'payer_email'        => $orderDetails->payer_email,
						'quantity'           => $orderDetails->quantity,
						'is_coupon_applied'  => $orderDetails->coupon_id!=null?true:false,
						'coupon_id'          => $orderDetails->coupon_id,
						'coupon_code'        => $orderDetails->coupon_code,
						'product_amount'     => $orderDetails->product_amount,
						'discounted_amount'  => $orderDetails->discounted_amount
					);
			return $myorder; 
		}
	}
	
	public function cancelOrder(Request $request){
		$count = DB::table('tracking_details')->where(['order_id'=>$request->order_id])->count();
		if($count==0){
			$cancel = 2;
			$updateOrderStatus = DB::table('payments')->where('order_id',$request->order_id)->update(['order_status'=>$cancel]);
			return response(['STATUS'=>'true','message'=>'Your order has been cancelled sucessfully and refund amount will be initiated within 3-5 business days.']);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'Product is already dispatched.']);
		}
	}
	
	function getTrainerSubscriptioInfo($trainer_id){	
		$trainerInfo  = DB::table('users')->select('currency','single_membership_rate','group_membership_rate')->where(['user_id'=>$trainer_id])->first();
		return $trainerInfo;
	}
	function getGymSubscriptionInfo($gym_id){
		$gymInfo  = DB::table('gym_users')->select('subscription_amount','subscription_month')->where(['user_id'=>$gym_id])->first();
		return $gymInfo;
	}	

	public function subscriptionPayment(Request $request){
		$order_id     	 = Str::random(12);
		$user_id      	 = $request->user_id;
		$subscription_start_date = $request->start_date;
		$preferred_time  = $request->preferred_time;
		$currentDate     = date('Y-m-d h:i:s');	
		$subscription_for     = $request->subscription_for;
		$subscription_months  = $request->months;
		if($subscription_for=='GYM'){
			$subscriptionTitle = 'Subscription For Gym';
			$gym_id     	  = $request->gym_id;
			$gymInfo          = $this->getGymSubscriptionInfo($gym_id);
			
			$membership_type  = 'SINGLE';
			if($membership_type=='GROUP'){
				$member_count = $request->member_count;
				$amountPer    = $gymInfo->subscription_amount;
			} 
			if($membership_type=='SINGLE'){
				$member_count = 1;
				$amountPer    = $gymInfo->subscription_amount;
			}
			
			$totalAmount = $amountPer*$member_count*$subscription_months;
			$tempTableData = array(
				"user_id"=>$user_id,
				"order_id"=>$order_id,
				"gym_id"=>$gym_id,
				"subscription_for"=>$subscription_for,
				"membership_type"=>$membership_type,
				"start_date"=>$subscription_start_date,
				"months"=>$subscription_months,
				"member_count"=>$member_count,
				//"amount"=>$totalAmount,
				"created_at"=>$currentDate,
			);
			//$tempId = DB::table('temp_subscription_payments')->insertGetId($tempTableData);
		}
		if($subscription_for=='TRAINER'){
			$subscriptionTitle = 'Subscription For Trainer';
			$trainer_id    	   = $request->trainer_id;
			$trainerInfo       = $this->getTrainerSubscriptioInfo($trainer_id);
			/* print_r($trainerInfo);
			die; */
			$membership_type  = $request->membership_type;
			if($membership_type=='GROUP'){
				$member_count = $request->member_count;
				$amountPer    = $trainerInfo->group_membership_rate;
			} 
			if($membership_type=='SINGLE'){
				//$member_count = 1;
				$member_count = $request->member_count;
				$amountPer    = $trainerInfo->single_membership_rate;
			}
			
			$totalAmount = $amountPer*$member_count*$subscription_months;
			$tempTableData = array(
				"user_id"=>$user_id,
				"order_id"=>$order_id,
				"trainer_id"=>$trainer_id,
				"subscription_for"=>$subscription_for,
				"membership_type"=>$membership_type,
				"start_date"=>$subscription_start_date,
				"preferred_time"=>$preferred_time,
				"months"=>$subscription_months,
				"member_count"=>$member_count,
				//"amount"=>$totalAmount,
				"created_at"=>$currentDate
			);
			//$tempId = DB::table('temp_subscription_payments')->insertGetId($tempTableData);
		}
		$shippingAmount  = 0;
		$taxAmount       = 0;
		//$productAmount   = $amountPer;
		//$subtotalAmount  = $productAmount*$member_count*$subscription_months;
		$subtotalAmount  = $totalAmount;				
		$totalAmount     = $shippingAmount + $taxAmount + $subtotalAmount;
/* 	
	echo $totalAmount; die;		 
	Start Discount Coupon Addition
*/
	$couponInfo = $this->getCouponInfo($request->discount_id);
	$couponData = array();
	if($couponInfo->amount!='' AND $couponInfo->amount!=null){
		//echo 'hi';
		$subtotalAmountAfterDiscount = $totalAmount-$couponInfo->amount;
		$subtotalAmountAfterDiscount = round($subtotalAmountAfterDiscount,2);
		$subtotalAmount = $subtotalAmountAfterDiscount;
		// echo $subtotalAmountAfterDiscount;
		
		$couponData = array(
			"amount"=>$subtotalAmountAfterDiscount,
			"coupon_id"=>$couponInfo->id,
			"coupon_code"=>$couponInfo->coupon_code,
			"membership_amount"=>$totalAmount,
			"discounted_amount"=>$couponInfo->amount,
		);
		$tempTableData = array_merge($tempTableData,$couponData);
	} else if($couponInfo->percentage!='' AND $couponInfo->percentage!=null){
		$discounted_amount = $totalAmount * $couponInfo->percentage/100;
		$discounted_amount = round($discounted_amount,2);
		$subtotalAmountAfterDiscount = round($totalAmount-$discounted_amount,2);
		$subtotalAmount              = round($subtotalAmountAfterDiscount,2);
				
		$couponData 	= array(
			"amount"=>$subtotalAmountAfterDiscount,
			"coupon_id"=>$couponInfo->id,
			"coupon_code"=>$couponInfo->coupon_code,
			"membership_amount"=>$totalAmount,
			"discounted_amount"=>$discounted_amount,
		);
		$tempTableData = array_merge($tempTableData,$couponData);
	} else{
		$subtotalAmountAfterDiscount = round($totalAmount,2);
		$withoutDiscount = array(
			"amount"=>$subtotalAmountAfterDiscount,
			"membership_amount"=>$totalAmount
		);
		$tempTableData = array_merge($tempTableData,$withoutDiscount);
	} 	
	
	
	$totalAmount =  $subtotalAmountAfterDiscount;
/* End Discount Coupon Addition */		
		
		
		$tempId = DB::table('temp_subscription_payments')->insertGetId($tempTableData);
		//echo $totalAmount;
		
		if($tempId){
			$quantity = 1;
			$clientId       = config('constants.PAYPAL_CLIENT_ID');
			$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
			$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					$clientId,$clientSecretId
				)
			); 
			
		$apiContext->setConfig( 
			array( 
				'mode' => 'live', 
				'log.LogEnabled' => true, 
				'log.FileName' => 'PayPal.log', 
				'log.LogLevel' => 'DEBUG', 
				// PLEASE USE INFO LEVEL FOR LOGGING IN LIVE ENVIRONMENTS 
				'cache.enabled' => true, 
			) 
		);
			/* $apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					'Ab8ItaowWf7vyWLrp2BFYXthERRcpmxPlEXn_K2vFL90wgh20GJNBrY81_c0eVB9q3KRjG-bqCwxWR_o','EMPJ0I-78LKzG4XzK4vtSyi8mRS1Fy9o4r43QhRFdnL7RKVi6OfPJfyNPqdLRcg6Tce494mOxS7UlEuc'
				)
			); */
	 
			$payer = new Payer();
			$payer->setPaymentMethod("paypal");
			/* Shipping address */

			/* End Shipping address */
			$item1 = new Item();
			$item1->setName($subscriptionTitle)
					->setCurrency('USD')
					->setQuantity(1)
					->setSku($order_id) // Similar to `item_number` in Classic API
					->setPrice($subtotalAmount);
			$itemList = new ItemList();
			/* $itemList->setItems(array($item1, $item2)); */
			$itemList->setItems(array($item1));
					
			/*  */
			$details = new Details();
			$details->setShipping($shippingAmount)
				->setTax($taxAmount)
				->setSubtotal($subtotalAmount);
			
			$amount = new Amount();
			$amount->setCurrency("USD")
					->setTotal($totalAmount)
					->setDetails($details);
				
			$transaction = new Transaction();
			$transaction->setAmount($amount)
						->setItemList($itemList)
						->setDescription("Payment description")
						->setInvoiceNumber(uniqid());
		
		
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("http://perfectlinkfitness.com/api/execute-subscription-payment?subtotalAmount=".$subtotalAmount."&orderId=".$order_id."&userId=".$user_id)->setCancelUrl("http://perfectlinkfitness.com/cancel-payment");
		
			$payment = new Payment();
			$payment->setIntent("sale")
					->setPayer($payer)
					->setRedirectUrls($redirectUrls)
					->setTransactions(array($transaction));
			
			$payment->create($apiContext);
			return redirect($payment->getApprovalLink());
		} else {
			$txn_id = 'TRASACTIONDECLINED';
			return view('paypal/success', ['txn_id'=>$txn_id]);
		}
		
    }
	
	/* public function executeSubscriptionPayment(){
		echo "xcvxcvxcv";
		die;
	} */
	public function executeSubscriptionPayment(){
		
		$clientId       = config('constants.PAYPAL_CLIENT_ID');
		$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$clientId,$clientSecretId
			)
		);
		
		$apiContext->setConfig( 
			array( 
				'mode' => 'live', 
				'log.LogEnabled' => true, 
				'log.FileName' => 'PayPal.log', 
				'log.LogLevel' => 'DEBUG', 
				// PLEASE USE INFO LEVEL FOR LOGGING IN LIVE ENVIRONMENTS 
				'cache.enabled' => true, 
			) 
		);
		/* $apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				'Ab8ItaowWf7vyWLrp2BFYXthERRcpmxPlEXn_K2vFL90wgh20GJNBrY81_c0eVB9q3KRjG-bqCwxWR_o','EMPJ0I-78LKzG4XzK4vtSyi8mRS1Fy9o4r43QhRFdnL7RKVi6OfPJfyNPqdLRcg6Tce494mOxS7UlEuc'
			)
		); */
	 
		$userId     = request('userId');
		$orderId    = request('orderId');
		//$shippingId = request('shippingId');
		$PayerID    = request('PayerID');
		$token      = request('token');
/*  Start User Info Fro Push Notification */
$userInfo = $this->getUserInfo($userId);		
/* End User Info Fro Push Notification */
		$subtotalAmount = request('subtotalAmount');
		$shippingAmount = 0;
		$taxAmount      = 0;
		$totalAmount    = $shippingAmount + $taxAmount + $subtotalAmount;
		if (empty($PayerID) || empty($token)) {
            $txn_id = 'TRASACTIONERROR';
			return view('paypal/success', ['txn_id'=>$txn_id]);
        } else {
			$paymentId = request('paymentId');
			$payment = Payment::get($paymentId, $apiContext);
			
			$execution = new PaymentExecution();
			$execution->setPayerId(request('PayerID'));
			
			$transaction = new Transaction();
			$amount = new Amount();
			$details = new Details();

			$details->setShipping($shippingAmount)
					->setTax($taxAmount)
					->setSubtotal($subtotalAmount);

			$amount->setCurrency('USD');
			$amount->setTotal($totalAmount);
			$amount->setDetails($details);
			$transaction->setAmount($amount);
			
			$execution->addTransaction($transaction);
			$result = $payment->execute($execution, $apiContext);

			if($result->getState() == 'approved') {
				//$shippingInfo  = $this->getShippingInfo($shippingId,$userId);
				$tempTableData = $this->getOrderInfoFromTempSubscriptionTable($orderId,$userId);
				$trans         = $result->getTransactions();
				// item info
				$Subtotal         = $trans[0]->getAmount()->getDetails()->getSubtotal();
				$Tax              = $trans[0]->getAmount()->getDetails()->getTax();
				$payer            = $result->getPayer();
				// payer info
				$PaymentMethod    = $payer->getPaymentMethod();
				$PayerStatus      = $payer->getStatus();
				$PayerMail        = $payer->getPayerInfo()->getEmail();
				$relatedResources = $trans[0]->getRelatedResources();
				$sale             = $relatedResources[0]->getSale();
				// sale info
				$saleId           = $sale->getId();
			
				$CreateTime       = $sale->getCreateTime();
				$UpdateTime       = $sale->getUpdateTime();
				$State            = $sale->getState();
				$Total            = $sale->getAmount()->getTotal();
			
			if($tempTableData->subscription_for=='TRAINER'){
				$paymentTable = array(
					"user_id"               => $userId,
					"order_id"              => $orderId,
					"transaction_id"        => $saleId,
					"trainer_id"            => $tempTableData->trainer_id,
					"subscription_for"      => $tempTableData->subscription_for,
					"membership_type"       => $tempTableData->membership_type,
					"start_date"            => $tempTableData->start_date,
					"months"                => $tempTableData->months,
					"member_count"          => $tempTableData->member_count,
					"amount"                => $tempTableData->amount,
					"tax"                   => $Tax,
					"shipping"              => $shippingAmount,
					"sub_total"             => $Subtotal,
					"total"                 => $Total,
					"payer_status"          => $PayerStatus,
					"payment_state"         => $State,
					"payment_method"        => $PaymentMethod,
					"payer_email"           => $PayerMail,
					
					"coupon_id"				=> $tempTableData->coupon_id,
					"coupon_code"			=> $tempTableData->coupon_code,
					"membership_amount"		=> $tempTableData->membership_amount,
					"discounted_amount"		=> $tempTableData->discounted_amount,
					
					"purchase_status"       => "1",
					"payment_created_at"    => $CreateTime,
					"payment_update_time"   => $UpdateTime,
					"created_at"            => date('Y-m-d h:i:s')
				);
	/*TRAINER Subscription Notification*/
	$notification = array(
		"user_id" =>$userId, 
		"type" =>"TRAINER",
		"transaction_id"=>$saleId,
		"message" =>"You have subscribed for trainer.",
		"created_at" =>date('Y-m-d h:i:s')
	);
	/*End TRAINER Subscription */
		/* Push Notification */	
		$trainerInfo = $this->getTrainerInfo($tempTableData->trainer_id);
		$deviceInfo = $this->get_device_token($userId);
		$tokenId = array($deviceInfo->device_token);
		$pushNotification = array(
			"title" => $trainerInfo->name,
			"body"  => "Subscription Purchased successfully.",
			"icon"  => $trainerInfo->profile_picture,  
			"flag"  => 12,
		);
		
		$notificationData = array(
				"title" => $userInfo->name,
				"body"  => "You have received new subscription.",
				"icon"  => $userInfo->profile_picture,  
				"flag"  => 12,
				"id"    => $saleId,
			);
		$this->push_notification($tokenId,$pushNotification,$notificationData); 
	
	/* End Push Notification */	
			}			
			if($tempTableData->subscription_for=='GYM'){
				$paymentTable = array(
					"user_id"               => $userId,
					"order_id"              => $orderId,
					"transaction_id"        => $saleId,
					"gym_id"                => $tempTableData->gym_id,
					"subscription_for"      => $tempTableData->subscription_for,
					"membership_type"       => $tempTableData->membership_type,
					"start_date"            => $tempTableData->start_date,
					"months"                => $tempTableData->months,
					"member_count"          => $tempTableData->member_count,
					"amount"                => $tempTableData->amount,
					"tax"                   => $Tax,
					"shipping"              => $shippingAmount,
					"sub_total"             => $Subtotal,
					"total"                 => $Total,
					"payer_status"          => $PayerStatus,
					"payment_state"         => $State,
					"payment_method"        => $PaymentMethod,
					"payer_email"           => $PayerMail,
					
					"coupon_id"				=> $tempTableData->coupon_id,
					"coupon_code"			=> $tempTableData->coupon_code,
					"membership_amount"		=> $tempTableData->membership_amount,
					"discounted_amount"		=> $tempTableData->discounted_amount,
					
					"purchase_status"       => "1",
					"payment_created_at"    => $CreateTime,
					"payment_update_time"   => $UpdateTime,
					"created_at"            => date('Y-m-d h:i:s')
				);
	/*GYM Subscription Notification*/
	$notification = array(
		"user_id" =>$userId, 
		"type" =>"GYM",
		"transaction_id"=>$saleId,
		"message" =>"You have subscribed for Gym.",
		"created_at" =>date('Y-m-d h:i:s')
	);
	/*End GYM Subscription*/
	/* Push Notification */	
		$gymInfo = $this->getGymInfo($tempTableData->gym_id);
		$deviceInfo = $this->get_device_token($userId);
		$tokenId = array($deviceInfo->device_token);
		$pushNotification = array(
			"title" => $gymInfo->gym_title,
			"body"  => "Subscription Purchased successfully.",
			"icon"  => $gymInfo->cover_image,  
			"flag"  => 11,
		);
		 
			$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "You have received new subscription.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 11,
					"id"    => $saleId,
				);
			$this->push_notification($tokenId,$pushNotification,$notificationData); 
		
	/* End Push Notification */	
	
	
			}
			
				$paymentFinal = DB::table('subscription_payments')->insertGetId($paymentTable);
				if($paymentFinal){
					$notificationSent = DB::table('fitness_notification')->insertGetId($notification);
				}
			/* echo '<pre>';
				print_r($paymentTable); 
				die; */
				return view('paypal/success', ['txn_id'=>$saleId]);
			}
		
		}
	}
	
	public function getSubscriptionInfoByTransactionId(Request $request){
		$subscriptionInfo = DB::table('subscription_payments')->select('*')->where(['transaction_id'=>$request->transaction_id])->first(); 
		$subscriptionDetails = $this->subscriptionInfoByTransactionIdAndSubscriptionFor($subscriptionInfo->subscription_for,$request->transaction_id);
		if(!empty($subscriptionDetails)){
			return response(['STATUS'=>'true','message'=>'Subscription Info.','response'=>$subscriptionDetails]);
		} else{
			return response(['STATUS'=>'false','message'=>'Transaction Error']);
		} 
	}
	
	function subscriptionInfoByTransactionIdAndSubscriptionFor($orderType,$transationId){
		$myorder =  (object) array();
		if($orderType=='TRAINER'){
			
			$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at','created_at')
				->where(['transaction_id'=>$transationId])
				->first();
			/* $productInfo = $this->productdetailsByProductId($orderInfo->product_id);  */
				$trainerInfo = $this->getTrainerInfo($orderInfo->trainer_id);
				$trainerInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$trainerInfo->profile_picture; 
			    $subscriptionDetails = (object) array_merge((array) $orderInfo,(array) $trainerInfo);
				$days = '+'.$subscriptionDetails->months.' days';
				$ENDATE = strtotime($days,strtotime($subscriptionDetails->start_date));
				$endDate = date("Y-m-d",$ENDATE);
				$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
								
			//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorder->order_id        = $subscriptionDetails->order_id;
					$myorder->transaction_id  = $subscriptionDetails->transaction_id;
					$myorder->purchase_date   = $subscriptionDetails->created_at;
					$myorder->order_status    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
					$myorder->shipment_detail = (object) array(
						"id"                  =>$subscriptionDetails->user_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"name"                =>$subscriptionDetails->name,
						"brief_description"   =>strip_tags($subscriptionDetails->about),
						"full_description"    =>strip_tags($subscriptionDetails->about),
						"cover_image"         =>$subscriptionDetails->profile_picture, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						"single_membership_rate"=>$subscriptionDetails->single_membership_rate,
						"group_membership_rate"=>$subscriptionDetails->group_membership_rate,
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft
					);
					$myorder->payment_detail  = (object) array(
						'total_amount_paid'  => $subscriptionDetails->total_amount_paid,
						'payer_status'       => $subscriptionDetails->payer_status,
						'payment_method'     => $subscriptionDetails->payment_method,
						'payment_created_at' => $subscriptionDetails->payment_created_at,
						'payer_email'        => $subscriptionDetails->payer_email,
						'is_coupon_applied'  => $subscriptionDetails->coupon_id!=null?true:false,
						'coupon_id'          => $subscriptionDetails->coupon_id,
						'coupon_code'        => $subscriptionDetails->coupon_code,
						'membership_amount'  => $subscriptionDetails->membership_amount,
						'discounted_amount'  => $subscriptionDetails->discounted_amount
					);
			return $myorder; 
			
		} else if($orderType=='GYM'){
			$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at','created_at')
				->where(['transaction_id'=>$transationId])
				->first();
			$gymInfo = $this->getGymInfo($orderInfo->gym_id);
			$subscriptionDetails = (object) array_merge((array) $orderInfo,(array) $gymInfo);
			$months = '+'.$subscriptionDetails->months.' months';
			$ENDATE = strtotime($months,strtotime($subscriptionDetails->start_date));
			$endDate = date("Y-m-d",$ENDATE);
			$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
		//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorder->order_id        = $subscriptionDetails->order_id;
					$myorder->transaction_id  = $subscriptionDetails->transaction_id;
					$myorder->purchase_date   = $subscriptionDetails->created_at;
					$myorder->order_status    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
			
			/* print_r($subscriptionDetails);
			die; */
			$myorder->shipment_detail = (object) array(
						"id"                  =>$subscriptionDetails->gym_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"name"                =>$subscriptionDetails->gym_title,
						"brief_description"   =>strip_tags($subscriptionDetails->brief_description),
						"full_description"    =>strip_tags($subscriptionDetails->full_description),
						"cover_image"         =>$subscriptionDetails->cover_image, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						"subscription_amount"=>$subscriptionDetails->subscription_amount,
						//"group_membership_rate"=>$subscriptionDetails->group_membership_rate,
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft
					);
			$myorder->payment_detail  = (object) array(
						'total_amount_paid'  => $subscriptionDetails->total_amount_paid,
						'payer_status'       => $subscriptionDetails->payer_status,
						'payment_method'     => $subscriptionDetails->payment_method,
						'payment_created_at' => $subscriptionDetails->payment_created_at,
						'payer_email'        => $subscriptionDetails->payer_email,
						'is_coupon_applied'  => $subscriptionDetails->coupon_id!=null?true:false,
						'coupon_id'          => $subscriptionDetails->coupon_id,
						'coupon_code'        => $subscriptionDetails->coupon_code,
						'membership_amount'  => $subscriptionDetails->membership_amount,
						'discounted_amount'  => $subscriptionDetails->discounted_amount
					);
			return $myorder; 
			
		}
	}
	function getGymInfo($gym_id){
		$gymDetails = DB::table('gym_users')->where(['active_status'=>1,'user_id'=>$gym_id])
		->select('gym_users.user_id as gym_id','gym_users.*')
		->first();
		$gymDetails->cover_image=url('/').self::COVER_IMAGE_FULL_PATH.$gymDetails->cover_image;
		return $gymDetails;
	}
	
	
	function dateDiffInDays($date1, $date2) { 
		$diff = strtotime($date2) - strtotime($date1); 
		// 1 day = 24 hours 
		// 24 * 60 * 60 = 86400 seconds 
		return abs(round($diff / 86400)); 
	} 
	
	public function checkAvilableUser(Request $request){ 
		$count = DB::table('users')->where(['user_name'=>$request->user_name])->count();
		if($count==0){
			return response(['STATUS'=>'true','message'=>'You can take this user name.']);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'User name already taken.']);
		}
	}
	
	
	public function addWall(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[
				'user_id'   => 'required|max:255',
				'text'      => 'required',
				'file'      => 'required',
				'file_type' => 'required', 
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$parkImage = $request->file('file');
			if(!empty($parkImage)){
				$validatedData['file'] = time().$user->user_id.'.'.$parkImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/images/wall');
				$parkImage->move($destinationPath, $validatedData['file']);
				/* Start Create Thumbnail */
				if($request->file_type=='VIDEO'){
					$file = url('/').self::WALL_FULL_PATH . $validatedData['file'];
					$thumbnailName = time().$user->user_id.'thumbnail.png';
					$thumbnail = $this->createThumbnail($file,$thumbnailName);
					//print_r($thumbnail);die; 
					$duration  = $thumbnail['duration'];
					$thumbnail = $thumbnail['thumbnail_name'];
				} else {
					$duration  = '';
					$thumbnail = '';
				}
				$validatedData['duration'] = $duration;
				$validatedData['thumbnail'] = $thumbnail;
				/* End Create Thumbnail */
				
				
			}
			$id = DB::table('user_wall')->insertGetId($validatedData);
		if($id){
			return response(['STATUS'=>'true','message'=>'Your Wall is Updated.']);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	public function addWallComment(Request $request){
		$validatedData = $this->validate($request,[
				'user_id'     => 'required|max:255',
				'wall_id'     => 'required',
				'comment'     => 'required'
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
	/* 	$count = DB::table('wall_comment')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->count();
		if($count==0){ */
			$id = DB::table('wall_comment')->insertGetId($validatedData); 
			if($id){
				$commentPosted = $this->getCommentById($request->wall_id,$id);
				$commentPosted->posted_at = strtotime($commentPosted->created_at);
				return response(['STATUS'=>'true','message'=>'Your comment is posted sucessfully.','response'=>$commentPosted]);  
			} else { 
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}
		/* } else {
			return response(['STATUS'=>'false','message'=>'You have already reported for this wall.']);
		} */
	}	
	public function getWallCommentCount($wall_id){
		$count = DB::table('wall_comment')->where(['wall_id'=>$wall_id])->count();
		return $count;
	}
	public function getCommentById($wall_id,$id){
		$commentPosted = DB::table('wall_comment')
						->join('users', 'wall_comment.user_id', '=', 'users.user_id')
						->select('users.name','users.profile_picture','wall_comment.*')
						->where(['wall_comment.id'=>$id,'wall_comment.wall_id'=>$wall_id])->first();
		$commentPosted->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $commentPosted->profile_picture;
/* $gymList = DB::table('gym_users')
                ->join('gym_category', 'gym_users.gym_category', '=', 'gym_category.id')
                ->select('gym_users.*','gym_category.id as catid','gym_category.*')
				->where(['status'=>1,'gym_category'=>$request->category_id])
                ->get();  */
		
		return $commentPosted;
	}
	public function getWallComment(Request $request){
		$wallCommentList = DB::table('wall_comment')
				->join('users', 'wall_comment.user_id', '=', 'users.user_id')
                ->select('users.name','users.profile_picture','wall_comment.*')
				->where(['wall_comment.wall_id'=>$request->wall_id])
				->orderBy('wall_comment.created_at', 'desc')->get();  
		foreach($wallCommentList as $key=>$val){
			$wallCommentList[$key]->posted_at = strtotime($val->created_at);
			$wallCommentList[$key]->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $val->profile_picture;
		}
		return response(['STATUS'=>'true','message'=>'Your comment list.','response'=>$wallCommentList]);  
	}
	/* 
	public function likeUnlike(Request $request){
		$validatedData = $this->validate($request,[
				'user_id'     => 'required|max:255',
				'wall_id'     => 'required',
				'like_unlike' => 'required'
			]);
			$count = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->count();
		if($count==0){
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$id = DB::table('wall_like_unlike')->insertGetId($validatedData); 
			if($id){
				return response(['STATUS'=>'true','message'=>'Status Updated.']);  
			} else { 
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}
		} else {
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$updateWall = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'id'=>$request->wall_id])->update(['like_unlike'=>$request->like_unlike]);
			if($updateWall){
				return response(['STATUS'=>'true','message'=>'Status Updated.']);  
			} else{ 
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}
		}
	} 
	*/
	

	public function wallLike(Request $request){
		$validatedData = $this->validate($request,[
				'user_id'     => 'required|max:255',
				'wall_id'     => 'required',
				'like_unlike' => 'required'
			]);
			$count1 = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id,'like_unlike'=>0])->count();
			
		if($count1==0){
			$count2 = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id,'like_unlike'=>1])->count();
			if($count2==0){
				$validatedData['created_at'] = date('Y-m-d h:i:s');
				$id = DB::table('wall_like_unlike')->insertGetId($validatedData); 
				if($id){
					return response(['STATUS'=>'true','message'=>'Status Liked.']);  
				} else { 
					return response(['STATUS'=>'false','message'=>'Network Error1']);
				}
			} else {
				$updateWall = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->delete();
				if($updateWall){
					return response(['STATUS'=>'true','message'=>'Status Like Removed.']);  
				} else{ 
					return response(['STATUS'=>'false','message'=>'Network Error2']);
				} 
		    } 
		} else{ 
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$updateWall = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->update(['like_unlike'=>$request->like_unlike]);
			if($updateWall){
				return response(['STATUS'=>'true','message'=>'Status Updated.']);  
			} else{ 
				return response(['STATUS'=>'false','message'=>'Network Error4']);
			}
		}
	}
	public function wallDislike(Request $request){
		$validatedData = $this->validate($request,[
				'user_id'     => 'required|max:255',
				'wall_id'     => 'required',
				'like_unlike' => 'required'
			]);
			$count1 = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id,'like_unlike'=>1])->count();
			
		if($count1==0){
			$count2 = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id,'like_unlike'=>0])->count();
			if($count2==0){
				$validatedData['created_at'] = date('Y-m-d h:i:s');
				$id = DB::table('wall_like_unlike')->insertGetId($validatedData); 
				if($id){
					return response(['STATUS'=>'true','message'=>'Status Liked.']);  
				} else { 
					return response(['STATUS'=>'false','message'=>'Network Error1']);
				}
			} else {
				$updateWall = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->delete();
				if($updateWall){
					return response(['STATUS'=>'true','message'=>'Status Like Removed.']);  
				} else{ 
					return response(['STATUS'=>'false','message'=>'Network Error2']);
				} 
		    } 
		} else{ 
			$validatedData['updated_at'] = date('Y-m-d h:i:s');
			$updateWall = DB::table('wall_like_unlike')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->update(['like_unlike'=>$request->like_unlike]);
			if($updateWall){
				return response(['STATUS'=>'true','message'=>'Status Updated.']);  
			} else{ 
				return response(['STATUS'=>'false','message'=>'Network Error4']);
			}
		}
		
	}
	public function reportToAdmin(Request $request){
		$validatedData = $this->validate($request,[
				'user_id'     => 'required|max:255',
				'wall_id'     => 'required',
				'comment'     => 'required'
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
		$count = DB::table('wall_report')->where(['user_id'=>$request->user_id,'wall_id'=>$request->wall_id])->count();
		if($count==0){
			$id = DB::table('wall_report')->insertGetId($validatedData); 
			if($id){
				return response(['STATUS'=>'true','message'=>'Reported to Admin.']);  
			} else { 
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}
		} else {
			return response(['STATUS'=>'false','message'=>'You have already reported for this wall.']);
		}
	}
	public function getWallList(Request $request){    
		$user = auth()->user();	
		$wallList = DB::table('user_wall')
                ->select('user_wall.id','user_wall.user_id','user_wall.text','user_wall.file','user_wall.file_type','user_wall.duration','user_wall.thumbnail','user_wall.created_at')
				->where(['user_wall.status'=>1])
				->orderBy('created_at', 'desc')->get(); 
				
		foreach($wallList as $key=>$val){
			$wallList[$key]->file = url('/').self::WALL_FULL_PATH.$val->file;
			$wallList[$key]->thumbnail = url('/').self::WALL_THUMBNAIL_PATH.$val->thumbnail;
			$countLIke   = DB::table('wall_like_unlike')->where(['wall_id'=>$val->id,'like_unlike'=>1])->count();
			$countUnlike = DB::table('wall_like_unlike')->where(['wall_id'=>$val->id,'like_unlike'=>0])->count();
			$userInfo = $this->getUserInfo($val->user_id);
			$isLike = DB::table('wall_like_unlike')->where(['user_id'=>$user->user_id,'wall_id'=>$val->id,'like_unlike'=>1])->count();
			$isUnlike = DB::table('wall_like_unlike')->where(['user_id'=>$user->user_id,'wall_id'=>$val->id,'like_unlike'=>0])->count();
			//print_r($userInfo);die;
			$wallList[$key]->is_liked  = $isLike;
			$wallList[$key]->is_unlike = $isUnlike;
			$wallList[$key]->name = $userInfo->name;
			$wallList[$key]->profile_picture = $userInfo->profile_picture;
			$wallList[$key]->likes     = $countLIke;
			$wallList[$key]->unlikes   = $countUnlike;
			$wallList[$key]->posted_at = strtotime($val->created_at);
			$wallList[$key]->comment_count   = $this->getWallCommentCount($val->id);
		}
		return response(['STATUS'=>'true','message'=>'Wall Lists','response'=>$wallList]);
	}
	public function getNotifications(Request $request){
		$notification = DB::table('fitness_notification')->select('*')->where(['user_id'=>$request->user_id])->get();
		foreach($notification as $key=>$val){
			if($val->type == 'GYM'){
				$gymInfo = DB::table('subscription_payments')
                ->join('gym_users', 'subscription_payments.gym_id', '=', 'gym_users.user_id')
                ->select('subscription_payments.order_id','subscription_payments.gym_id','gym_users.cover_image')
				->where(['subscription_payments.transaction_id'=>$val->transaction_id])
                ->first(); 				
				$notification[$key]->image=url('/').self::COVER_IMAGE_FULL_PATH.$gymInfo->cover_image;				
			} else if($val->type == 'TRAINER'){
				$trainerInfo = DB::table('subscription_payments')
                ->join('users', 'subscription_payments.trainer_id', '=', 'users.user_id')
                ->select('subscription_payments.order_id','subscription_payments.trainer_id','users.profile_picture')
				->where(['subscription_payments.transaction_id'=>$val->transaction_id])
                ->first(); 			
				$notification[$key]->image=url('/').self::PROFILE_IMAGE_PATH.$trainerInfo->profile_picture;
			} else if($val->type == 'SUPPLEMENTS'){
				$productInfo = DB::table('payments')
                ->join('products', 'payments.product_id', '=', 'products.product_id')
                ->select('payments.order_id','payments.product_id','products.cover_image')
				->where(['payments.transaction_id'=>$val->transaction_id])
                ->first(); 	
				$notification[$key]->image = url('/').self::PRODUCT_COVER_IMAGE_FULL_PATH.$productInfo->cover_image;
			} else if($val->type == 'ACCESSORIES'){
				$accessoriesInfo = DB::table('payments')
                ->join('accessories', 'payments.accessorie_id', '=', 'accessories.accessorie_id')
                ->select('payments.order_id','payments.accessorie_id','accessories.cover_image')
				->where(['payments.transaction_id'=>$val->transaction_id])
                ->first(); 	
				$accessoriesLists[$key]->image = url('/').self::ACCESSORIES_COVER_IMAGE_FULL_PATH.$accessoriesInfo->cover_image;
			} else if($val->type == 'DISCOUNT'){
				$notification[$key]->image = '';
			} else if($val->type == 'MEALS'){
				$notification[$key]->image = '';
			}
		}
		return response(['STATUS'=>'true','message'=>'Notification Info.','response'=>$notification]);
	}
	
	
	
	public function addChallange(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[
				'user_id'   => 'required|max:255',
				'text'      => 'required',
				'file'      => 'required',
				'file_type' => 'required',
				'friends_id'=> 'required'
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$challangeImage = $request->file('file');
			if(!empty($challangeImage)){
				$fileName = time().$user->user_id.'.'.$challangeImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/challange');
				$challangeImage->move($destinationPath, $fileName);
				/* Start Create Thumbnail */
				if($request->file_type=='VIDEO'){
					$file = url('/').self::CHALLANGE_FULL_PATH . $fileName;
					$thumbnailName = time().$user->user_id.'thumbnail.png';
					$thumbnail = $this->createThumbnail($file,$thumbnailName);
					//print_r($thumbnail);die; 
					$duration  = $thumbnail['duration'];
					$thumbnail = $thumbnail['thumbnail_name'];
				} else {
					$duration  = '';
					$thumbnail = '';
				}
				/* End Create Thumbnail */
				$challangeArray = array(
					'user_id'   => $request->user_id,
					'text'      => $request->text,
					'file'      => $fileName,
					'file_type' => $request->file_type,
					'thumbnail' => $thumbnail,
					'duration'  => $duration,
					'created_at'=> date('Y-m-d h:i:s')
				);
			}
			
			$id = DB::table('user_challange')->insertGetId($challangeArray);
		if($id){
			$friendsArray = explode(',',$request->friends_id);
			//print_r($friendsArray);
			foreach($friendsArray as $key=>$val){
				$newArray[]= array(
						'challange_id'  => $id,
						'user_id'       => $val,
						'challanger_id' => $request->user_id,
						'created_at'    => date('Y-m-d h:i:s')
					);
				$notification[] = array(
					"user_id" 	 =>$request->user_id, 
					"friend_id"  =>$val,
					"type" 		 =>"CHALLANGE",
					"message" 	 =>"You have received a challange request",
					"created_at" =>date('Y-m-d h:i:s')
				);
				/* Device token for push notification */
				$deviceInfo = $this->get_device_token($val);
				$tokenId[] = $deviceInfo->device_token;
				/*End Device token for push notification */
			}
			$id = DB::table('challanged_friend')->insert($newArray);
			
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($request->user_id);
				/* $deviceInfo = $this->get_device_token($friendInfo->user_id);
				$tokenId = array($deviceInfo->device_token); */
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a challange.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 4,
					"data"  => array (
						"id"  => $request->user_id,
					) 
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 4,
					"id"    => $request->user_id
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */	
			
			
			$notificationSent = DB::table('fitness_notification')->insert($notification);
			return response(['STATUS'=>'true','message'=>'Challange request has been sent.']);
		} else{ 
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	
	public function acceptChallange(Request $request){
		
			$validatedData = $this->validate($request,[
				'user_id'      => 'required|max:255',
				'challange_id' => 'required',
			]);
			$count = DB::table('challanged_friend')->where(['user_id'=>$request->user_id,'challange_id'=>$request->challange_id])->count();
		if($count>0){
			$challanger = DB::table('challanged_friend')->select('challanger_id')->where(['user_id'=>$request->user_id,'challange_id'=>$request->challange_id])->first();
			
			$validatedData['accepted_at'] = date('Y-m-d h:i:s');
			$acceptedDate = date('Y-m-d h:i:s');
			$acceptChallange = DB::table('challanged_friend')->where(['user_id'=>$request->user_id,'challange_id'=>$request->challange_id])->update(['status'=>1,'accepted_at'=>$acceptedDate]);
			
				$notification = array(
					"user_id" 	 =>$challanger->challanger_id, 
					"friend_id"  =>$request->user_id,
					"type" 		 =>"CHALLANGE",
					"message" 	 =>"Your challange has been accepted.",
					"created_at" =>date('Y-m-d h:i:s')
				); 
			$notificationSent = DB::table('fitness_notification')->insert($notification);
			
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($request->user_id);
				$deviceInfo = $this->get_device_token($challanger->challanger_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Accepted your challange.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 5,
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 5,
					"id"    => $request->user_id
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */	
				$chalaneInfo = $this->getChallangeInfo($request->challange_id);

				$challangerInfo = $this->getUserInfoByUserId($chalaneInfo->user_id);
				//print_r($challangerInfo);die;
				$userInfoId = $this->getUserInfoByUserId($request->user_id);
				
				$participants = $this->getParticipantList($chalaneInfo->id);
				$challangeData = array(
					"challange_id"=>$chalaneInfo->id,
					"user_id"=>$chalaneInfo->user_id,
					"text"=>$chalaneInfo->text,
					"file"=>$chalaneInfo->file,
					"thumbnail"=>$chalaneInfo->thumbnail,
					"duration"=>$chalaneInfo->duration,
					"file_type"=>$chalaneInfo->file_type,
					"name"=>$challangerInfo->name,
					"profile_image"=>$challangerInfo->profile_picture,
					"participant"=>$participants
				);
			
			
			return response(['STATUS'=>'true','message'=>'Challange Accepted.','response'=>$challangeData]);
		} else{ 
			return response(['STATUS'=>'false','message'=>'Challange doesnot exists at this time.']);
		}
	}
	
	public function completeChallange(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[
				'user_id'   => 'required|max:255',
				'text'      => 'required',
				'file'      => 'required',
				'file_type' => 'required',
				'challange_id'=> 'required'
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$challangeImage = $request->file('file');
			if(!empty($challangeImage)){
				$validatedData['file'] = time().$user->user_id.'.'.$challangeImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/challange');
				$challangeImage->move($destinationPath, $validatedData['file']);
				
				/* Start Create Thumbnail */
				if($request->file_type=='VIDEO'){
					$file = url('/').self::CHALLANGE_FULL_PATH . $validatedData['file'];
					$thumbnailName = time().$user->user_id.'thumbnail.png';
					$thumbnail = $this->createThumbnail($file,$thumbnailName);
					//print_r($thumbnail);die; 
					$duration  = $thumbnail['duration'];
					$thumbnail = $thumbnail['thumbnail_name'];
				} else {
					$duration  = '';
					$thumbnail = '';
				}
				$validatedData['thumbnail'] = $thumbnail;
				$validatedData['duration']  = $duration;
				
				/* End Create Thumbnail */
				
			}
			
			$id = DB::table('challange_complete')->insertGetId($validatedData);
		if($id){
			$completedDate = date('Y-m-d h:i:s');
			$completeChallangeStatus = DB::table('challanged_friend')->where(['user_id'=>$request->user_id,'challange_id'=>$request->challange_id])->update(['status'=>2,'completed_at'=>$completedDate]);
			
			$challanger = DB::table('challanged_friend')->select('challanger_id')->where(['user_id'=>$request->user_id,'challange_id'=>$request->challange_id])->first();
			$notification = array(
					"user_id" 	 =>$request->user_id, 
					"friend_id"  =>$challanger->challanger_id,
					"type" 		 =>"CHALLANGE",
					"message" 	 =>"You challange has been completed.",
					"created_at" =>date('Y-m-d h:i:s')
				);
			$notificationSent = DB::table('fitness_notification')->insertGetId($notification);
			
		/* Push Notification */
				$userInfo = $this->getUserInfoByUserId($request->user_id);
				$deviceInfo = $this->get_device_token($challanger->challanger_id);
				$tokenId = array($deviceInfo->device_token);
				$pushNotification = array(
					"title" => $userInfo->name,
					"body"  => "Completed your challange.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 6,
				);
				$notificationData = array(
					"title" => $userInfo->name,
					"body"  => "Sent you a friend request.",
					"icon"  => $userInfo->profile_picture,  
					"flag"  => 6,
					"id"    => $request->user_id
				);
				$this->push_notification($tokenId,$pushNotification,$notificationData);
		/* End Push Notification */	
					
					$chalaneInfo = $this->getChallangeInfo($request->challange_id);
					$userInfoId = $this->getUserInfoByUserId($request->user_id);
					
					$participants = $this->getParticipantList($chalaneInfo->id);
					$challangeData = array(
						"challange_id"=>$chalaneInfo->id,
						"user_id"=>$chalaneInfo->user_id,
						"text"=>$chalaneInfo->text,
						"file"=>$chalaneInfo->file,
						"file_type"=>$chalaneInfo->file_type,
						"name"=>$userInfoId->name,
						"thumbnail"=>$chalaneInfo->thumbnail,
						"duration"=>$chalaneInfo->duration,
						"profile_image"=>$userInfoId->profile_picture,
						"participant"=>$participants
					);
			
			
			return response(['STATUS'=>'true','message'=>'Challange has been completed.','response'=>$challangeData]);
		} else{ 
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	function getChallangeInfo($challangeId){
		$challange = DB::table('user_challange')->where(['id'=>$challangeId])->first();
		$challangeRequestFile = url('/').self::CHALLANGE_FULL_PATH . $challange->file;
		$challange->file = $challangeRequestFile;
		$challange->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH . $challange->thumbnail;
		return $challange;	
	}
	function getParticipantList($challangeId){
		$return = DB::table('challanged_friend')->where(['challange_id'=>$challangeId])->get();
		$chalaneInfo = $this->getChallangeInfo($challangeId);
		foreach($return as $key=>$value){
			$userInfoId = $this->getUserInfoByUserId($value->user_id);
			$arr[] = array(
				"user_id"=>$value->user_id,
				"name"=>$userInfoId->name,
				"profile_image"=>$userInfoId->profile_picture,
				"file"=>$chalaneInfo->file,
				"challange_status"=>$value->status,
				"text"=>"",
				"completed_url"=>"",
				"thumbnail"=>$chalaneInfo->thumbnail,
				"duration"=>$chalaneInfo->duration,
				
				"completed_duration"=>"",
				"completed_thumbnail"=>"",
								
				"created_at"=>$value->created_at,
				"accepted_at"=>$value->accepted_at,
				"completed_at"=>$value->completed_at
			);
		}
		return $arr;
		
	}	
	
	public function challangeRequestSendList(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[ 'user_id' => 'required|max:100' ]);
		$arr = array();
		/*  
			$challangeList = DB::table('user_challange')
                ->join('challanged_friend', 'user_challange.id', '=', 'challanged_friend.challange_id')
                ->select('user_challange.id as challange_id','user_challange.user_id','user_challange.text','user_challange.file','user_challange.file_type','challanged_friend.status','challanged_friend.id','challanged_friend.user_id as friend_id',
				'challanged_friend.created_at','challanged_friend.accepted_at','challanged_friend.completed_at')
				->where(['user_challange.user_id'=>$request->user_id])
				->where('challanged_friend.status', '!=' , 2)
				->orderByDesc('user_challange.created_at')
				->get(); 
		*/
			$challangeList = DB::table('challanged_friend')->distinct()
							->where(['challanger_id'=>$request->user_id])
							->orderBy('challange_id', 'desc')->get(['challange_id']);
		
			if(!empty($challangeList)){
				foreach($challangeList as $key=>$challange){
					$chalaneInfo = $this->getChallangeInfo($challange->challange_id);
					$userInfoId = $this->getUserInfoByUserId($chalaneInfo->user_id);
					
					$participants = $this->getParticipantList($chalaneInfo->id);
					$arr[] = array(
						"challange_id"=>$chalaneInfo->id,
						"user_id"=>$chalaneInfo->user_id,
						"text"=>$chalaneInfo->text,
						"file"=>$chalaneInfo->file,
						"file_type"=>$chalaneInfo->file_type,
						
						"duration"=>$chalaneInfo->duration,
						"thumbnail"=>$chalaneInfo->thumbnail,
						
						"name"=>$userInfoId->name,
						"profile_image"=>$userInfoId->profile_picture,
						"participant"=>$participants
					);
				}
				
				return response(['STATUS'=>'true','message'=>'Challange List','response'=>$arr]);
			} else { 
				return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
			}
		/* if(!empty($challangeList)){
			foreach($challangeList as $key=>$challange){
				
				$userInfoId = $this->getUserInfoByUserId($challange->user_id);
				$challangeList[$key]->name = $userInfoId->name;
				$challangeList[$key]->profile_image = $userInfoId->profile_picture;
				
				$challangeRequestFile = url('/').self::CHALLANGE_FULL_PATH . $challange->file;
				$challangeList[$key]->file = $challangeRequestFile;
				
				
				$userInfo = $this->getUserInfoByUserId($challange->friend_id);
				$challangeList[$key]->participant = array(
					'user_id'=>$challange->friend_id,
					'name'=>$userInfo->name,
					'profile_image'=>$userInfo->profile_picture,
					'file'=>$challangeRequestFile,
					'challange_status'=>$challange->status,
					'text'=>'',
					'completed_url'=>'',
					'created_at'=>$challange->created_at,
					'accepted_at'=>$challange->accepted_at,
					'completed_at' =>$challange->completed_at
				);
				unset($challangeList[$key]->status);
				unset($challangeList[$key]->friend_id);				
				unset($challangeList[$key]->created_at);				
				unset($challangeList[$key]->accepted_at);				
				unset($challangeList[$key]->completed_at);				
			}
			return response(['STATUS'=>'true','message'=>'Challange List','response'=>$challangeList]);
		} else { 
			return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
		} */
	}
	
	public function challangeRequestReceivedList(Request $request){
		$user = auth()->user();	
		$arr = array();
		$validatedData = $this->validate($request,[ 'user_id' => 'required|max:100' ]);
		$challangeList = DB::table('challanged_friend')->distinct()
							->where(['user_id'=>$request->user_id])->where('status', '!=' , 2)
							->orderBy('challange_id', 'desc')->get(['challange_id']);

		if(!empty($challangeList)){
				foreach($challangeList as $key=>$challange){
					$chalaneInfo = $this->getChallangeInfo($challange->challange_id);
					$userInfoId  = $this->getUserInfoByUserId($chalaneInfo->user_id);
					
					$participants = $this->getParticipantList($chalaneInfo->id);
					$arr[] = array(
						"challange_id"=>$chalaneInfo->id,
						"user_id"=>$chalaneInfo->user_id,
						"text"=>$chalaneInfo->text,
						"file"=>$chalaneInfo->file,
						"file_type"=>$chalaneInfo->file_type,
						
						"duration"=>$chalaneInfo->duration,
						"thumbnail"=>$chalaneInfo->thumbnail,
						
						"name"=>$userInfoId->name,
						"profile_image"=>$userInfoId->profile_picture,
						"participant"=>$participants
					);
				}
				
				return response(['STATUS'=>'true','message'=>'Challange List','response'=>$arr]);
			} else { 
				return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
			}
		/* $challangeList = DB::table('user_challange')
                ->join('challanged_friend', 'user_challange.id', '=', 'challanged_friend.challange_id')
				->select('user_challange.id','user_challange.user_id','user_challange.text','user_challange.file','user_challange.file_type','challanged_friend.status','challanged_friend.id','challanged_friend.user_id as friend_id',
				'challanged_friend.created_at','challanged_friend.accepted_at','challanged_friend.completed_at')
				->where(['challanged_friend.user_id'=>$request->user_id])
				->where('challanged_friend.status', '!=' , 2)
				->orderByDesc('user_challange.created_at')
				->get(); 
			foreach($challangeList as $key=>$challange){
				
				$userInfoId = $this->getUserInfoByUserId($challange->user_id);
				$challangeList[$key]->name = $userInfoId->name;
				$challangeList[$key]->profile_image = $userInfoId->profile_picture;
				
				$challangeRequestFile = url('/').self::CHALLANGE_FULL_PATH . $challange->file;
				$challangeList[$key]->file = $challangeRequestFile;
				
				$userInfo = $this->getUserInfoByUserId($challange->friend_id);
				$challangeList[$key]->participant = array(
					'user_id'=>$challange->friend_id,
					'name'=>$userInfo->name,
					'profile_image'=>$userInfo->profile_picture,
					'file'=>$challangeRequestFile,
					'challange_status'=>$challange->status,
					'text'=>'',
					'completed_url'=>'',
					'created_at'=>$challange->created_at,
					'accepted_at'=>$challange->accepted_at,
					'completed_at' =>$challange->completed_at
				);
				unset($challangeList[$key]->status);
				unset($challangeList[$key]->friend_id);				
				unset($challangeList[$key]->created_at);				
				unset($challangeList[$key]->accepted_at);				
				unset($challangeList[$key]->completed_at);				
			} */
				
				
		if(!empty($challangeList)){
			return response(['STATUS'=>'true','message'=>'Challange Received List','response'=>$challangeList]);
		} else { 
			return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
		}
	}
	function getParticipantChallangeCompleteList($challangeId){
		$return = DB::table('challanged_friend')->where(['challange_id'=>$challangeId])->get();
		$chalaneInfo = $this->getChallangeInfo($challangeId);
		$arr = array();
		foreach($return as $key=>$value){
			$userInfoId = $this->getUserInfoByUserId($value->user_id);
			$comp = $this->getCompleteChalangeInfoById($value->user_id,$challangeId);
			//print_r($comp);echo $comp->text;die;
			$arr[] = array(
				"user_id"=>$value->user_id,
				"name"=>$userInfoId->name,
				"profile_image"=>$userInfoId->profile_picture,
				"file"=>$chalaneInfo->file,
				"challange_status"=>$value->status,
				
				"duration"=>$chalaneInfo->duration,
				"thumbnail"=>$chalaneInfo->thumbnail,
				
				"text"=>$comp->text,
				"completed_url"=>$comp->file,
				
				"completed_duration"=>$comp->duration,
				"completed_thumbnail"=>$comp->thumbnail,
				
				"created_at"=>$value->created_at,
				"accepted_at"=>$value->accepted_at,
				"completed_at"=>$value->completed_at
			); 
		}
		//print_r($arr);die;
		return $arr;
	}
	function getCompleteChalangeInfoById($userId,$challangeId){
		$count = DB::table('challange_complete')->where(['user_id'=>$userId,'challange_id'=>$challangeId])->count();
		if($count>0){
			$return = DB::table('challange_complete')->where(['user_id'=>$userId,'challange_id'=>$challangeId])->first();
			$File = url('/').self::CHALLANGE_FULL_PATH . $return->file;
			$return->file = $File;
			$thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH . $return->thumbnail;
			$return->thumbnail = $thumbnail;
		} else {
			$return = (object) array(
				"text"=>'',
				"file"=>'',
				"duration"=>'',
				"thumbnail"=>''
			);
		}
		//print_r($return);die;
		return $return; 
	}
	public function challangeRequestCompletedList(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[ 'user_id' => 'required|max:100' ]);
		$arr = array();
/* DB::enableQueryLog(); */

		/* $challangeList = DB::table('challanged_friend')->distinct()
							->where(['user_id'=>$request->user_id])
							->orWhere(['challanger_id'=>$request->user_id])
							->where(['status'=> 2])
							->orderBy('challange_id', 'desc')->get(['challange_id']); */	
							
		$challangeList = DB::table('challanged_friend')->distinct()
							->whereRaw('(user_id = "'.$request->user_id.'" OR challanger_id = "'.$request->user_id.'")')
							->where(['status'=> 2])->orderBy('challange_id', 'desc')->get(['challange_id']);
							
/* $query = DB::getQueryLog();
print_r($query); 
 
print_r($challangeList);die;*/
		if(!empty($challangeList)){
				foreach($challangeList as $key=>$challange){
					$chalaneInfo = $this->getChallangeInfo($challange->challange_id);
					$userInfoId = $this->getUserInfoByUserId($chalaneInfo->user_id);
					
					$participants = $this->getParticipantChallangeCompleteList($chalaneInfo->id);
					$arr[] = array(
						"challange_id"=>$chalaneInfo->id,
						"user_id"=>$chalaneInfo->user_id,
						"text"=>$chalaneInfo->text,
						"file"=>$chalaneInfo->file,
						"file_type"=>$chalaneInfo->file_type,
						
						"duration"=>$chalaneInfo->duration,
						"thumbnail"=>$chalaneInfo->thumbnail,
						
						"name"=>$userInfoId->name,
						"profile_image"=>$userInfoId->profile_picture,
						"participant"=>$participants
						
						//"completed_duration"=>$comp->duration,
						//"completed_thumbnail"=>$comp->thumbnail,
					);
				}
				
				return response(['STATUS'=>'true','message'=>'Challange List','response'=>$arr]);
			} else { 
				return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
			}
		/* $challangeList = DB::table('user_challange')
                ->join('challanged_friend', 'user_challange.id', '=', 'challanged_friend.challange_id')
                ->join('challange_complete', 'challanged_friend.challange_id', '=', 'challange_complete.challange_id')
				->select('user_challange.id','user_challange.user_id','user_challange.text','user_challange.file','user_challange.file_type','challanged_friend.status','challanged_friend.id','challanged_friend.user_id as friend_id',
				'challanged_friend.created_at','challanged_friend.accepted_at','challanged_friend.completed_at',
				'challange_complete.user_id as comp_uid', 'challange_complete.text as comp_text', 'challange_complete.file as comp_file'
				)
				->where(['challanged_friend.user_id'=>$request->user_id,'challanged_friend.status'=>2])
				->orWhere(['challanged_friend.challanger_id'=>$request->user_id,'challanged_friend.status'=>1])
				->orderByDesc('challanged_friend.completed_at')
				->get(); 
				
			foreach($challangeList as $key=>$challange){
				
				$userInfoId = $this->getUserInfoByUserId($challange->user_id);
				$challangeList[$key]->name = $userInfoId->name;
				$challangeList[$key]->profile_image = $userInfoId->profile_picture;
				
				$challangeRequestFile = url('/').self::CHALLANGE_FULL_PATH . $challange->file;
				$challangeList[$key]->file = $challangeRequestFile;
				
				$userInfo = $this->getUserInfoByUserId($challange->friend_id);
				$challange->comp_file = url('/').self::CHALLANGE_FULL_PATH . $challange->comp_file;
				$challangeList[$key]->participant = array(
					'user_id'=>$challange->friend_id,
					'name'=>$userInfo->name,
					'profile_image'=>$userInfo->profile_picture,
					'file'=>$challangeRequestFile,
					'challange_status'=>$challange->status,
					'completed_url'=>$challange->comp_file,
					'text'=>$challange->comp_text,
					'created_at'=>$challange->created_at,
					'accepted_at'=>$challange->accepted_at,
					'completed_at' =>$challange->completed_at
				);
				unset($challangeList[$key]->status);
				unset($challangeList[$key]->friend_id);				
				unset($challangeList[$key]->created_at);				
				unset($challangeList[$key]->accepted_at);				
				unset($challangeList[$key]->completed_at);				
				unset($challangeList[$key]->comp_uid);				
				unset($challangeList[$key]->comp_text);				
				unset($challangeList[$key]->comp_file);				
			}
		if(!empty($challangeList)){
			return response(['STATUS'=>'true','message'=>'Challange Completed List','response'=>$challangeList]);
		} else { 
			return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
		} */
	}
	
/* 
cUqKqfIML3I:APA91bHmBl51KLVT9PiAtt_J9LYl_eUAd3PlI5nqVHCMAHMmNAHTTxMiXUmS9Js9Fx5fkUvip0XRJoN5QmJfsKq9FQpct80RspK4HW13VMgaZluDh1J3VPmCmFsAuX7jyFsppj3-XJ0A
DB::enableQueryLog();
$query = DB::getQueryLog();
print_r($query);
die;  */

function get_device_token($userId){
	$userInfo = DB::table('users')
				->select('name','email','profile_picture','device_type','device_token')
				->where(['user_id'=>$userId])->first();
	$userImg = url('/').self::PROFILE_IMAGE_PATH . $userInfo->profile_picture;
	$userInfo->profile_picture = $userImg;
	return $userInfo;	
}

	public function push_test(){
		
		$tokenId = array('cUqKqfIML3I:APA91bHmBl51KLVT9PiAtt_J9LYl_eUAd3PlI5nqVHCMAHMmNAHTTxMiXUmS9Js9Fx5fkUvip0XRJoN5QmJfsKq9FQpct80RspK4HW13VMgaZluDh1J3VPmCmFsAuX7jyFsppj3-XJ0A','dM_4kBCzRvKdO0LpbDlcjl:APA91bGJzjsT1Fk6j-s4N0OueEIMBVMRzOaD5kz9pVneexZsCf90CyjU1G1sfg-vMdtEpCxtOYBQM-lARvA6fgKdSTNiyWuhEo7l5gU8vT8FCvyssTUTfzXANJ2if5K5ykSJ1cniyuzW','eZr_WWv3bIA:APA91bHS3WP4MDAduL-w-UTBseRqw153o81XKClWHiQmBPjDoTavYXArtn5Gx8Uk2p3eou2vbzWjIwNvnlh0NU3RdkG7YrwhCBM4zZza9QYM854PnAJ9VLO8fmgqoJeX4JkNFJxl171O');
		$notification = array(
			"title" => "Avnish",
			"body"  => "you have received a friend request.",
			"icon"  => "img",  
			"flag"  => 1,
		);
		$notificationData =  array (
									"title" => "Avnish",
									"body"  => "you have received a friend request.",
									"icon"  => "img",  
									"id"  => '1234',
								);
		
		$this->push_notification($tokenId,$notification,$notificationData);
	}
	public function push_notification($token_id,$notification,$notificationData){ 
	    $url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array (
			'registration_ids' => $token_id,
			'notification'     => $notification,
			'data'             => $notificationData
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
	/*  Push Notification For ( Subscription Expiring Reminder For GYM And Trainer ) */
	function subscriptionEnding(){
		$today = date('Y-m-d');
		$expDate = date('Y-m-d', strtotime($today . ' +1 day'));
		$array = array();
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','gym_id','trainer_id','subscription_for','start_date','months')
				->get();
		$array = array();
		foreach($orderInfo as $key=>$order){
			$months = '+'.$order->months.' months';
			$ENDATE = strtotime($months,strtotime($order->start_date));
			$endDate = date("Y-m-d",$ENDATE);
			$orderInfo[$key]->end_date = $endDate;
			if($endDate==$expDate){
				$array[] = array(
					'user_id'=>$order->user_id,
					'gym_id'=>$order->gym_id,
					'trainer_id'=>$order->trainer_id,
					'subscription_for'=>$order->subscription_for,
					'start_date'=>$order->start_date,
					'end_date'=>$endDate,
				);

				if($order->subscription_for=='TRAINER'){
					$userInfo = $this->getUserInfoByUserId($order->trainer_id);
					$deviceInfo = $this->get_device_token($order->user_id);
					$tokenId = array($deviceInfo->device_token);
					$pushNotification = array(
						"title" => $userInfo->name,
						"body"  => "Your subscription will expire tomorrow. To continue avail services, please resubscribe.",
						"icon"  => $userInfo->profile_picture,  
						"flag"  => 13,
					);
					$notificationData = array(
						"title" => $userInfo->name,
						"body"  => "Sent you a friend request.",
						"icon"  => $userInfo->profile_picture,  
						"flag"  => 13,
						"id"    => $order->trainer_id
					);
					$this->push_notification($tokenId,$pushNotification,$notificationData);
				}
				if($order->subscription_for=='GYM'){
					$gymInfo = $this->getGymInfo($order->gym_id);
					$deviceInfo = $this->get_device_token($order->user_id);
					$tokenId = array($deviceInfo->device_token);
					$pushNotification = array(
						"title" => $gymInfo->gym_title,
						"body"  => "Your subscription will expire tomorrow. To continue avail services, please resubscribe.",
						"icon"  => $gymInfo->cover_image,  
						"flag"  => 14,
					);
					$notificationData = array(
						"title" => $userInfo->name,
						"body"  => "Sent you a friend request.",
						"icon"  => $userInfo->profile_picture,  
						"flag"  => 14,
						"id"    => $order->gym_id
					);
					$this->push_notification($tokenId,$pushNotification,$notificationData);
				}
			}
		}
		//print_r($orderInfo);
		print_r($array);
		
	}
	
	function createThumbnail($videoFile,$thumbnailName){
		$ffprobe = FFMpeg\FFProbe::create();
		$duration = $ffprobe->streams($videoFile)  // extracts streams informations
					->videos()                     // filters video streams
					->first()                      // returns the first video stream
					->get('duration');
		$sec = $duration/2;	
		$ffmpeg = FFMpeg\FFMpeg::create();
		$video = $ffmpeg->open($videoFile);
		$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
		//$fileName = time().'thumbnail.png';
		$fileName = $thumbnailName;
		$save = $frame->save($fileName);		
		return array('duration'=>$duration,'thumbnail_name'=>$fileName);
	}
	

	public function addFirbaseId(Request $request){
		$firebaseId = DB::table('users')->where('user_id',$request->user_id)->update(['firebase_id'=>$request->firebase_id]);
		if($firebaseId){
			$fId = array('firebase_id'=>$request->firebase_id);
			return response(['STATUS'=>'true','message'=>'Firebase id Updated Sucessfully','response'=>$fId]);
		} else { 
			return response(['STATUS'=>'true','message'=>'Challange List is Empty.']);
		}
	}
	public function searchTrainer(Request $request){
		$user = auth()->user();
		$lat = $request->lat;		
		$lon = $request->lon;		
		$radius = $request->radius;	
		$gymCaregory = $request->gym_category;
		if($request->type=='GYM'){
			$res = $this->search_gym_near_by_users($lat,$lon,$radius,$gymCaregory);
			return response(['STATUS'=>'true','message'=>'User Ids','response'=>$res]);
		} else{
			$res = $this->search_trainer_near_by_users($lat,$lon,$radius);
			return response(['STATUS'=>'true','message'=>'User Ids','response'=>$res]);
		}
	}
	public function search_trainer_near_by_users($latitude,$longitude,$radius,$trainerCaregory) {
        $d = $radius;
		$r = 3959; //3959 if earth's radius in miles OR 6371 if radius in Km

		$latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(0))));

		$latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(180))));

		$lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));

		$lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));
/* DB::enableQueryLog(); */
		$result = DB::select( DB::raw("SELECT user_id, name, profile_picture, about,lat,lon FROM `users` WHERE lat <= $latN AND lat >= $latS AND lon <= $lonE AND lon >= $lonW AND active_status = 1 AND user_type = 3 AND profile_status = 1 AND trainer_category = $trainerCaregory"));
				
/* $query = DB::getQueryLog();print_r($query);die; */
		return $result;
	}
	
	public function search_gym_near_by_users($latitude,$longitude,$radius,$gymCaregory) {
     
		$d = $radius;
		$r = 3959; //3959 if earth's radius in miles OR 6371 if radius in Km

		$latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(0))));

		$latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(180))));

		$lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));

		$lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));
/* DB::enableQueryLog(); */
				$result = DB::select( DB::raw("SELECT user_id as gym_id,gym_title,cover_image,brief_description,lat,lon FROM `gym_users` WHERE lat <= $latN AND lat >= $latS AND lon <= $lonE AND lon >= $lonW AND active_status=1 AND gym_category = $gymCaregory"));
/* $query = DB::getQueryLog(); print_r($query); */

		return $result;
	}
	
	public function search_park_near_by_users($latitude,$longitude,$radius,$parkType1,$parkType2,$both=''){
     
		$d = $radius;
		$r = 3959; //3959 if earth's radius in miles OR 6371 if radius in Km

		$latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(0))));

		$latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
				+ cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(180))));

		$lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));

		$lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270))
				* sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
				- sin(deg2rad($latitude)) * sin(deg2rad($latN))));
/* DB::enableQueryLog(); */
if($both==''){
		$result = DB::select(DB::raw("SELECT id,park_name,park_type,lat,lon,park_image,park_address FROM parks WHERE lat <= $latN AND lat >= $latS AND lon <= $lonE AND lon >= $lonW AND status=1 AND (park_type = $parkType1 OR park_type= $parkType2)"));
} else {
		$result = DB::select(DB::raw("SELECT id,park_name,park_type,lat,lon,park_image,park_address FROM parks WHERE lat <= $latN AND lat >= $latS AND lon <= $lonE AND lon >= $lonW AND status=1 AND (park_type = $parkType1 OR park_type= $parkType2 OR park_type = $both)"));
}
/* $query = DB::getQueryLog();print_r($query); */
		return $result;
	}
	
	
	public function myOrderTrainer(Request $request){
		$orders = DB::table('subscription_payments')->select('transaction_id','subscription_for','membership_type')->where(['trainer_id'=>$request->user_id])->get();
		$orderDetails = array();
	/* 	if(!empty($orders)){
			foreach($orders as $key=>$order){
				$orderDetails[$key] = $this->orderInfoByTransactionIdAndOrderType($order->product_type,$order->transaction_id);
			}
			foreach($orderDetails as $k=>$orderInfo){
				unset($orderDetails[$k]->tracking_detail);
				unset($orderDetails[$k]->shipping_detail);
				unset($orderDetails[$k]->payment_detail);
			}
		} else{
			$orderDetails = array();
		} */
		$subscription = $this->subscriptionInfoByIdForTrainer($request->user_id);
		$orderAndSubscriptionDetails = array_merge($orderDetails,$subscription);
		/*  print_r($orderAndSubscriptionDetails);
		die;  */ 
		if(!empty($orderAndSubscriptionDetails)){
			return response(['STATUS'=>'true','message'=>'My Orders.','response'=>$orderAndSubscriptionDetails]);
		} else{
			return response(['STATUS'=>'true','message'=>'Empty order Lists','response'=>$orderAndSubscriptionDetails]);
		} 
	}
	
	function subscriptionInfoByIdForTrainer($userId){
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at')
				->where(['trainer_id'=>$userId])
				->get();
			$myorder = array();
			$myorderGym = array();
			$trainerSubscription = array();
			$gymSubscription = array();
			
/* print_r($orderInfo);
die; */
$count = 0;
			foreach($orderInfo as $key=>$order){
				if($order->subscription_for=='TRAINER'){
					$trainerInfo = $this->getTrainerInfo($order->user_id);
					$trainerInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$trainerInfo->profile_picture; 
					$subscriptionDetails = (object) array_merge((array) $order,(array) $trainerInfo);
					
					unset($subscriptionDetails->achievements);
					unset($subscriptionDetails->galary_image);
					unset($subscriptionDetails->videos);
					unset($subscriptionDetails->distance);
/* print_r($subscriptionDetails);
die; */
					$days = '+'.$subscriptionDetails->months.' days';
					$ENDATE = strtotime($days,strtotime($subscriptionDetails->start_date));
					$endDate = date("Y-m-d",$ENDATE);
					$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
				
					//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorder['order_id']        = $subscriptionDetails->order_id;
					$myorder['transaction_id']  = $subscriptionDetails->transaction_id;
					$myorder['order_status']    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
					
					$myorder['shipment_detail'] = (object) array(
						"id"                  =>$subscriptionDetails->trainer_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"product_category"    =>$subscriptionDetails->trainer_category,
						"product_title"       =>$subscriptionDetails->name, 
						"sub_title"           =>null,
						"brief_description"   =>$subscriptionDetails->about,
						"full_description"    =>$subscriptionDetails->about,
						"cover_image"         =>$subscriptionDetails->profile_picture, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$subscriptionDetails->currency, 
						//"subscription_amount"=>$subscriptionDetails->subscription_amount, 
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft,
						"per"                 =>null 
					);
					$trainerSubscription[$count++] = $myorder;
					 //print_r($myorder);
			
				} 
			}	

		return $trainerSubscription;
	}
	
	public function getSubscriptionInfoByTransactionIdForTrainer(Request $request){
		$subscriptionInfo = DB::table('subscription_payments')->select('*')->where(['transaction_id'=>$request->transaction_id])->first(); 
		$subscriptionDetails = $this->subscriptionInfoByTransactionIdAndSubscriptionForTrainerSide($subscriptionInfo->subscription_for,$request->transaction_id);
		if(!empty($subscriptionDetails)){
			return response(['STATUS'=>'true','message'=>'Subscription Info.','response'=>$subscriptionDetails]);
		} else{
			return response(['STATUS'=>'false','message'=>'Transaction Error']);
		} 
	}
	
	function subscriptionInfoByTransactionIdAndSubscriptionForTrainerSide($orderType,$transationId){
		$myorder =  (object) array();
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','payment_created_at')
				->where(['transaction_id'=>$transationId])
				->first();
		//print_r($orderInfo);die;
			/* $productInfo = $this->productdetailsByProductId($orderInfo->product_id);  */
				$userInfo = $this->getUserInfo($orderInfo->user_id);
				$trainerInfo = $this->getTrainerInfo($orderInfo->trainer_id);
	//print_r($userInfo);//die;
				//$userInfo->profile_picture = url('/').self::PROFILE_IMAGE_PATH.$trainerInfo->profile_picture; 
			    $subscriptionDetails = (object) array_merge((array) $orderInfo,(array) $userInfo);
		//print_r($subscriptionDetails);die;
				/* $months = '+'.$subscriptionDetails->months.' months'; */
				$days = '+'.$subscriptionDetails->months.' days';
				$ENDATE = strtotime($days,strtotime($subscriptionDetails->start_date));
				$endDate = date("Y-m-d",$ENDATE);
				$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
								
			//echo '<pre>';print_r($subscriptionDetails);die;
			        $myorder->order_id        = $subscriptionDetails->order_id;
					$myorder->transaction_id  = $subscriptionDetails->transaction_id;
					$myorder->order_status    = $subscriptionDetails->order_status==1?'Active':'Cancelled';
					$myorder->shipment_detail = (object) array(
						"id"                  =>$subscriptionDetails->user_id,
						"product_type"        =>$subscriptionDetails->subscription_for,
						"name"                =>$subscriptionDetails->name,
						"brief_description"   =>strip_tags($subscriptionDetails->about),
						"full_description"    =>strip_tags($subscriptionDetails->about),
						"cover_image"         =>$subscriptionDetails->profile_picture, 
						"amount"              =>$subscriptionDetails->total_amount_paid, 
						"currency"            =>$trainerInfo->currency, 
						"single_membership_rate"=>$trainerInfo->single_membership_rate,
						"group_membership_rate"=>$trainerInfo->group_membership_rate,
						"start_date"          =>$subscriptionDetails->start_date, 
						"months"              =>$subscriptionDetails->months, 
						"end_date"            =>$endDate, 
						"member_count"        =>$subscriptionDetails->member_count, 
						"preferred_time"      =>$subscriptionDetails->preferred_time,
						"membership_type"     =>$subscriptionDetails->membership_type, 
						"valid_upto"          =>$daysLeft,
						"gender"              =>$subscriptionDetails->gender,
						"height"              =>$subscriptionDetails->height,
						"weight"              =>$subscriptionDetails->weight,
						"about"               =>$subscriptionDetails->about,
						"address"             =>$subscriptionDetails->address
					);
					$myorder->payment_detail  = (object) array(
						'total_amount_paid'  => $subscriptionDetails->total_amount_paid,
						'payer_status'       => $subscriptionDetails->payer_status,
						'payment_method'     => $subscriptionDetails->payment_method,
						'payment_created_at' => $subscriptionDetails->payment_created_at,
						'payer_email'        => $subscriptionDetails->payer_email,
						'is_coupon_applied'  => $subscriptionDetails->coupon_id!=null?true:false,
						'coupon_id'          => $subscriptionDetails->coupon_id,
						'coupon_code'        => $subscriptionDetails->coupon_code,
						'membership_amount'  => $subscriptionDetails->membership_amount,
						'discounted_amount'  => $subscriptionDetails->discounted_amount
					);
			//print_r($myorder);die;
			return $myorder; 
	}
	
	public function addTrainerHomeService(Request $request){
		
		$validatedData =$this->validate($request,[
				'location_name'=>'required',
				'user_id'=>'required',
				'address'=>'required',
				'lat'=>'required',
				'lon'=>'required'
			]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');	
		$homeService = DB::table('home_service_trainer_info')->insertGetId($validatedData);
		if($homeService){
			$homeServiceInfo = DB::table('home_service_trainer_info')
				->select('*')
				->where(['user_id'=>$request->user_id])->get();
			return response(['STATUS'=>'true','message'=>'Home Service Added Successfully.','response'=>$homeServiceInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}		
	}
	public function removeTrainerHomeService(Request $request){
		$validatedData = $request->validate([
			'id'=>'required',			
			'user_id'=>'required',
        ]);
		$deleteRecord = DB::table('home_service_trainer_info')->where(['id'=>$request->id,'user_id'=>$request->user_id])->delete();
		if($deleteRecord){
			$homeServiceInfo = DB::table('home_service_trainer_info')
				->select('*')
				->where(['user_id'=>$request->user_id])->get();
			return response(['STATUS'=>'true','message'=>'Record Deteted Successfully.','response'=>$homeServiceInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	public function updateTrainerHomeService(Request $request){
		$validatedData = $request->validate([
				'id'=>'required',
				'location_name'=>'required',
				'user_id'=>'required',
				'address'=>'required',
				'lat'=>'required',
				'lon'=>'required'
        ]);
        
        $updateHomeService = DB::table('home_service_trainer_info')
            ->where('id',$request->id)
            ->update(['location_name'=>$request->location_name,'user_id'=>$request->user_id,'address'=>$request->address,'lat'=>$request->lat,'lon'=>$request->lon]);
		if($updateHomeService){
			$homeServiceInfo = DB::table('home_service_trainer_info')
				->select('*')
				->where(['user_id'=>$request->user_id,'id'=>$request->id])->get();
			return response(['STATUS'=>'true','message'=>'Home Service Updated Successfully.','response'=>$homeServiceInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	function getHomeServiceInfoById($userId){
		$homeServiceInfo = DB::table('home_service_trainer_info')->select('*')->where(['user_id'=>$userId])->get();
		return $homeServiceInfo;
	}
	/* Wallet Trainer */
	public function trainerWalletHistory(Request $request){
		$user = auth()->user();
		$validatedData = $request->validate([
			'user_id'=>'required'
        ]);	
		$subInfo = $this->subscriptionInfoByIdForTrainerForWallet($request->user_id,'HISTORY');
		if($subInfo){
			return response(['STATUS'=>'true','message'=>'Trainer Wallet History.','response'=>$subInfo->history]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	public function trainerWallet(Request $request){
		$user = auth()->user();
		$validatedData = $request->validate([
			'user_id'=>'required'
        ]);
		$wallateEndDate = date('Y-m-d');
//DB::enableQueryLog();
		$startDate = $this->get_wallet_start_date_from_payment_not_received($request->user_id);

//$query = DB::getQueryLog();print_r($query);//die;
		$count = DB::table('wallet_request')->where(['trainer_id'=>$request->user_id])->count();
		if($count>0){
			if($startDate->start_date=='' or $startDate->start_date==null){
				$last_row = DB::table('wallet_request')->where(['trainer_id'=>$request->user_id])->orderBy('id', 'DESC')->first();
				$walletStartDate = date('Y-m-d',strtotime($last_row->requested_date));
			} else{
				$walletStartDate = $startDate->start_date;
			}
		} else {
			$startDateFirstTime = date('Y-m-d', strtotime($user->created_at));
			$walletStartDate = $startDateFirstTime;
		}
		$subInfo = $this->subscriptionInfoByIdForTrainerForWallet($request->user_id);
	
		$walletInfo = array(
			'from_date'=>$walletStartDate,
			'to_date'  =>$wallateEndDate,
			'wallet_admin_received'   =>$subInfo->wallet_admin_received,
			'wallet_hundred_percent'   =>$subInfo->wallet_hundred_percent,
			'wallet_seventyfive_percent'   =>$subInfo->wallet_seventyfive_percent,
			'upcoming' =>$subInfo->upcoming,
			'history'  =>$subInfo->history
			
		);
		if($walletInfo){
			return response(['STATUS'=>'true','message'=>'Trainer Wallet.','response'=>$walletInfo]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	public function get_wallet_start_date_from_payment_not_received($trainerId){
		$result = DB::select(DB::raw("SELECT min(start_date) as start_date FROM subscription_payments WHERE trainer_id='".$trainerId."' AND subscription_for='TRAINER' AND payment_state='completed' AND order_status=1 AND wallet_status=0 ORDER BY created_at ASC"));	
		return $result[0];
	}

	function subscriptionInfoByIdForTrainerForWallet($userId,$type='NORMAL'){
		if($type=='NORMAL'){
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','wallet_status','payment_created_at', 'created_at as purchase_date')
				->where(['trainer_id'=>$userId,'subscription_for'=>'TRAINER','payment_state'=>'completed','order_status'=>1,'wallet_status'=>0])->get();
		} if($type=='HISTORY'){
		$orderInfo = DB::table('subscription_payments')
				->select('user_id','order_id','transaction_id','gym_id','trainer_id','subscription_for','membership_type','start_date',
				'preferred_time','months','member_count','total as total_amount_paid','payer_status','order_status','payment_method','payer_email','coupon_id','coupon_code','membership_amount','discounted_amount','purchase_status','wallet_status','payment_created_at', 'created_at as purchase_date')
				->where(['subscription_for'=>'TRAINER','payment_state'=>'completed','order_status'=>1,'wallet_status'=>1])
				->orWhere(['subscription_for'=>'TRAINER','payment_state'=>'completed','order_status'=>1,'wallet_status'=>2])
				->where(['trainer_id'=>$userId])
				->get();
		}
		
			$upcoming = array();
			$history  = array();
			$upcomingSubscription = array();
			$historySubscription  = array();
			$orderIdss  = array();

			$count = 0;
			$today = date('Y-m-d');
			$walletAdminReceived = 0;
			$walletAmouontHunderd = 0;
			$walletAmouontSeventyFive = 0;
			$walletDiscountedAmouont = 0;
			foreach($orderInfo as $key=>$order){
				if($order->subscription_for=='TRAINER'){
					$userInfo = $this->getUserInfoByUserId($order->user_id);
					$subscriptionDetails = (object) array_merge((array) $order,(array) $userInfo);

					$days = '+'.$subscriptionDetails->months.' days';
					$ENDATE = strtotime($days,strtotime($subscriptionDetails->start_date));
					$endDate = date("Y-m-d",$ENDATE);
					$daysLeft = $this->dateDiffInDays($subscriptionDetails->start_date, $endDate).' days'; 
					
					if($endDate>$today){
						$upcoming = (object) array(
							"start_date" =>$subscriptionDetails->start_date,
							"end_date"   =>$endDate,
							"days"       =>$daysLeft,
							"order_id"   =>$subscriptionDetails->order_id,
							"transaction_id"   =>$subscriptionDetails->transaction_id,
							"user_id"    =>$subscriptionDetails->user_id,
							"name"       =>$subscriptionDetails->name,
							"profile_pic"=>$subscriptionDetails->profile_picture,
							"purchase_date"=>$subscriptionDetails->purchase_date,
							"member_count"=>$subscriptionDetails->member_count,
							"total_amount_paid"=>$subscriptionDetails->total_amount_paid,
							"membership_amount"=>$subscriptionDetails->membership_amount,
							"discounted_amount"=>$subscriptionDetails->discounted_amount,
							"wallet_status"=>$subscriptionDetails->wallet_status
						);
						$upcomingSubscription[$count++] = $upcoming;
					} else {
						$history = (object) array(
							"start_date" =>$subscriptionDetails->start_date,
							"end_date"   =>$endDate,
							"days"       =>$daysLeft,
							"order_id"   =>$subscriptionDetails->order_id,
							"transaction_id"   =>$subscriptionDetails->transaction_id,
							"user_id"    =>$subscriptionDetails->user_id,
							"name"       =>$subscriptionDetails->name,
							"profile_pic"=>$subscriptionDetails->profile_picture,
							"purchase_date"=>$subscriptionDetails->purchase_date,
							"member_count"=>$subscriptionDetails->member_count,
							"total_amount_paid"=>$subscriptionDetails->total_amount_paid,
							"membership_amount"=>$subscriptionDetails->membership_amount,
							"discounted_amount"=>$subscriptionDetails->discounted_amount,
							"wallet_status"=>$subscriptionDetails->wallet_status
						);

						$walletAdminReceived =  $walletAdminReceived + $subscriptionDetails->total_amount_paid;
						$walletAmouontHunderd =  $walletAmouontHunderd + $subscriptionDetails->membership_amount;
						$walletDiscountedAmouont =  $walletDiscountedAmouont + $subscriptionDetails->discounted_amount;
						
						$historySubscription[$count++]  = $history;
						$orderIdss[] = $subscriptionDetails->order_id;
					}
				} 
			}
			$percentage = 75;
			$walletAmouontSeventyFive = ($percentage / 100) * $walletAmouontHunderd;
			$result = (object) array('upcoming'=>array_values($upcomingSubscription),'history'=>array_values($historySubscription),'wallet_admin_received'=>$walletAdminReceived,'wallet_hundred_percent'=>$walletAmouontHunderd,'wallet_seventyfive_percent'=>$walletAmouontSeventyFive,'wallet_discounted_amouont'=>$walletDiscountedAmouont,'history_order_id'=>$orderIdss);			
			return $result;
	}
	
	public function requestToAdmin(Request $request){
		$user = auth()->user();
		$validatedData = $request->validate([
			'user_id'=>'required',
			'start_date'=>'required',
			'end_date'=>'required'
        ]);
		$requestSentToAdmin = true;
		if($requestSentToAdmin){
			$upadtedate = date('Y-m-d h:i:s');
	//DB::enableQueryLog();
			$subInfo = $this->subscriptionInfoByIdForTrainerForWallet($request->user_id);
	//$query = DB::getQueryLog();print_r($query);die;	
			$updateWallet = DB::table('subscription_payments')
							->whereIn('order_id',$subInfo->history_order_id)
							->update(['wallet_status'=>1,'wallet_admin_request_date'=>$upadtedate]);
			if($updateWallet){
				$admintable = array(
							'trainer_id'=>$request->user_id,
							'requested_date'=>$upadtedate,
							'start_date'=>$request->start_date,
							'end_date'=>$request->end_date,
							'requested_amount'=>$subInfo->wallet_seventyfive_percent,
							'total_amount'=>$subInfo->wallet_hundred_percent,
							'discount_amount'=>$subInfo->wallet_discounted_amouont,
							'crerated_at'=>$upadtedate
						);
				$walletRequestedTable = DB::table('wallet_request')->insertGetId($admintable);
			}
			$arr = array('start_date'=>date('Y-m-d'),'end_date'=>date('Y-m-d'));
			return response(['STATUS'=>'true','message'=>'Request Sent To Admin Sucessfully.','response'=>$arr]);
		} else{
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	
	public function feedBack(Request $request){
		$user = auth()->user();
		$validatedData = $request->validate([
			'user_id'=>'required',
			'order_id'=>'required',
			'type'=>'required',
			'gym_id'=>'',
			'trainer_id'=>'',
			'star'=>'required',
			'feedback'=>'required'
        ]);
		$validatedData['created_at'] = date('Y-m-d h:i:s');
		$count = DB::table('subscription_feedback')->where(['user_id'=>$request->user_id,'order_id'=>$request->order_id])->count();
		if($count>0){
			return response(['STATUS'=>'true','message'=>'You have already submited the feedback.']);
		} else {
			$feedback = DB::table('subscription_feedback')->insertGetId($validatedData);
			if($feedback){
				return response(['STATUS'=>'true','message'=>'Thank your for your feedback.']);
			} else {
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}	
		}
	}
	public function addTrainerPaypalId(Request $request){
		$updatePaypalInfo = DB::table('users')->where(['user_id'=>$request->user_id])->update(['paypal_id'=>$request->paypal_id]);
		if($updatePaypalInfo){
			return response(['STATUS'=>'true','message'=>'Your paypal id updated sucessfully.']);
		} else {
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}	
	}
	/* Calculate distance between to coordinates */
	function distance($lat1, $lon1, $lat2, $lon2) {
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		} else {
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			return round($miles, 2);
		}
	}
	function trainerCountInPark($parkId){
		$count = DB::table('trainer_park')->where(['park_id'=>$parkId])->count();
		return $count;
	}	
	function trainerListByParkId($parkId){
		$trainers = DB::table('trainer_park')
		   ->join('users', 'trainer_park.user_id', '=', 'users.user_id')
		->select('trainer_park.user_id','trainer_park.park_id','users.name','users.profile_picture')->where(['park_id'=>$parkId])->get();
		foreach($trainers as $key=>$trainer){
			$trainers[$key]->rating = $this->trainerRatingById($trainer->user_id);
			$trainers[$key]->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $trainer->profile_picture;
		}
		return $trainers;
	}
	function userCountInPark($parkId){
		$count = DB::table('user_park')->where(['park_id'=>$parkId])->count();
		return $count;
	}	
	function galaryParkCount($parkId){
		$count = DB::table('park_experience')->where(['park_id'=>$parkId])->count();
		return $count;
	}
	function userListByParkId($parkId){
		$users = DB::table('user_park')
		   ->join('users', 'user_park.user_id', '=', 'users.user_id')
		->select('user_park.user_id','user_park.park_id','users.name','users.profile_picture')->where(['park_id'=>$parkId])->get();
		foreach($users as $key=>$user){
			$users[$key]->profile_picture = url('/').self::PROFILE_IMAGE_PATH . $user->profile_picture;
		}
		return $users;
	}
	function trainerRatingById($trainerId){
		$rating = 4.5;
		return $rating;
	}
	function trainerParkList(Request $request){
		$parkType1 = 2;// park Link
		$parkType2 = 3;//Both
		$parks = $this->search_park_near_by_users($request->lat,$request->lon,$request->radius,$parkType1,$parkType2);
		if(!empty($parks)){
			foreach($parks as $ky=>$park){
				$parks[$ky]->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
				$parks[$ky]->park_id = $park->id;
				$parks[$ky]->total_trainers = $this->trainerCountInPark($park->id);
				$parks[$ky]->total_users = $this->userCountInPark($park->id); 
				$parks[$ky]->distance = $this->distance($request->lat,$request->lon,$park->lat,$park->lon);
			}
			return response(['STATUS'=>'true','message'=>'Park Lists.','response'=>$parks]);
        } else {
			  return response(['STATUS'=>'false','message'=>'Empty Park List.']);
		}
		
		
	}
	
	function trainerParkDetails(Request $request){
		$user = auth()->user();
		$park = DB::table('parks')->where(['status'=>1,'id'=>$request->park_id])
				->select('id','park_name','park_type','lat','lon','park_image','park_address','about_park')
				->first();	
        if($park){
			$park->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
			$park->total_trainers = $this->trainerCountInPark($park->id);
			$park->total_users = $this->userCountInPark($park->id); 
			$park->distance = $this->distance($request->lat,$request->lon,$park->lat,$park->lon);
			$park->galary_count = $this->galaryParkCount($park->id);
			$park->going_in_status = $this->parkGoingStatus($user->user_id,$park->id);;
			$park->trainer_list = $this->trainerListByParkId($park->id);
			$park->user_list = $this->userListByParkId($park->id);
			return response(['STATUS'=>'true','message'=>'Park Detail.','response'=>$park]);
        } else {
			return response(['STATUS'=>'false','message'=>'Park Info Not Found.']);
		}
	}
	function parkGoingStatus($userId,$parkId){
		$count = DB::table('user_park')->where(['user_id'=>$userId,'park_id'=>$parkId,'status'=>1])->count();
		return $count;
	}
	function parkGoingIn(Request $request){
		$validatedData = $request->validate([
				'user_id'=>'required',
				'park_id'=>'required'
			]);
 		$validatedData['created_at'] = date('Y-m-d h:i:s');	
		$count = DB::table('user_park')->where(['user_id'=>$request->user_id,'park_id'=>$request->park_id])->count();
		if($count>0){
			$status = DB::table('user_park')->select('status')->where(['user_id'=>$request->user_id,'park_id'=>$request->park_id])->first();
			if($status->status==0){
				$status = 1;
				$updateStatus = DB::table('user_park')->where(['user_id'=>$request->user_id,'park_id'=>$request->park_id])
				->update(['status'=>$status]);
				$message = 'Thanks for joining park. We appreciate your presence for daily fitness activity.';
			} else {
				$status = 0;
				$updateStatus = DB::table('user_park')->where(['user_id'=>$request->user_id,'park_id'=>$request->park_id])
				->update(['status'=>$status]);
				$message = 'Your journey with this park ends now, You will no longer share your experience with this park.';
			}
			return response(['STATUS'=>'true','message'=>$message,'response'=>$status]);
		} else {
			$id = DB::table('user_park')->insertGetId($validatedData);
			if($id){
				return response(['STATUS'=>'true','message'=>'Thanks for joining park. We appreciate your presence for daily fitness activity.']);
			} else{
				return response(['STATUS'=>'false','message'=>'Network Error']);
			}	 
		}
	}

	public function shareYourExperience(Request $request){
		$user = auth()->user();	
		$validatedData = $this->validate($request,[
				'user_id'   => 'required',
				'park_id'   => 'required',
				'text'      => 'required',
				'file'      => 'required',
				'file_type' => 'required', 
			]);
			$validatedData['created_at'] = date('Y-m-d h:i:s');
			$parkImage = $request->file('file');
			if(!empty($parkImage)){
				$validatedData['file'] = time().$user->user_id.'.'.$parkImage->getClientOriginalExtension();
				$destinationPath = public_path('/assets/park_experience');
				$parkImage->move($destinationPath, $validatedData['file']);
				/* Start Create Thumbnail */
				if($request->file_type=='VIDEO'){
					$file = url('/').self::PARK_EXPERIENCE_FULL_PATH . $validatedData['file'];
					$thumbnailName = time().$user->user_id.'thumbnail.png';
					$thumbnail = $this->createThumbnail($file,$thumbnailName);
					$duration  = $thumbnail['duration'];
					$thumbnail = $thumbnail['thumbnail_name'];
				} else {
					$duration  = '';
					$thumbnail = '';
				}
				$validatedData['duration'] = $duration;
				$validatedData['thumbnail'] = $thumbnail;
				/* End Create Thumbnail */
			}
			$id = DB::table('park_experience')->insertGetId($validatedData);
		if($id){
			return response(['STATUS'=>'true','message'=>'Your experience added sucessfully.']);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'Network Error']);
		}
	}
	function parkGallery(Request $request){
		$parks = DB::table('park_experience')
		   ->join('parks', 'park_experience.park_id', '=', 'parks.id')
			->select('park_experience.user_id','park_experience.park_id','park_experience.text','park_experience.file','park_experience.file_type','park_experience.duration','park_experience.thumbnail','parks.park_name')
			->where(['park_id'=>$request->park_id])->get();
		foreach($parks as $key=>$val){
			$parks[$key]->file = url('/').self::PARK_EXPERIENCE_FULL_PATH . $val->file;
			$parks[$key]->thumbnail = url('/').self::PARK_GALARY_PATH . $val->thumbnail;			
		}
		if(!empty($parks)){
			return response(['STATUS'=>'true','message'=>'Galary Images.','response'=>$parks]);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'Galary Images Empty.']);
		}
	}
	
	function userVisitedParkList($userId){
		
		$userParkList = DB::table('user_park')
		   ->join('parks', 'user_park.park_id', '=', 'parks.id')
			->select('user_park.user_id','user_park.park_id','parks.park_name','parks.park_type','parks.lat','parks.lon','parks.park_image','parks.park_address')
			->where(['user_park.user_id'=>$userId])->get();
		foreach($userParkList as $key=>$park){
			$park->park_image = url('/').self::PARK_IMAGE_FULL_PATH.$park->park_image;
			$park->total_trainers = $this->trainerCountInPark($park->park_id);
			$park->total_users = $this->userCountInPark($park->park_id); 
			$park->distance = 0;
			$park->galary_count = $this->galaryParkCount($park->park_id);
		}
		return $userParkList;
	}
	function getUserWallList($userId){
		$wallList = DB::table('user_wall')
                ->select('id','user_id','text','file','file_type','duration','thumbnail','created_at')
				->where(['status'=>1,'user_id'=>$userId])
				->orderBy('created_at', 'desc')->get(); 
				
		foreach($wallList as $key=>$val){
			$wallList[$key]->file = url('/').self::WALL_FULL_PATH.$val->file;
			$wallList[$key]->thumbnail = url('/').self::WALL_THUMBNAIL_PATH.$val->thumbnail;
		}
		return $wallList;
	}	
	function getUserChallangeList($userId){
		$challangeList = DB::table('user_challange')
                ->select('id','user_id','text','file','file_type','duration','thumbnail','created_at')
				->where(['status'=>1,'user_id'=>$userId])
				->orderBy('created_at', 'desc')->get(); 
		foreach($challangeList as $key=>$val){
			$challangeList[$key]->file = url('/').self::CHALLANGE_FULL_PATH.$val->file;
			$challangeList[$key]->thumbnail = url('/').self::CHALLANGE_THUMBNAIL_PATH.$val->thumbnail;
		}
		return $challangeList;
	}	
	function getUserParkExperienceList($userId){
		$parkExperienceList = DB::table('park_experience')
                ->select('id','user_id','text','file','file_type','duration','thumbnail','created_at')
				->where(['status'=>1,'user_id'=>$userId])
				->orderBy('created_at', 'desc')->get(); 
		foreach($parkExperienceList as $key=>$val){
			$parkExperienceList[$key]->file = url('/').self::PARK_EXPERIENCE_FULL_PATH.$val->file;
			$parkExperienceList[$key]->thumbnail = url('/').self::PARK_GALARY_PATH.$val->thumbnail;
		} 
		return $parkExperienceList;
	}
	function userCompleteProfile(Request $request){
		$userInfo = $this->getUserInfo($request->user_id);
		$userParkInfo = $this->userVisitedParkList($request->user_id);
		$userInfo->park_info = $userParkInfo;
		$userInfo->subscribed_gym = $this->subscribesGymByUserId($request->user_id);
		
		$walList      = $this->getUserWallList($request->user_id);
		$chalangeList = $this->getUserChallangeList($request->user_id);
		$parkExpList  = $this->getUserParkExperienceList($request->user_id);
		$userInfoGalary = array_merge_recursive((array)$walList,(array)$chalangeList,(array)$parkExpList);
		foreach($userInfoGalary as $ke=>$newval){
			$narr[] = $userInfoGalary[$ke];
		}
		$userInfo->gallery = $narr[0];
		
		if(!empty($userInfo)){
			return response(['STATUS'=>'true','message'=>'User Info.','response'=>$userInfo]);  
		} else{ 
			return response(['STATUS'=>'false','message'=>'User Info Empty.']);
		}
	}
	/* DB::enableQueryLog();$query = DB::getQueryLog();print_r($query);die;	 */
}
	