<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $table = 'slot';
    protected $guard_name = 'admin';
    protected $fillable = [
        'locationId', 'slotName', 'statusId', 'createdBy'
    ];
}
