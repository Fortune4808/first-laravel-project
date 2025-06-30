<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
  
    public function index()
    {
        $users = User::with(['gender', 'status'])->paginate(50); 

        if ($users->isEmpty()) {return response()->json(['status' => false,'message' => 'No record found!'], 200);}

        return response()->json([
            'success' => true,
            'message' => 'User fetched successfully',
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'total' => $users->total(),
                'perPage' => $users->perPage(),
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
                'nextPageUrl' => $users->nextPageUrl(),
                'prevPageUrl' => $users->previousPageUrl(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
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
