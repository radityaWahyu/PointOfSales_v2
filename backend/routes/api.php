<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\OutputTypeController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// -------------------Endpoint Category---------------------------------
Route::get('category/list', [CategoryController::class, 'listData']);
Route::apiResource('category', CategoryController::class)->except(['destroy']);
Route::delete('category', [CategoryController::class, 'destroy']);

// -------------------Endpoint Unit---------------------------------
Route::get('unit/list', [UnitController::class, 'listData']);
Route::apiResource('unit', UnitController::class)->except(['destroy']);
Route::delete('unit', [UnitController::class, 'destroy']);

// -------------------Endpoint Supplier---------------------------------
Route::get('supplier/list', [SupplierController::class, 'listData']);
Route::get('supplier/download_format', [SupplierController::class, 'download_format_import']);
Route::post('supplier/import', [SupplierController::class, 'import']);
Route::apiResource('supplier', SupplierController::class)->except(['destroy']);
Route::delete('supplier', [SupplierController::class, 'destroy']);

// -------------------Endpoint Customer---------------------------------
Route::get('customer/list', [CustomerController::class, 'listData']);
Route::get('customer/download_format', [CustomerController::class, 'download_format_import']);
Route::post('customer/import', [CustomerController::class, 'import']);
Route::get('customer/list', [CustomerController::class, 'listData']);
Route::apiResource('customer', CustomerController::class)->except(['destroy']);
Route::delete('customer', [CustomerController::class, 'destroy']);

// -------------------Endpoint Output-type---------------------------------
Route::get('output_type/list', [OutputTypeController::class, 'listData']);
Route::apiResource('output_type', OutputTypeController::class)->except(['destroy']);
Route::delete('output_type', [OutputTypeController::class, 'destroy']);

// -------------------Endpoint User---------------------------------
Route::apiResource('user', UserController::class)->except(['destroy']);
Route::delete('user', [UserController::class, 'destroy']);

// -------------------Endpoint Item---------------------------------
Route::get('item/download_format', [ItemController::class, 'download_format_import']);
Route::post('item/import', [ItemController::class, 'import']);
Route::get('item/find-by-barcode', [ItemController::class, 'findByBarcode']);
Route::apiResource('item', ItemController::class)->except(['destroy']);
Route::delete('item', [ItemController::class, 'destroy']);

// -------------------Endpoint Sale---------------------------------
Route::get('sale/struk/{sale}', [SaleController::class, 'get_struk']);
Route::apiResource('sale', SaleController::class)->except(['destroy']);
Route::delete('sale', [SaleController::class, 'destroy']);
Route::delete('sale/item', [SaleController::class, 'deleteItem']);

// -------------------Endpoint Purchase---------------------------------
Route::get('purchase/struk/{purchase}', [PurchaseController::class, 'get_struk']);
Route::apiResource('purchase', PurchaseController::class)->except(['destroy']);
Route::delete('purchase', [PurchaseController::class, 'destroy']);
Route::delete('purchase/item', [PurchaseController::class, 'deleteItem']);
