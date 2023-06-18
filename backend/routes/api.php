<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::resource('document', DocumentController::class);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/product', [ProductController::class, 'index']);

    Route::post('/product', [ProductController::class, 'store']);

    Route::patch('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);

    Route::post('/cart', [CartController::class, 'store']);
    Route::get('/cart/user', [CartController::class, 'getUserCart']);
    Route::delete('/cart/delete/{id}', [CartController::class, 'destroy']);
    Route::get('/cart/{id}', [CartController::class, 'show']);


    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order/user', [OrderController::class, 'getUserOrders']);
    Route::get('/purchase/user', [OrderController::class, 'getUserPurchase']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::patch('/order/update/status/{id}', [OrderController::class, 'updateStatus']);
    Route::post('/order/receipt/send', [OrderController::class, 'sendReceipt']);
    Route::get('/order/sales/today', [OrderController::class, 'getSalesToday']);
    Route::post('/store/order', [OrderController::class, 'walkInOrder']);
    Route::get('/order/delivery/available', [OrderController::class, 'getAllAvailableDelivery']);
    Route::post('/order/delivery/{id}', [OrderController::class, 'acceptDelivery']);
    Route::get('/order/delivery/rider', [OrderController::class, 'getRidersDelivery']);
    Route::post('/order/delivery/complete/{id}', [OrderController::class, 'completeDelivery']);

    Route::post('/rating', [OrderController::class, 'addRating']);

    Route::get('/user', [UserController::class, 'getUsersByType']);

    Route::post('/user/deactivate/{id}', [UserController::class, 'deactivateUser']);

    Route::post('/user/activate/{id}', [UserController::class, 'activateUser']);

    Route::patch('/user/update/{id}', [UserController::class, 'updateUser']);

    Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);

    Route::post('/register/user', [UserController::class, 'registerUser']);

    Route::get('/user/{id}', [UserController::class, 'show']);


    Route::post('/refund', [RefundController::class, 'store']);
    Route::get('/refund/user', [RefundController::class, 'index']);
    Route::delete('/refund/delete/{id}', [RefundController::class, 'destroy']);
    Route::get('/refund/{id}', [RefundController::class, 'show']);
    Route::patch('/refund/update/{id}', [RefundController::class, 'update']);
    Route::patch('/refund/update/status/{id}', [RefundController::class, 'updateStatus']);

});
Route::get('/product/store/{id}', [ProductController::class, 'getStoreProducts']);
Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/product', [ProductController::class, 'getAllProducts']);


Route::get('/test', [DocumentController::class, 'test']);

Route::post('/email', [UserController::class, 'sendEmail']);

Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/verify-email', [AuthController::class, 'verifyEmail']);