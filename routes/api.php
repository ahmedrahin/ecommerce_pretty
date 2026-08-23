<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PrintfulWebhookController;
use App\Http\Controllers\Apps\BrandController;

use Illuminate\Support\Facades\Http;

// use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\TicketParcentController;
// use App\Http\Controllers\Api\AirlineController;
// use App\Http\Controllers\Api\AirlinesCommissionController;
// use App\Http\Controllers\Api\BankDetailsController;
// use App\Http\Controllers\Api\FundController;
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

Route::post('/printful/shipping_deliverd', [PrintfulWebhookController::class, 'shipping_deliverd']);

Route::get('/printful-test', function () {
    $productId = 415783222;
    $response = Http::withToken(env('PRINTFUL_API_KEY'))
        ->get("https://api.printful.com/store/products/$productId");

    return $response->json();
});