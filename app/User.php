<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;

use App\Dictionary;

use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens,HasRoles;
    //protected  $table="users";

    protected $guard_name = 'api';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

   // protected  $table="users";

       

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

   

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }


    public function dictionaries()
    {
        return $this->hasMany(Dictionary::class);
    }
}
