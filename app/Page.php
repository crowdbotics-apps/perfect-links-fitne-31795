<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Page extends Authenticatable
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
        'id','title','brief_description','full_description','image','page_name'
    ];

/* 
	
	public function isAdmin()    {        
		return $this->type === self::ADMIN_TYPE;    
	} */
}
