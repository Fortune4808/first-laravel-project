<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'password_reset_otp';
    protected $primaryKey = 'userId';
    protected $fillable = ['userId', 'otp', 'expiry_at'];
}
