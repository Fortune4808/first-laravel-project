<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setup\GenderController;
use App\Http\Controllers\Setup\StatusController;
use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\User\UserController as UserUserController;
use App\Http\Controllers\User\UserController;

Route::apiResource('/status', StatusController::class);
Route::apiResource('/gender', GenderController::class);

Route::apiResource('/user/register', UserController::class)->only(['store']);
Route::post('/admin/login', [AuthController::class, 'login']);

Route::post('/user/login', [AuthController::class, 'login']);

Route::middleware(['auth:admin', 'permission:CREATE STAFF'])->group(function () {
   Route::apiResource('/admin/register', StaffController::class)->only(['store']);
});
Route::middleware(['auth:admin', 'permission:VIEW STAFF'])->group(function () {
   Route::apiResource('/admin/profile', StaffController::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE STAFF'])->group(function () {
   Route::apiResource('/admin/update', StaffController::class)->only(['update']);
});
Route::middleware(['auth:admin', 'permission:VIEW USERS'])->group(function () {
   Route::apiResource('/admin/user/profile', UserUserController::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE USERS'])->group(function () {
   Route::apiResource('/admin/user/update', UserUserController::class)->only(['update']);
});

Route::middleware(['auth:users'])->group(function () {
   Route::apiResource('/user/profile', UserController::class)->only(['show']);
});
Route::middleware(['auth:users'])->group(function () {
   Route::apiResource('/user/profile', UserController::class)->only(['update']);
});

