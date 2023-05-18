<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DocumentController;

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
})->middleware('auth');

Route::controller(CompanyController::class)->group(function () {
    Route::get('/company', 'index')->name('company.index');
    Route::get('/company/create', 'create')->name('company.create');
    Route::post('/company', 'store')->name('company.store');
    Route::get('/company/{id}', 'show');
    Route::get('/company/edit/{company}', 'edit')->name('company.edit');
    Route::patch('/company/{company}', 'update')->name('company.update');
    Route::delete('/company/delete/{company}', 'delete')->name('company.delete');

});

Route::controller(ItemController::class)->group(function () {
    Route::get('/item','index')->name('item.index');
    Route::get('/item/form','form')->name('item.form');
    Route::delete('/item/delete/{item}', 'delete')->name('item.delete');
});

Route::controller(DocumentController::class)->middleware('auth')->group(function () {
    Route::get('/document/{type}','index')->name('document.index');
    Route::get('/document/{type}/create', 'create')->name('document.create');
    Route::post('/document','store')->name('document.store');
    Route::get('/document/{id}', 'show');
    Route::get('/document/edit/{document}', 'edit')->name('document.edit');
    Route::patch('/document/{document}', 'update')->name('document.update');
    Route::delete('/document/delete/{document}', 'delete')->name('document.delete'); 
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
