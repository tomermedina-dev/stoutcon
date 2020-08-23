<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'date_of_birth',
        'address',
        'mobile_number',
        'employee_identification',
        'position',
        'department',
        'status',
        'project',
        'location',
        'start_date',
        'end_date',
        'work_status',
        'benefits',
        'other_benefits',
        'rate_per_hour',
        'night_differencial',
        'salary_amount',
        'sss_contribution',
        'philhealth',
        'pag_ibig',
        'tax_withheld',
        // 'employee_status',
        'biometric_id',
        'role',
        'email_verified_at',
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
}
