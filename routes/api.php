<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Setup\GenderController;
use App\Http\Controllers\Setup\StatusController;
use App\Http\Controllers\Admin\LocationContoller;
use App\Http\Controllers\Admin\Auth\AuthController as AdminAuth;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\User\Auth\AuthController as UserAuth;
use App\Http\Controllers\Admin\User\UserController as AdminUserController;

Route::apiResource('/status', StatusController::class);
Route::apiResource('/gender', GenderController::class);

Route::post('/user/register', [UserAuth::class, 'register']);
Route::post('/admin/login', [AdminAuth::class, 'login']);
Route::post('/admin/resetPassword', [AdminAuth::class, 'resetPassword']);
Route::post('/admin/finishResetPassword', [AdminAuth::class, 'finishResetPassword']);
Route::post('/user/login', [UserAuth::class, 'login']);
Route::post('/user/resetPassword', [UserAuth::class, 'resetPassword']);
Route::post('/user/finishResetPassword', [UserAuth::class, 'finishResetPassword']);

Route::middleware(['auth:admin', 'permission:CREATE STAFF'])->group(function () {
   Route::apiResource('/admin', StaffController::class)->only(['store']);
});
Route::middleware(['auth:admin', 'permission:VIEW STAFF'])->group(function () {
   Route::apiResource('/admin', StaffController::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE STAFF'])->group(function () {
   Route::apiResource('/admin', StaffController::class)->only(['update']);
});
Route::middleware(['auth:admin', 'permission:VIEW USERS'])->group(function () {
   Route::apiResource('/admin/user', AdminUserController::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE USERS'])->group(function () {
   Route::apiResource('/admin/user', AdminUserController::class)->only(['update']);
});
Route::middleware(['auth:admin', 'permission:ADD LOCATION'])->group(function () {
   Route::apiResource('/admin/location', LocationContoller::class)->only(['store']);
});
Route::middleware(['auth:admin', 'permission:VIEW LOCATION'])->group(function () {
   Route::apiResource('/admin/location', LocationContoller::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE LOCATION'])->group(function () {
   Route::apiResource('/admin/location', LocationContoller::class)->only(['update']);
});
Route::middleware(['auth:admin', 'permission:DELETE LOCATION'])->group(function () {
   Route::apiResource('/admin/location', LocationContoller::class)->only(['destroy']);
});
Route::middleware(['auth:admin', 'permission:ADD SLOT'])->group(function () {
   Route::apiResource('/admin/slot', SlotController::class)->only(['store']);
});
Route::middleware(['auth:admin', 'permission:VIEW SLOT'])->group(function () {
   Route::apiResource('/admin/slot', LocationContoller::class)->only(['index', 'show']);
});
Route::middleware(['auth:admin', 'permission:UPDATE SLOT'])->group(function () {
   Route::apiResource('/admin/slot', SlotController::class)->only(['update']);
});

Route::middleware(['auth:users'])->group(function () {
   Route::apiResource('/user', UserController::class);
});


