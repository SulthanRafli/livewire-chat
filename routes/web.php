<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Chat\Chat;
use App\Http\Livewire\Chat\Index;
use App\Http\Livewire\Users;
use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', Index::class)->name('chat.index');
    Route::get('/chat/{query}', Chat::class)->name('chat');
    Route::get('/users', Users::class)->name('users');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home', [AdminController::class, 'index'])->name('home');;

    Route::group(['prefix' => 'department'], function () {
        Route::get('/', [AdminController::class, 'department'])->name('department');
        Route::get('/add', [AdminController::class, 'createDepartment'])->name('department.create');
        Route::get('/{id}', [AdminController::class, 'editDepartment'])->name('department.edit');
        Route::post('/', [AdminController::class, 'saveDepartment'])->name('department.save');
        Route::post('/{id}', [AdminController::class, 'updateDepartment'])->name('department.update');
        Route::delete('/{id}', [AdminController::class, 'deleteDepartment'])->name('department.delete');
    });

    Route::group(['prefix' => 'topic'], function () {
        Route::get('/', [AdminController::class, 'topic'])->name('topic');
        Route::get('/add', [AdminController::class, 'createTopic'])->name('topic.create');
        Route::get('/{id}', [AdminController::class, 'editTopic'])->name('topic.edit');
        Route::post('/', [AdminController::class, 'saveTopic'])->name('topic.save');
        Route::post('/{id}', [AdminController::class, 'updateTopic'])->name('topic.update');
        Route::delete('/{id}', [AdminController::class, 'deleteTopic'])->name('topic.delete');
    });

    Route::group(['prefix' => 'student'], function () {
        Route::get('/', [AdminController::class, 'student'])->name('student');
        Route::get('/add', [AdminController::class, 'createStudent'])->name('student.create');
        Route::get('/{id}', [AdminController::class, 'editStudent'])->name('student.edit');
        Route::post('/', [AdminController::class, 'saveStudent'])->name('student.save');
        Route::post('/{id}', [AdminController::class, 'updateStudent'])->name('student.update');
        Route::delete('/{id}', [AdminController::class, 'deleteStudent'])->name('student.delete');
    });

    Route::group(['prefix' => 'lecturer'], function () {
        Route::get('/', [AdminController::class, 'lecturer'])->name('lecturer');
        Route::get('/add', [AdminController::class, 'createLecturer'])->name('lecturer.create');
        Route::get('/{id}', [AdminController::class, 'editLecturer'])->name('lecturer.edit');
        Route::post('/', [AdminController::class, 'saveLecturer'])->name('lecturer.save');
        Route::post('/{id}', [AdminController::class, 'updateLecturer'])->name('lecturer.update');
        Route::delete('/{id}', [AdminController::class, 'deleteLecturer'])->name('lecturer.delete');
    });
});
