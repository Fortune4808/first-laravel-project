<?php

namespace App\Models\User;

use App\Models\Setup\Gender;
use App\Models\Setup\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $guard_name = 'users';
    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'userId', 'firstName', 'middleName', 'lastName',
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
