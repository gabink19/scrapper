<?php

date_default_timezone_set("Asia/Jakarta");
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokpedToolsController;

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
    // return view('welcome');
});

Route::get('/test',[TokpedToolsController::class, 'getListProductCurl']);

Route::group(['prefix' => 'tokped'], function(){
    Route::get('/',[TokpedToolsController::class, 'index']);
    Route::get('/etalase',[TokpedToolsController::class, 'etalase']);
    Route::post('/etalase',[TokpedToolsController::class, 'etalase']);
    Route::get('/scrapPerProduct',[TokpedToolsController::class, 'scrapPerProduct']);
    Route::get('/getListProduct',[TokpedToolsController::class, 'getListProduct']);
    Route::get('/getSellerCategory',[TokpedToolsController::class, 'getSellerCategory']);
});