<?php

namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
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

        $data = $request->all();
        $data['firstName'] = strtoupper($data['firstName']);
        $data['middleName'] = strtoupper($data['middleName']);
        $data['lastName'] = strtoupper($data['lastName']);
        $users->update($data);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ], 200);
    }
}
