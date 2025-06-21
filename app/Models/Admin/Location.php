<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';
    protected $guard_name = 'admin';
    protected $fillable = [
        'locationName', 'createdBy'
    ];
}
