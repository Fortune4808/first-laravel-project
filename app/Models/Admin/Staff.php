<?php

namespace App\Models\Admin;

use App\Models\Setup\Gender;
use App\Models\Setup\Status;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory, HasRoles, HasApiTokens;

    protected $guard_name = 'admin';
    protected $primaryKey = 'staffId';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'staffId', 'firstName', 'middleName', 'lastName',
        'mobileNumber', 'emailAddress', 'genderId', 'statusId', 'password',
        'passport'
    ];

    protected $hidden = ['password'];

     protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'genderId');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'statusId');
    }
}

