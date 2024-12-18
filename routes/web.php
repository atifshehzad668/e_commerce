<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
<<<<<<< HEAD
=======
use App\Http\Controllers\admin\OrderController;
>>>>>>> origin/master
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\AuthController;




<<<<<<< HEAD
// Route::get('/', function () {
//     return view('welcome');
// });

=======
>>>>>>> origin/master

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
// Route definition
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/cart', [CartController::class, 'Cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-to-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-cart', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
// In routes/web.php or routes/api.php
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process/checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}', [CartController::class, 'thanks'])->name('front.thanks');


// user register route


Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/user-login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/user-login', [AuthController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/register/user', [AuthController::class, 'register_user'])->name('account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.process.register');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/user-profile', [AuthController::class, 'profile'])->name('account.profile');
<<<<<<< HEAD
=======
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/order-detail/{orderId}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
>>>>>>> origin/master
        Route::get('/user-logout', [AuthController::class, 'logout'])->name('account.logout');
    });
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');


        // categories routes
        Route::resource('categories', CategoryController::class);
        Route::get('/sub-categories/index', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/edit/{id}', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/update/{id}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/destroy/{id}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');


        //brands routes
        Route::resource('brands', BrandController::class);
        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('product-images/delete', [ProductImageController::class, 'delete'])->name('product-images.delete');


        //product routes
        Route::resource('products', ProductController::class);
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');



<<<<<<< HEAD
        // shop routes

=======
        // order detail routes
        Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
        Route::get('/orders/{id}',[OrderController::class,'detail'])->name('orders.detail');
        Route::post('/orders/change-status/{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/orders/send-email/{id}',[OrderController::class,'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');
>>>>>>> origin/master




        //front routes



        Route::get('product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');
        Route::get('/getslug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});

require __DIR__ . '/auth.php';
