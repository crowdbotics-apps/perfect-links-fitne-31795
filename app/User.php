<?php
namespace App;
use App\Notifications\PasswordResetNotification;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/*
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//Hasapitoken is imported for authtoken
use Laravel\Passport\HasApiTokens;
 */

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','name', 'email', 'user_name', 'email_verified_at', 'password','social_id','profile_picture','user_type','role', 'country_code','phone_number','gender','dob','height','weight','about','address','lat','lon','device_type','device_token','provider','created_at','updated_at','otp','otp_status','trainer_category','currency','preffered_date_time','status','active_status','profile_status','currency','group_membership_rate','group_membership_rate','paypal_id'
    ];
 
       /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}
