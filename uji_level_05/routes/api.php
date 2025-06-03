<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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

// Untuk Menampilkan Seluruh Data Buku - GET
Route::get('/books', [BookController::class, 'index'])->name('book.index');
// Untuk Mengirim Data Buku - POST
Route::post('/books', [BookController::class, 'store'])->name('book.store');
// Untuk Mengedit Data Buku - PUT
Route::put('/books/{id}', [BookController::class, 'update'])->name('book.update');
// Untuk Menghapus Data Buku - DELETE
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('book.destroy');
// Untuk Menampilkan Data Buku Menurut ID - GET (ID)
Route::get('/books/{id}', [BookController::class, 'show'])->name('book.show');
