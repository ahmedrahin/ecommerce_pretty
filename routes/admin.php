<?php

/*
|--------------------------------------------------------------------------
| Admin Web Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Apps\User\PermissionManagementController;
use App\Http\Controllers\Apps\User\RoleManagementController;
use App\Http\Controllers\Apps\User\UserManagementController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\Apps\Product\BrandController;
use App\Http\Controllers\Apps\Product\CategoryController;
use App\Http\Controllers\Apps\Product\SubcategoryController;
use App\Http\Controllers\Apps\Product\SubsubcategoryController;
use App\Http\Controllers\Apps\Product\ProductController;
use App\Http\Controllers\Apps\Product\VariantController;
use App\Http\Controllers\Apps\Order\ShippingController;
use App\Http\Controllers\Apps\Product\ProductEditController;
use App\Http\Controllers\Apps\Product\FilterOptionController;
use App\Http\Controllers\Apps\Order\OrderController;
use App\Http\Controllers\Apps\Settings\SettingController;
use App\Http\Controllers\Apps\User\AdminManagementController;
use App\Http\Controllers\Apps\Order\CouponController;
use App\Http\Controllers\Apps\Order\WarrantyController;
use App\Http\Controllers\Apps\Order\ServiceController;
use App\Http\Controllers\Apps\Marketing\ReviewController;
use App\Http\Controllers\Apps\Marketing\SliderController;
use App\Http\Controllers\Apps\User\ContactMessageController;
use App\Http\Controllers\Apps\Marketing\SubscriptionController;
use App\Http\Controllers\Apps\Product\ProductQuestionController;
use App\Http\Controllers\Apps\Marketing\OfferController;
use App\Http\Controllers\Apps\Report\StockController;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/c-clean', function (){
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    session()->flush();
    return env('APP_NAME') . ' All cache cleared.';
});

Route::middleware(['isAdmin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/all-notification', [DashboardController::class, 'allNotification'])->name('all.notification');

    Route::name('admin-management.')->middleware('can:admin catalouge')->group(function () {
        Route::resource('/admin-list', AdminManagementController::class);
        Route::resource('/admin-management/roles', RoleManagementController::class);
        Route::resource('/admin-management/permissions', PermissionManagementController::class);
    });

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
    });

    // ->middleware('can:product catalouge')
    Route::prefix('product-catalogue')->name('product-catalogue.')->group(function () {
        Route::get('brand', [BrandController::class, 'index'])->name('brand.index');
        Route::get('category', [CategoryController::class, 'index'])->name('category.index');
        Route::post('category/update-sort-order', [CategoryController::class, 'updateSortOrder'])->name('category.update-sort-order');
        Route::get('subcategory', [SubcategoryController::class, 'index'])->name('subcategory.index');
        Route::get('subsubcategory', [SubsubcategoryController::class, 'index'])->name('subsubcategory.index');
        Route::get('/product-filter', [FilterOptionController::class, 'index'])->name('product.filter');
        Route::get('/get-category-filters/{id}', [FilterOptionController::class, 'getCategoryFilters'])->name('category.filters');
        Route::get('/get-category-filters-edit/{id}', [FilterOptionController::class, 'getCategoryFiltersEdit'])->name('category.filters.edit');
    });

    // product management
    Route::name('product-management.')->group(function () {
        Route::controller(ProductController::class)->group(function () {
            // Apply middleware for permissions
            Route::get('/all-product', 'index')->name('index')->middleware('can:all product');
            Route::get('/create-product', 'create')->name('create');
            Route::post('/store-product', 'store')->name('store');
            Route::get('/product-edit/{id}', [ProductEditController::class, 'edit'])->name('edit')->middleware('can:update product');
            Route::post('/product-update/{id}', [ProductEditController::class, 'update'])->name('update')->middleware('can:update product');
            Route::post('/update-product-order', 'updateOrder')->name('update-order')->middleware('can:update product');
            Route::get('/product-details/{id}', 'show')->name('show')->middleware('can:all product');

            // API for categories (without middleware, as they might be public)
            Route::get('/get-brand/{category_id}', [BrandController::class, 'getBrand']);
            Route::get('/get-subcategories/{category_id}', [SubcategoryController::class, 'getSubcategories']);
            Route::get('/get-subsubcategories/{subcategory_id}', [SubsubcategoryController::class, 'getSubsubcategories']);
            Route::get('/get-subsubcategories-for-multiple', [SubsubcategoryController::class, 'getSubsubcategoriesForMultiple']);

            // API for product variations
            Route::get('/products/{product}/variants', [VariantController::class, 'productVariants']);
            Route::get('/get-attribute-value/{attribute_id}', [VariantController::class, 'getAttributeValue']);
            Route::get('/full-compare-product', 'fullCompare')->name('full.compare');
        });
    });

    // product variant
    Route::name('product-variant.')->group(function(){
        Route::controller(VariantController::class)->group(function () {
            Route::get('/product-variant', 'index')->name('index');
            Route::post('product-stock/{stock}/toggle', 'toggleVariant')->name('stock.toggle');
        });
    });


    // shipping management
    Route::name('shipping.')->group(function(){
        Route::controller(ShippingController::class)->group(function () {
            Route::get('/shipping-district', 'district')->name('district');
            Route::get('/shipping-method', 'shipping_method')->name('shipping_method');
        });
    });

    // order management
    Route::name('order-management.')->middleware('can:order catalouge')->group(function(){
        Route::resource('/order', OrderController::class);
        Route::get('/today-order', [OrderController::class, 'todayOrder'])->name('order.today');
        Route::get('/monthly-order/{year?}/{month?}', [OrderController::class, 'monthlyOrder'])->name('order.monthly');
        Route::get('/order-invoice/{id}', [OrderController::class, 'order_invoice'])->name('invoice');
        Route::get('trash-order', [OrderController::class, 'trash'])->name('order.trash');
    });


    Route::resource('warranty', WarrantyController::class);
    Route::get('/warranty/{year?}/{month?}', [WarrantyController::class, 'index'])->name('warranty.index');
    Route::post('/warranty/update/{id}', [WarrantyController::class, 'update'])->name('warranty.update');
    Route::get('all-warranty', [WarrantyController::class, 'all'])->name('all.warranty');

    Route::resource('service', ServiceController::class);
    Route::get('/service/{year?}/{month?}', [ServiceController::class, 'index'])->name('service.index');
    Route::post('/service/update/{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::get('all-servicing', [ServiceController::class, 'all'])->name('all.service');


    // coupon
    Route::name('coupon.')->group(function(){
        Route::get('coupon', [CouponController::class, 'index'])->name('index');
    });

    // review
    Route::name('review.')->group(function(){
        Route::get('product-reviews', [ReviewController::class, 'index'])->name('index');
        Route::get('image-reviews', [ReviewController::class, 'imageReview'])->name('image');
        Route::post('add-image-reviews', [ReviewController::class, 'imageReviewadd'])->name('addimage');
        Route::delete('delete-image-reviews/{id}', [ReviewController::class, 'deleteReview'])->name('deleteimage');
    });

    // contact message
    Route::name('contact.')->group(function(){
        Route::get('contact-message', [ContactMessageController::class, 'index'])->name('message');
        Route::get('weekly-contact-message', [ContactMessageController::class, 'weekly'])->name('weekly.message');
        Route::get('contact-message-details/{id}', [ContactMessageController::class, 'show'])->name('message.details');
        Route::post('contact-message-reply/{id}', [ContactMessageController::class, 'reply'])->name('message.reply');
        Route::post('contact-message-delete/{id}', [ContactMessageController::class, 'destroy'])->name('message.delete');
    });

    // product quesiton
    Route::name('question.')->group(function(){
        Route::get('question-message', [ProductQuestionController::class, 'index'])->name('product');
        Route::get('weekly-question', [ProductQuestionController::class, 'weekly'])->name('weekly');
        Route::get('question-message-details/{id}', [ProductQuestionController::class, 'show'])->name('details');
        Route::post('question-reply/{id}', [ProductQuestionController::class, 'reply'])->name('reply');
        Route::post('question-delete/{id}', [ProductQuestionController::class, 'destroy'])->name('delete');
    });

    Route::resource('subscription', SubscriptionController::class);

    // setting
    Route::name('setting.')->group(function(){
        Route::controller(SettingController::class)->group(function(){
            Route::get('/genarel-settings', 'manage')->name('manage');
            Route::post('homepage-pages-content-update', 'homepagesContent')->name('home.content.update');
            Route::get('/website-frontend-settings', 'website_setting')->name('website');
            Route::post('pages-content-update', 'pagesContent')->name('pages.content.update');
        });
    });

     // home slider
    Route::resource('slider', SliderController::class);

    // offers
    Route::resource('offer', OfferController::class);
    Route::post('/offer/update/{id}', [OfferController::class, 'update'])->name('offer.update');


    // report
    Route::name('report.')->group(function(){
        Route::controller(StockController::class)->group(function(){
            Route::get('/stock-out', 'stockOut')->name('stockout');
            Route::get('/low-stock', 'lowStock')->name('lowstock');
            Route::get('/stock-in/{year?}/{month?}', 'stockIn')->name('stockin');
            Route::get('/add-new-stock', 'addStock')->name('add.stock');
            Route::post('/stock-in-store', 'storeStock')->name('store.stock');
        });
    });


    // ajax api calling
    Route::get('/products/search', function (Request $request) {
        $search = $request->get('q', '');
        return Product::query()
            ->orderBy('id', 'DESC')
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name as text']);
    })->name('products.search');

    Route::get('/users/search', function (Request $request) {
        $search = $request->get('q', '');
        return User::query()
            ->orderBy('id', 'DESC')
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name as text', 'email', 'phone']);
    })->name('users.search');

});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

