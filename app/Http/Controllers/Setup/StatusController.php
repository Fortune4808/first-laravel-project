<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setup\StatusResource;
use App\Models\Setup\Status;

class StatusController extends Controller
{

    public function index()
    {
        return StatusResource::collection(Status::all());
    }
}
