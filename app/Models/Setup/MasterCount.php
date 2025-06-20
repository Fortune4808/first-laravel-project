<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCount extends Model
{
    use HasFactory;

    protected $table = 'setup_mast_count';
    protected $primaryKey = 'countId';
    public $incrementing = false;
    protected $keyType = 'string';

    public static function generateCustomId($countId)
    {
        $counter = self::where('countId', $countId)->first();
        if ($counter) {
            $counter->increment('countValue');
            return $countId . $counter->countValue . date('YmdHis');
        }
    }
}
