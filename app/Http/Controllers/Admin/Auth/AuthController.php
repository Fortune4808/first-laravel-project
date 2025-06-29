<?php

namespace App\Http\Controllers\Admin\Auth;

use Carbon\Carbon;
use App\Models\Setup\Otp;
use App\Models\Admin\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\StaffResource;
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
            ]);
        }

        if ($user->statusId==2){
            return response()->json([
                'success' => false,
                'message' => 'Staff account has been suspended!'
            ]);
        }

        $token = $user->createToken('admin-token')->plainTextToken; 

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'data' => new StaffResource($user)
        ]);
    }
    

    public function resetPassword(Request $request): JsonResponse
    {
        $incomingFields = $request->validate([
            'emailAddress' => 'required|email'
        ]);

        $user = Staff::where('emailAddress', $incomingFields['emailAddress'])->first();
        
        if (!$user){
            return response()->json([
                'success' => false,
                'message' => 'Email Address not found!'
            ]);
        }

        if ($user->statusId==2){
            return response()->json([
                'success' => false,
                'message' => 'Staff account has been suspended!'
            ]);
        }

        if ($user){
            $otp = rand(100000, 999999);
            Otp::updateOrCreate(
                ['userId' => $user['staffId']],
                ['otp' => $otp, 'expiry_at' => Carbon::now()->addMinutes(10),]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP successfully sent',
            'emailAddress' => $user['emailAddress'],
            'firstName' => $user['firstName'],
            'staffId' => $user['staffId']
        ]);
    }

    public function resendOTP(Request $request):JsonResponse
    {
        $user = Staff::where('staffId', $request->staffId)->first();
        if ($user){
            $otp = rand(100000, 999999);
            Otp::updateOrCreate(
                ['userId' => $user['staffId']],
                ['otp' => $otp, 'expiry_at' => Carbon::now()->addMinutes(10),]
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'OTP successfully sent'
        ]);
    }

    public function finishResetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|integer',
            'password' => 'required|min:8|confirmed'
        ]);

        $otp = Otp::where('userId', $request->staffId)->where('otp', $request->otp)->first();
        if (!$otp){
            return response()->json([
                'success' => false,
                'message' => 'OTP is not valid'
            ]);
        }

        if (Carbon::now()->gt($otp->expiry_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired'
            ]);
        }

        $user = Staff::where('staffId', $request->staffId)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        $otp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
    
}
