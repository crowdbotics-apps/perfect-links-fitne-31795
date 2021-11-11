<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['string', 'min:8', 'confirmed'],
            'social_id'=>'',
            'user_type'=> ['required', 'string', 'min:8'],
            'profile_picture' => ['required', 'string'],
            'country_code'=>['required', 'string', 'max:6'],
            'phone_number'=>['required', 'string', 'max:14', 'unique:users'],
            'gender'=>['required', 'string', 'max:6'],
            'dob'=>['required', 'string', 'max:20'],
            'height'=>['required', 'string', 'max:20'],
            'weight'=>['required', 'string', 'max:20'],
            'about'=>'',
            'address'=>'',
            'lat'=>'',
            'lon'=>'',
            'device_type'=>['required', 'string'],
            'device_token'=>['required', 'string']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'social_id'=>$data['social_id'],
            'profile_picture' => $data['profile_picture'],
            'user_type'=> $data['user_type'],
            'country_code'=> $data['country_code'],
            'phone_number'=> $data['phone_number'],
            'gender'=> $data['gender'],
            'dob'=> $data['dob'],
            'height'=> $data['height'],
            'weight'=> $data['weight'],
            'device_type'=> $data['device_type'],
            'device_token'=> $data['device_token'],
        ]);
    }

    /**
     * update a user instance after a valid login.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function update(array $data)
    {
        return User::update([
            'device_type'=> $data['device_type'],
            'device_token'=> $data['device_token']
        ]);
    }
}
