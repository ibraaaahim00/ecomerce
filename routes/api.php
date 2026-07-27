<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReviewController;
/*
|--------------------------------------------------------------------------
| Public Auth
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me',      [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Products & Categories — القراءة فقط للمستخدم العادي
    Route::get('products',          [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    Route::get('categories',             [CategoryController::class, 'index']);
    Route::get('categories/{category}',  [CategoryController::class, 'show']);

    // السلة
    Route::apiResource('cart', CartItemController::class);

    // الأوردرات
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'myOrders']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/stats', [AdminDashboardController::class, 'stats']);

    // إدارة الأوردرات
    Route::get('/admin/orders',              [OrderController::class, 'allOrders']);
    Route::put('/admin/orders/{order}',      [OrderController::class, 'updateStatus']);

    // إدارة المنتجات (الكتابة للأدمن فقط)
    Route::post('products',             [ProductController::class, 'store']);
    Route::put('products/{product}',    [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // إدارة الكاتيجوريز (الكتابة للأدمن فقط)
    Route::post('categories',               [CategoryController::class, 'store']);
    Route::put('categories/{category}',     [CategoryController::class, 'update']);
    Route::delete('categories/{category}',  [CategoryController::class, 'destroy']);
});
Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::post('logout',[AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('resend-otp', [AuthController::class, 'resendOTP']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('reset password', [AuthController::class, 'resetPassword']);
Route::post('profile', [AuthController::class, 'profile']);


