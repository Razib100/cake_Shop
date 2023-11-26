<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\PayPalController;

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
Route::prefix('admin')->middleware('auth:admin')->group(function () {
//Route::group(['prefix' => 'admin',  'admin/home'], function () {
    /*Route::get('logout', [LoginController::class,'logout']);
    Route::auth();
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');*/

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::get('/contactUs', [DashboardController::class,'contactUs'])->name('contactUs');
    Route::get('/contactUs/msg', [DashboardController::class,'contactUsMsg'])->name('contactUsMsg');
    Route::delete('/contactUs/{id}', [DashboardController::class,'contactUs_destroy'])->name('contactUsDelete');

    /*IMAGE UPLOAD IN SUMMER NOTE*/
    Route::post('image/upload', [ImageController::class,'upload_image']);

    Route::resource('profile_update', ProfileUpdateController::class);

    /* CUSTOMER MANAGEMENT */
    Route::post('customers/assign', [CustomerController::class,'assign'])->name('customers.assign');
    Route::post('customers/unassign', [CustomerController::class,'unassign'])->name('customers.unassign');
    Route::resource('customers', CustomerController::class);

    /* CATEGORY MANAGEMENT */
    Route::post('category/assign', [CategoryController::class,'assign'])->name('category.assign');
    Route::post('category/unassign', [CategoryController::class,'unassign'])->name('category.unassign');
    Route::resource('category', CategoryController::class);

    /* PRODUCT MANAGEMENT */
    Route::post('products/assign', [ProductController::class,'assign'])->name('products.assign');
    Route::post('products/unassign', [ProductController::class,'unassign'])->name('products.unassign');
    Route::resource('products', ProductController::class);
    Route::delete('deleteProductImg', [ProductController::class,'deleteProductImg'])->name('products.deleteProductImg');

    /* ORDER MANAGEMENT */
    Route::get('orders/print/{id}', [OrderController::class,'invoicePrint'])->name('orders.print');
    Route::get('orders/export', [OrderController::class,'exportOrder'])->name('orders.export');
    Route::post('orders/status', [OrderController::class,'statusUpdate'])->name('orders.status');
    Route::post('orders/assign', [OrderController::class,'assign'])->name('orders.assign');
    Route::post('orders/unassign', [OrderController::class,'unassign'])->name('orders.unassign');
    Route::resource('orders', OrderController::class);
    Route::post('get_product_price',[OrderController::class,'getProductPrice'])->name('orders.getProductPrice');

    /* SETTING MANAGEMENT */
    Route::post('delete_settings_image', [SettingController::class,'deleteSettingsImage']);
    Route::resource('settings', SettingController::class);

    Auth::routes();
});

Route::get('/admin',[LoginController::class,'showAdminLoginForm'])->name('admin.login-view');
Route::post('/admin',[LoginController::class,'adminLogin'])->name('admin.login');

Route::group(['middleware' => 'web'], function () {
    Route::get('logout', [LoginController::class,'logout']);
    Route::auth();
    Route::get('home/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::post('addToCart',[HomeController::class,'addToCart'])->name('addToCart');
Route::post('updateToCart',[HomeController::class,'updateToCart'])->name('updateToCart');
Route::post('getCartTotal',[HomeController::class,'getCartTotal'])->name('getCartTotal');
Route::post('getCartProducts',[HomeController::class,'getCartProducts'])->name('getCartProducts');
Route::post('getConfirmOrderSection',[HomeController::class,'getConfirmOrderSection'])->name('getConfirmOrderSection');
Route::post('addOrder',[HomeController::class,'addOrder'])->name('addOrder');
Route::get('gcashPaymentSuccess',[PayPalController::class,'gcashPaymentSuccess'])->name('gcashPaymentSuccess');
Route::get('cancelOrder',[PayPalController::class,'cancelOrder'])->name('cancelOrder');
Route::post('getProduct',[HomeController::class,'getProduct'])->name('getProduct');
Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/contact-us', [HomeController::class,'contact_us'])->name('contact_us');
Route::post('storeContactInfo',[HomeController::class,'storeContactInfo']);
Route::get('orderPlaced/{id}',[HomeController::class,'orderPlaced'])->name('orderPlaced');
Route::get('privacyPolicy',[HomeController::class,'privacyPolicy'])->name('privacyPolicy');
Route::get('deletionInstruction',[HomeController::class,'deletionInstruction'])->name('deletionInstruction');

Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

Route::post('/payment/process', [PayPalController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success', [PayPalController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [PayPalController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('gcashSuccessPayment',[PayPalController::class,'gcashSuccessPayment']);
