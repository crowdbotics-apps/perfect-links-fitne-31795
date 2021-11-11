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

class Temp_payment extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','order_id','product_type','product_id','amount','quantity','shipping_address_id','created_at'
    ];
}
