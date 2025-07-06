<?php

namespace App\Http\Controllers\User\Auth;
use Carbon\Carbon;
use App\Models\Setup\Otp;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Setup\MasterCount;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse 
    {
        $request->validate([
            'emailAddress' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('emailAddress', $request->emailAddress)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The credentials provided are invalid.'
            ], 401);
        }

        if ($user->statusId==2){
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended!'
            ], 403);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'data' => new UserResource($user)
        ], 200);
    }


    public function register(Request $request):JsonResponse
    {
        $request->validate([
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'emailAddress' => 'required|email|unique:users,emailAddress',
            'mobileNumber' => 'required|string|unique:users,mobileNumber',
            'genderId' => 'required|exists:setup_gender,id'
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
            'statusId' => 1,
            'passport' => 'avatar.jpg',
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
    

     public function resetPassword(Request $request): JsonResponse
    {
        $incomingFields = $request->validate([
            'emailAddress' => 'required|email'
        ]);

        $user = User::where('emailAddress', $incomingFields['emailAddress'])->first();
        
        if (!$user){
            return response()->json([
                'success' => false,
                'message' => 'Email Address not found!'
            ], 404);
        }

        if ($user->statusId==2){
            return response()->json([
                'success' => false,
                'message' => 'User account has been suspended!'
            ], 403);
        }

        if ($user){
            $otp = rand(100000, 999999);
            Otp::updateOrCreate(
                ['userId' => $user['userId']],
                ['otp' => $otp, 'expiry_at' => Carbon::now()->addMinutes(10),]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP successfully sent',
            'emailAddress' => $user['emailAddress'],
            'firstName' => $user['firstName'],
            'userId' => $user['userId'],
            'otp' => $otp
        ], 200);
    }


    public function finishResetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|integer',
            'password' => 'required|min:8|confirmed'
        ]);

        $otp = Otp::where('userId', $request->userId)->where('otp', $request->otp)->first();
        if (!$otp){
            return response()->json([
                'success' => false,
                'message' => 'OTP is not valid'
            ], 400);
        }

        if (Carbon::now()->gt($otp->expiry_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired'
            ], 400);
        }

        $user = User::where('userId', $request->userId)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        $otp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
    
}
    

