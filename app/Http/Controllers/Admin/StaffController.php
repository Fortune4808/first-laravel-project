<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Staff;
use Illuminate\Http\Request;
use App\Models\Setup\MasterCount;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\StaffResource;

class StaffController extends Controller
{

    public function index()
    {
        $currentStaffId = auth('admin')->id();
        $staff = Staff::with(['gender', 'status'])->where('staffId', '!=', $currentStaffId)->paginate(50); 

        if ($staff->isEmpty()) {return response()->json(['status' => false,'message' => 'No record found!'], 404);}

        return response()->json([
            'status' => true,
            'message' => 'Staff fetched successfully',
            'data' => StaffResource::collection($staff->items()),
            'pagination' => [
                'total' => $staff->total(),
                'perPage' => $staff->perPage(),
                'currentPage' => $staff->currentPage(),
                'lastPage' => $staff->lastPage(),
                'nextPageUrl' => $staff->nextPageUrl(),
                'prevPageUrl' => $staff->previousPageUrl(),
                'from' => $staff->firstItem(),
                'to' => $staff->lastItem(),
            ]
        ]);
    }

    
    public function show(string $id)
    {
        return new StaffResource(Staff::findOrFail($id));
    }


    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);
        $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'emailAddress' => 'required|email|unique:staff,emailAddress,'. $id . ',staffId',
            'mobileNumber' => 'required|string|unique:staff,mobileNumber,'. $id . ',staffId',
            'genderId' => 'required|integer',
            'roleId' => 'required|exists:roles,id',
            'statusId' => 'required|integer'
        ]);

        $data = $request->all();
        $data['firstName'] = strtoupper($data['firstName']);
        $data['middleName'] = strtoupper($data['middleName']);
        $data['lastName'] = strtoupper($data['lastName']);
        
        $staff->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Staff updated successfully'
        ], 200);
    }


    public function store(Request $request): JsonResponse 
    {
        $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'emailAddress' => 'required|email|unique:staff,emailAddress',
            'mobileNumber' => 'required|string|unique:staff,mobileNumber',
            'genderId' => 'required|exists:setup_gender,id',
            'roleId' => 'required|exists:roles,id',
            'statusId' => 'required|exists:setup_status,id'
        ]);

        $staffId = MasterCount::generateCustomId('STF');
        $staff = Staff::create([
            'staffId' => $staffId,
            'firstName' => (strtoupper($request->firstName)),
            'middleName' => (strtoupper($request->middleName)),
            'lastName' => (strtoupper($request->lastName)),
            'emailAddress' => (strtolower($request->emailAddress)),
            'mobileNumber' => $request->mobileNumber,
            'genderId' => $request->genderId,
            'statusId' => $request->statusId,
            'password' => Hash::make($staffId)
        ]);

        $role = Role::findById($request->roleId, 'admin');
        $staff->assignRole($role);

        if ($staff){
            return response()->json([
            'success' => true,
            'message' => 'Staff registration successful.',
            ], 201);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Registration failed.'
        ], 500);
    }
}
