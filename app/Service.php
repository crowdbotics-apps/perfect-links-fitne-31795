<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Service extends Authenticatable
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
        'service_image','service_name','about_service'
    ];

/* 
	
	public function isAdmin()    {        
		return $this->type === self::ADMIN_TYPE;    
	} */
}
