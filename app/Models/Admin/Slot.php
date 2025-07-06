<?php

namespace App\Models\Admin;

use App\Models\Setup\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;

    protected $table = 'slot';
    protected $guard_name = 'admin';
    protected $fillable = [
        'locationId', 'slotName', 'statusId', 'createdBy'
    ];

      public function locations()
    {
        return $this->belongsTo(Location::class, 'locationId');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'statusId');
    }

}
