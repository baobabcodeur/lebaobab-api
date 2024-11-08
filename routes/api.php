<?php
// routes/api.php
 use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UrgentAdController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\OTPController;

Route::post('/send-otp', [OTPController::class, 'sendOtp']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/otp/request', [AuthController::class, 'requestOtp']);
Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
Route::post('/password/reset/request', [AuthController::class, 'requestPasswordReset']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/urgent-ads-all', [UrgentAdController::class, 'index']);
Route::get('/providers', [ServiceController::class, 'index']);
Route::get('/projects-all', [ProjectController::class, 'index']);



Route::middleware('auth:sanctum')->group(function () {

   

Route::get('users', [UserController::class, 'index']); // Afficher la liste des utilisateurs
Route::get('users/{id}', [UserController::class, 'show']); // Afficher un utilisateur spécifique

    
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);

    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{project_id}', [PaymentController::class, 'show']);
    Route::post('/payments/escrow', [PaymentController::class, 'createEscrow']);

    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages/{project_id}', [MessageController::class, 'index']);
    Route::get('/messages', [MessageController::class, 'listAll']);

    Route::post('/urgent-ads', [UrgentAdController::class, 'store']);
    Route::get('/urgent-ads', [UrgentAdController::class, 'index']);
    Route::get('/urgent-ads/{id}', [UrgentAdController::class, 'show']);
    Route::put('/urgent-ads/{id}', [UrgentAdController::class, 'update']); 
    Route::delete('/urgent-ads/{id}', [UrgentAdController::class, 'destroy']); 

    // Nombre total de projets
  

    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions', [SubscriptionController::class, 'show']);
    Route::delete('/subscriptions', [SubscriptionController::class, 'destroy']);
});



Route::post('/subscription/create', [SubscriptionController::class, 'createSubscription'])->name('subscription.create');
Route::post('/subscription/cancel/{subscriptionToken}', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');
Route::post('/subscription/callback', [SubscriptionController::class, 'callback'])->name('subscription.callback');
