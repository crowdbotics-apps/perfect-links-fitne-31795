<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Park extends Authenticatable
{
    use Notifiable;
	const ADMIN_TYPE = 'admin';
	const DEFAULT_TYPE = 'default';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'park_name','park_type','lat','lon','park_image','park_address','created_at','updated_at'
    ];
}
