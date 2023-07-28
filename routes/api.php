<?php

use App\Http\Controllers\BomSaleController;
use App\Http\Controllers\COGSController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FcRegisterController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:api')->get('/session', function () {
//     return session()->all();
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('role:Super-Admin')->group(function(){
});

Route::middleware('role:Admin')->group(function(){

});


Route::apiResource('companies', App\Http\Controllers\CompanyController::class);



Route::get('/bills', [App\Http\Controllers\BillController::class, 'index'])->name('bills.list');
Route::post('/bills', [App\Http\Controllers\BillController::class, 'store'])->name('bills.store');
Route::get('/bills/edit/{id}', [App\Http\Controllers\BillController::class, 'edit'])->name('bills.edit');
Route::post('/bills/bulkdelete', [App\Http\Controllers\BillController::class, 'bulkdelete'])->name('bills.bulkdelete');
Route::post('/bills/{id}', [App\Http\Controllers\BillController::class, 'update'])->name('bills.update');
Route::delete('/bills/{id}', [App\Http\Controllers\BillController::class, 'delete'])->name('bills.delete');
Route::get('/bills/exported-data', [App\Http\Controllers\BillController::class, 'exportData']);

Route::get('/boms/get-all-boms', [App\Http\Controllers\BomController::class, 'getAllBoms'])->name('boms.getAllBoms');
Route::get('/boms', [App\Http\Controllers\BomController::class, 'index'])->name('boms.list');
Route::post('/boms', [App\Http\Controllers\BomController::class, 'store'])->name('boms.store');
Route::get('/boms/edit/{id}', [App\Http\Controllers\BomController::class, 'edit'])->name('boms.edit');
Route::post('/boms/bulkdelete', [App\Http\Controllers\BomController::class, 'bulkdelete'])->name('boms.bulkdelete');
Route::post('/boms/{id}', [App\Http\Controllers\BomController::class, 'update'])->name('boms.update');
Route::delete('/boms/{id}', [App\Http\Controllers\BomController::class, 'delete'])->name('boms.delete');
Route::get('/boms/exported-data', [App\Http\Controllers\BomController::class, 'exportData']);


Route::get('/get-users-by-role', [App\Http\Controllers\UserController::class, 'getUsersByRole'])->name('users.getUsersByRole');
Route::post('/assign-user', [App\Http\Controllers\UserController::class, 'assignUser'])->name('users.assignUser');

Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.list');
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');

// Route::apiResource('sales', \App\Http\Controllers\SaleController::class);
Route::get('/sales', [App\Http\Controllers\SaleController::class, 'index'])->name('sales.list');
Route::post('/sales', [App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
Route::get('/sales/edit/{id}', [App\Http\Controllers\SaleController::class, 'edit'])->name('sales.edit');
Route::post('/sales/{id}', [App\Http\Controllers\SaleController::class, 'update'])->name('sales.update');
Route::delete('/sales/{id}', [App\Http\Controllers\SaleController::class, 'delete'])->name('sales.delete');
Route::get('/sales/exported-data', [App\Http\Controllers\SaleController::class, 'exportData'])->name('sales.list');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.list');

//Route::get('/register/exported-data/{formats}',[App\Http\Controllers\RegisterController::class,'exportCsvData']);
Route::get('/register/exported-data/{fileType}', [App\Http\Controllers\RegisterController::class, 'exportData']);
Route::get('/register/{id}/export-balance-sheet/{fileType}', [App\Http\Controllers\RegisterController::class, 'exportBalanceSheet']);
Route::get('/registers', [App\Http\Controllers\RegisterController::class, 'index'])->name('registers');
Route::get('/registers/view/{id}', [App\Http\Controllers\RegisterController::class, 'view'])->name('registers.view');
Route::post('/registers/close/', [App\Http\Controllers\RegisterController::class, 'close'])->name('registers.close');
Route::post('/registers/undo/', [App\Http\Controllers\RegisterController::class, 'undo'])->name('registers.undo');
Route::post('/register/opening/inventory', [App\Http\Controllers\InventoryController::class, 'store']);


// export routes
// Route::get('/bill/{billId}/export/{fileName}',[ExportController::class,'exportBillXls']);

Route::get('/bill/blade/{billId}/export/{format}',[ExportController::class,'exportBillBladeXls']);
Route::get('/bom/blade/{bomId}/export/{format}',[ExportController::class,'exportBomBladeXls']);
Route::get('/sale/blade/{saleId}/export/{format}',[ExportController::class,'exportSaleBladeXls']);

Route::post('/bill/import',[ImportController::class,'billImport']);
Route::post('/bom/import',[ImportController::class,'bomImport']);
Route::post('/sale/import',[ImportController::class,'saleImport']);



// cogs routes
Route::get('/cogs/exported-data/{fileType}',[COGSController::class,'exportData']);
Route::get('/cogs/boms/{id}',[COGSController::class,'getById']);
Route::get('/cogs/boms',[COGSController::class,'getAll']);

//bom sales routes
Route::get('/bomSales', [App\Http\Controllers\BomSaleController::class, 'index'])->name('bomSales.list');
Route::post('/bomSales', [App\Http\Controllers\BomSaleController::class, 'store'])->name('bomSales.store');
Route::get('/bomSales/edit/{id}', [App\Http\Controllers\BomSaleController::class, 'edit'])->name('bomSales.edit');
Route::post('/bomSales/{id}', [App\Http\Controllers\BomSaleController::class, 'update'])->name('bomSales.update');
Route::delete('/bomSales/{id}', [App\Http\Controllers\BomSaleController::class, 'delete'])->name('bomSales.delete');


// role permssion management routes

// role permssion routes

Route::get('/roles/all',[RolePermissionController::class,'roleIndex']);
Route::post('/roles/create',[RolePermissionController::class,'roleStore']);
Route::get('/roles/edit/{id}',[RolePermissionController::class,'roleEdit']);
Route::put('/roles/update/{id}',[RolePermissionController::class,'roleUpdate']);
Route::delete('/roles/delete/{id}',[RolePermissionController::class,'roleDestroy']);

Route::get('/permissions/all',[RolePermissionController::class,'permissionIndex']);
Route::post('/permissions/create',[RolePermissionController::class,'permissionStore']);
Route::delete('/permissions/delete/{id}',[RolePermissionController::class,'permissionDestroy']);


// user role management routes
Route::put('/users/roles/update/{id}', [App\Http\Controllers\UserController::class, 'assignUserRole']);

// company route 
Route::get('/company/overview', [CompanyController::class, 'companyOverview']);
Route::get('/company/list',[CompanyController::class, 'getAll']);
Route::delete('/company/delete/{id}',[CompanyController::class, 'destroy']);
Route::put('/company/restore/{id}',[CompanyController::class, 'restore']);

Route::get('/bom-sale-items', [BomSaleController::class, 'getBomSaleItems']);
Route::get('/fc-registers', [FcRegisterController::class, 'index']);
Route::get('/fc-registers/view/{id}', [FcRegisterController::class, 'view']);