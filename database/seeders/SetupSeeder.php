<?php

namespace Database\Seeders;

use App\Models\Setup\Gender;
use App\Models\Setup\MasterCount;
use App\Models\Setup\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
{

    public function run(): void
    {
        MasterCount::firstOrCreate(
            ['countId' => 'STF'],
            [
                'countValue' => 0,
                'countDesc' => 'COUNT NUMBER OF STAFF'
            ]
        );

        MasterCount::firstOrCreate(
            ['countId' => 'CUS'],
            [
                'countValue' => 0,
                'countDesc' => 'COUNT NUMBER OF USERS'
            ]
        );


        Gender::firstOrCreate(['genderName' => 'MALE']);
        Gender::firstOrCreate(['genderName' => 'FEMALE']);

        Status::firstOrCreate(['statusName' => 'ACTIVE']);
        Status::firstOrCreate(['statusName' => 'INACTIVE']);
    }
}
