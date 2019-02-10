<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'mobile', 'password', 'email', 'lastactivity', 'token', 'is_logged_out', 'is_sms_verified', 'last_sent_code', 'passcode', 'unique_id', 'is_activated', 'gender', 'last_fpass', 'is_fpass_enabled', 'is_confirm_sms_enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
