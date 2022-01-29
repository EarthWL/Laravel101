<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['admin'])->group(function () {
    Route::get('/user',[UserController::class,'index'])->name('user');
    Route::get('/getUserList',[UserController::class,'show'])->name('getUserList');
    Route::get('/getUserTrash',[UserController::class,'trash'])->name('getUserTrash');
    Route::post('/addUser',[UserController::class,'store'])->name('addUser');
    Route::post('/getUserDetail',[UserController::class,'edit'])->name('getUserDetail');
    Route::post('/updateUser',[UserController::class,'update'])->name('updateUser');
    Route::post('/delUser',[UserController::class,'softdel'])->name('delUser');
    Route::post('/restoreUser',[UserController::class,'restore'])->name('restoreUser');
    Route::post('/destroyUser',[UserController::class,'destroy'])->name('destroyUser');
});