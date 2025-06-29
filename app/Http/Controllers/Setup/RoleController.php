<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{

    public function index()
    {
        $user = Auth::guard('admin')->user();
        $roleId = $user->roles->first()?->id;

        if ($roleId == 1) {
            $roles = Role::all();
        } else {
            $roles = Role::where('id', '>=', $roleId)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Roles fetched successfully',
            'data' => $roles,
        ]);

    }
}
