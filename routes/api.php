<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::group(['middleware' => ['auth:sanctum','admin']], function() {
    Route::get('/admins', [AdminController::class, 'index'])->name('index');
    Route::post('/admins/create', [AdminController::class, 'create'])->name('create');
    Route::patch('/admins/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/admins/{id}', [AdminController::class, 'delete'])->name('delete');

    Route::get('/courses', [CourseController::class, 'index'])->name('index');
    Route::post('/courses/create', [CourseController::class, 'create'])->name('create');
    Route::patch('/courses/{id}', [CourseController::class, 'update'])->name('update');
    Route::delete('/courses/{id}', [CourseController::class, 'delete'])->name('delete');

    Route::get('/modules', [ModuleController::class, 'index'])->name('index');
    Route::post('/modules/create', [ModuleController::class, 'create'])->name('create');
    Route::patch('/modules/{id}', [ModuleController::class, 'update'])->name('update');
    Route::delete('/modules/{id}', [ModuleController::class, 'delete'])->name('delete');

    Route::get('/schools', [SchoolController::class, 'index'])->name('index');
    Route::post('/schools/create', [SchoolController::class, 'create'])->name('create');
    Route::patch('/schools/{id}', [SchoolController::class, 'update'])->name('update');
    Route::delete('/schools/{id}', [SchoolController::class, 'delete'])->name('delete');

    Route::get('/sections', [SectionController::class, 'index'])->name('index');
    Route::post('/sections/create', [SectionController::class, 'create'])->name('create');
    Route::patch('/sections/{id}', [SectionController::class, 'update'])->name('update');
    Route::delete('/sections/{id}', [SectionController::class, 'delete'])->name('delete');

    Route::get('/students', [StudentController::class, 'index'])->name('index');
    Route::post('/students/create', [StudentController::class, 'create'])->name('create');
    Route::patch('/students/{id}', [StudentController::class, 'update'])->name('update');
    Route::delete('/students/{id}', [StudentController::class, 'delete'])->name('delete');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('index');
    Route::post('/teachers/create', [TeacherController::class, 'create'])->name('create');
    Route::patch('/teachers/{id}', [TeacherController::class, 'update'])->name('update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'delete'])->name('delete');
});

Route::group(['middleware' => ['auth:sanctum','admin','teacher']], function() {

});

Route::group(['middleware' => ['auth:sanctum','admin','teacher','student']], function() {

});

//public access
Route::post('/admin', [AuthController::class, 'admin'])->name('admin');
Route::post('/teacher', [AuthController::class, 'teacher'])->name('teacher');
Route::post('/student', [AuthController::class, 'student'])->name('student');
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');