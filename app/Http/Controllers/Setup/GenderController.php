<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setup\GenderResource;
use App\Models\Setup\Gender;

class GenderController extends Controller
{
  
    public function index()
    {
        return GenderResource::collection(Gender::all());
    }
    
}
