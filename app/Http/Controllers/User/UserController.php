<?php

namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Setup\MasterCount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'emailAddress' => 'required|email|unique:users,emailAddress',
            'mobileNumber' => 'required|string|unique:users,mobileNumber',
            'genderId' => 'required|exists:setup_gender,id',
            'statusId' => 'required|exists:setup_status,id'
        ]);

        $userId = MasterCount::generateCustomId('CUS');
        $users = User::create([
            'userId' => $userId,
            'firstName' => (strtoupper($request->firstName)),
            'middleName' => (strtoupper($request->middleName)),
            'lastName' => (strtoupper($request->lastName)),
            'emailAddress' => (strtolower($request->emailAddress)),
            'mobileNumber' => $request->mobileNumber,
            'genderId' => $request->genderId,
            'statusId' => $request->statusId,
            'password' => Hash::make($userId)
        ]);

        if ($users){
            return response()->json([
            'success' => true,
            'message' => 'User registration successful.',
            ], 201);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Registration failed.'
        ], 500);
    }

    public function show(string $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $users = User::findOrFail($id);
        $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'emailAddress' => 'required|email|unique:users,emailAddress,'.$id .',userId',
            'mobileNumber' => 'required|string|unique:users,mobileNumber,'.$id.',userId',
            'genderId' => 'required|exists:setup_gender,id',
            'statusId' => 'required|exists:setup_status,id'
        ]);

         $users->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ], 200);
    }
}
