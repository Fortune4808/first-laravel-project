<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse 
    {
        $request->validate([
            'emailAddress' => 'required|email',
            'password' => 'required'
        ]);

        $user = Staff::where('emailAddress', $request->emailAddress)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The credentials provided are invalid.'
            ], 401);
        }

        if ($user->statusId==2){
            return response()->json([
                'success' => false,
                'message' => 'Staff account has been suspended!'
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token
        ], 200);
    }
    

    
}
