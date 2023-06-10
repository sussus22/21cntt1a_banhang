<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BillController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/cct', function () {
    return view('checkout');
});
//đăng ký và đăng nhập của khách hàng
Route::get('/dangky',[PageController::class,'getSignin'])->name('getsignin');
Route::post('/dangky',[PageController::class,'postSignin'])->name('postsignin');

Route::get('/trangchu',[PageController::class, 'getSanPham'])->name('banhang.trangchu');;
Route::get('/trangchitiet/{id}', [PageController::class, 'show'])->name('trangchu.show');
Route::get('add-to-cart/{id}',[PageController::class,'addToCart'])->name('banhang.addToCart');
Route::get('del-to-cart/{id}',[PageController::class,'delCartItem'])->name('banhang.delToCart');
Route::get('get-checkout',[PageController::class,'getCheckout'])->name('banhang.getcheckout');
Route::post('checkout',[PageController::class,'Checkout'])->name('banhang.checkout');

/*------ phần quản trị ----------*/
Route::get('/admin/dangnhap',[UserController::class,'getLogin'])->name('admin.getLogin');
Route::post('/admin/dangnhap',[UserController::class,'postLogin'])->name('admin.postLogin');
Route::get('/admin/dangxuat',[UserController::class,'getLogout'])->name('admin.getLogout');

Route::group(['prefix'=>'admin','middleware'=>'adminLogin'],function(){
   
        Route::group(['prefix'=>'category'],function(){
            // admin/category/danhsach
            Route::get('danhsach',[CategoryController::class,'getCateList'])->name('admin.getCateList');
            // admin/category/them
            Route::get('them',[CategoryController::class,'getCateAdd'])->name('admin.getCateAdd');
            Route::post('them',[CategoryController::class,'postCateAdd'])->name('admin.postCateAdd');
            Route::delete('xoa/{id}',[CategoryController::class,'getCateDelete'])->name('admin.getCateDelete');
            Route::get('sua/{id}',[CategoryController::class,'getCateEdit'])->name('admin.getCateEdit');
            Route::put('sua/{id}',[CategoryController::class,'postCateEdit'])->name('admin.postCateEdit');
        });

        //viết tiếp các route khác cho crud products, users,.... thì viết tiếp

        Route::group(['prefix'=>'bill'],function(){
            Route::get('danhsachbill',[BillController::class,'listBillAll'])->name('admin.listBillAll');
            // admin/bill/{status}
            Route::get('{status}',[BillController::class,'getBillList'])->name('admin.getBillList');
            //phần bill này không nhất thiết phải dùng request ajax, làm như những hàm bình thường, phần route này cô vẫn để lại để tham khảo
            //by laravel request
            Route::get('{id}/{status}',[BillController::class,'updateBillStatus'])->name('admin.updateBillStatus');
            //by ajax request
            Route::post('updatebill/{id}', [BillController::class, 'updateBillStatusAjax'])->name('admin.updateBillStatusAjax');
           
            Route::delete('deletebill/{id}',[BillController::class,'cancelBill'])->name('admin.cancelBill');
        });
        Route::group(['prefix'=>'quanlyuser'],function(){
            Route::get('danhsach',[UserController::class,'index'])->name('admin.getUserList');
            Route::get('sua/{id}',[UserController::class,'edit'])->name('admin.getUserEdit');
            Route::put('sua/{id}',[UserController::class,'update'])->name('admin.postUserEdit');
            Route::delete('xoa/{id}',[UserController::class,'destroy'])->name('admin.getUserDelete');
        });
});
