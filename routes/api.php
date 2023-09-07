<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ModuleConroller;
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
});

Route::group(['middleware' => ['auth:sanctum','admin']], function() {

});

Route::group(['middleware' => ['auth:sanctum','admin','teacher']], function() {

});

Route::group(['middleware' => ['auth:sanctum','admin','teacher','student']], function() {

});

//public access
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');