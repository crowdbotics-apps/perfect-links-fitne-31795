<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use DB;
class LoginController extends Controller
{
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
		if($user->role=='SUBADMIN'){ 
			return redirect('admin/add_gym');
			exit;
		} else {
			$pagesName = $this->leftBarPageName();
			$registered_gym = DB::table('gym_users')->count();
			$registered_user = DB::table('users')->where(['user_type'=>2])->count();
			$registered_trainer = DB::table('users')->where(['user_type'=>3])->count();
			$sold_product = DB::table('payments')->where(['product_type'=>'PRODUCT'])->count();
			$sold_accessories = DB::table('payments')->where(['product_type'=>'ACCESSORIES'])->count();
			return view('admin/dashboard',['user'=>$user,
				'pagesName'=>$pagesName,
				'registered_gym'=>$registered_gym,
				'registered_user'=>$registered_user,
				'registered_trainer'=>$registered_trainer,
				'sold_accessories'=>$sold_accessories,
				'sold_product'=>$sold_product
				]);
		}
    }
	public function showLoginForm(Request $request){
		return view('auth/login');
	}
	function leftBarPageName(){
		return DB::table('pages')->get();
	} 
	/* function leftBarPageName(){
		
		return array('page_name'=>'avnish');
	} */
}
