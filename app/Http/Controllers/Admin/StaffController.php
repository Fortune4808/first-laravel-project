<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Staff;
use Illuminate\Http\Request;
use App\Models\Setup\MasterCount;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\StaffResource;

class StaffController extends Controller
{

    public function index()
    {
        $user = Auth::guard('admin')->user();
        $roleId = $user->roles->first()?->id;
        
        if ($roleId==1){ $staff = Staff::with(['gender:id,genderName', 'status:id,statusName'])->where('staffId', '!=', $user->staffId)->paginate(50); }
        if ($roleId > 1){ $staff = Staff::with(['gender:id,genderName', 'status:id,statusName'])
            ->where('staffId', '!=', $user->staffId)
            ->whereHas('roles', function ($query) use ($roleId) { $query->where('id', '>=', $roleId); })->paginate(50); }

        if ($staff->isEmpty()) { return response()->json(['status' => false,'message' => 'No record found!'], 200); }

        return response()->json([
            'success' => true,
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
            'success' => true,
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
            'statusId' => 'required|exists:setup_status,id',
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
            'password' => Hash::make($staffId),
            'passport' => 'avatar.jpg'
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

    public function uploadPassport(Request $request)
    {
        $request->validate([
            'passport' => 'required|image|max:30'
        ]);

        $staff = auth('admin')->user();

        if ($staff->passport && Storage::exists('public/passports/admin/' . $staff->passport)) {
            Storage::delete('public/passports/admin/' . $staff->passport);
        }

        $file = $request->file('passport');
        $filename = $staff->staffId . '.' . $file->getClientOriginalName();
        $file->storeAs('public/passports/admin', $filename);

        $staff->passport = $filename;
        $staff->save();

        return response()->json([
            'success' => true,
            'message' => 'Passport uploaded successfully',
            'passportUrl' => asset('storage/passports/admin/' . $filename),
        ]);

    }
}
