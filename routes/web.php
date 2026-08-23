<?php

/*
|--------------------------------------------------------------------------
| Frontend Web Controllers
|--------------------------------------------------------------------------
*/
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\Frontend\ShopController;

use App\Http\Controllers\Frontend\UserDashboardController;
use App\Models\Order;
use App\Http\Controllers\Apps\Order\OrderController;
use App\Http\Controllers\Apps\Marketing\OfferController;
use App\Http\Controllers\Payment\StripePaymentController;


/*
|--------------------------------------------------------------------------
| Frontend Web Routes
|--------------------------------------------------------------------------
*/

// home and static pages
Route::middleware('clear.direct_checkout')->group(function(){
    Route::controller(PagesController::class)->group(function () {
        Route::get('/', 'home')->name('homepage');
        Route::get('/about-us', 'about')->name('about');
        Route::get('/contact-us', 'contact')->name('contact');
        Route::post('/send-message', 'resetToFresh')->name('message');
    });

    Route::get('terms-condition', function(){
        return view('frontend.pages.static.terms');
    })->name('terms');
    Route::get('privacy-policy', function(){
        return view('frontend.pages.static.privacy-policy');
    })->name('privacy.policy');
    Route::get('refund-policy', function(){
        return view('frontend.pages.static.refund');
    })->name('refund.policy');

    // product-details page
    Route::get('/product/{slug}', [ShopController::class, 'productDetails'])->name('product-details');

    // shop page
    Route::controller(ShopController::class)->group(function () {
        Route::get('shop', 'allProducts')->name('shop');
        Route::get('category/{slug1?}/{slug2?}/{slug3?}', [ShopController::class, 'categoryProducts'])->name('category.products');
        Route::get('/search', 'searchProducts')->name('search.products');
        Route::get('/wishlist', 'wishlist')->name('wishlist');
    });

    Route::get('/cart', function(){
        return view('frontend.pages.order.cart');
    })->name('cart');

    // user dashboard page
    Route::controller(UserDashboardController::class)->middleware('auth')->group(function () {
        Route::get('/account', 'dashboard')->name('user.dashboard');
        Route::get('/account/order', 'orders')->name('user.orders');
        Route::get('/account/order-info/{user_id}/{order_id}', 'invoice')->name('order.invoice');
        Route::get('/account/edit-profile', 'editProfile')->name('user.edit.profile');
        Route::get('/account/update-password', 'updatePassword')->name('user.edit.password');
        Route::get('/account/my-wishlist', 'wishlist')->name('user.wishlist');
        Route::post('avatar/upload', 'uploadAvatar')->name('user.avatar.upload');
        Route::post('avatar/remove', 'removeAvatar')->name('user.avatar.remove');
    });
});


// checkout page
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::get('/success-order/{order_id}', function ($order_id) {
    $order = Order::where('order_id', $order_id)->firstOrFail();
    return view('frontend.pages.order.success', compact('order'));
})->name('success.order');

// stripe payment
Route::get('/checkout/success', [StripePaymentController::class, 'handleStripeSuccess'])->name('checkout.success');
Route::get('/checkout.cancel', [StripePaymentController::class, 'handleStripeCancel'])->name('checkout.cancel');

// order invoice download pdf
Route::get('/order-invoice/{order_id}', [OrderController::class, 'downloadInvoice'])->name('order.invoice.pdf');

Route::fallback(function () {
    return view('pages.system.fallback');
});

require __DIR__ . '/auth.php';
