<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorrectController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;

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
    Route::get('/user/{type}', [AuthController::class, 'user']);
    Route::post('/password/{id}', [UserController::class, 'password']);

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin_dashboard');
        
        Route::get('/answers/{id}', [AnswerController::class, 'index'])->name('answers_index');

        Route::get('/admins', [AdminController::class, 'index'])->name('admin_index');
        Route::post('/admins/create', [AdminController::class, 'create'])->name('admin_create');
        Route::patch('/admins/{id}', [AdminController::class, 'update'])->name('admin_update');
        Route::delete('/admins/{id}', [AdminController::class, 'delete'])->name('admin_delete');

        Route::post('/corrects/create/{id}', [CorrectController::class, 'create'])->name('correct_create');
        Route::post('/corrects/{id}', [CorrectController::class, 'update'])->name('correct_update');
        Route::delete('/corrects/{id}', [CorrectController::class, 'delete'])->name('correct_delete');
    
        Route::get('/courses', [CourseController::class, 'index'])->name('courses_index');
        Route::get('/courses/search', [CourseController::class, 'search'])->name('courses_search');
        Route::post('/courses/create', [CourseController::class, 'create'])->name('courses_create');
        Route::patch('/courses/{id}', [CourseController::class, 'update'])->name('courses_update');
        Route::delete('/courses/{id}', [CourseController::class, 'delete'])->name('courses_delete');
    
        Route::get('/modules', [ModuleController::class, 'index'])->name('moduldes_index');
        Route::get('/modules/search', [ModuleController::class, 'search'])->name('modules_search');
        Route::post('/modules/create', [ModuleController::class, 'create'])->name('moduldes_create');
        Route::patch('/modules/{id}', [ModuleController::class, 'update'])->name('moduldes_update');
        Route::delete('/modules/{id}', [ModuleController::class, 'delete'])->name('moduldes_delete');
    
        Route::post('/options/create/{id}', [OptionController::class, 'create'])->name('options_create');
        Route::post('/options/{id}', [OptionController::class, 'update'])->name('options_update');
        Route::delete('/options/{id}', [OptionController::class, 'delete'])->name('options_delete');
    
        Route::get('/questions', [QuestionController::class, 'index'])->name('questions_index');
        Route::post('/questions/create', [QuestionController::class, 'create'])->name('questions_create');
        Route::post('/questions/create-all/{id}', [QuestionController::class, 'createMany'])->name('questions_createAll');
        Route::post('/questions/{id}', [QuestionController::class, 'update'])->name('questions_update');
        Route::delete('/questions/{id}', [QuestionController::class, 'delete'])->name('questions_delete');
    
        Route::get('/results', [ResultController::class, 'index'])->name('results_index');
        Route::get('/results/search', [ResultController::class, 'search'])->name('result_search');
        Route::patch('/results/{id}', [ResultController::class, 'invalidate'])->name('results_invalidate');

        Route::get('/schools', [SchoolController::class, 'index'])->name('schools_index');
        Route::get('/schools/search', [SchoolController::class, 'search'])->name('school_search');
        Route::post('/schools/create', [SchoolController::class, 'create'])->name('schools_create');
        Route::patch('/schools/{id}', [SchoolController::class, 'update'])->name('schools_update');
        Route::delete('/schools/{id}', [SchoolController::class, 'delete'])->name('schools_delete');
    
        Route::get('/sections', [SectionController::class, 'index'])->name('sections_index');
        Route::get('/sections/search', [SectionController::class, 'search'])->name('sections_search');
        Route::post('/sections/create', [SectionController::class, 'create'])->name('sections_create');
        Route::patch('/sections/{id}', [SectionController::class, 'update'])->name('sections_update');
        Route::delete('/sections/{id}', [SectionController::class, 'delete'])->name('sections_delete');
    
        Route::get('/students', [StudentController::class, 'index'])->name('students_index');
        Route::get('/students/search', [StudentController::class, 'search'])->name('students_search');
        Route::post('/students/create', [StudentController::class, 'create'])->name('students_create');
        Route::patch('/students/{id}', [StudentController::class, 'update'])->name('students_update');
        Route::delete('/students/{id}', [StudentController::class, 'delete'])->name('students_delete');
    
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects_index');
        Route::get('/subjects/search', [SubjectController::class, 'search'])->name('subjects_search');
        Route::post('/subjects/create', [SubjectController::class, 'create'])->name('subjects_create');
        Route::patch('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects_update');
        Route::delete('/subjects/{id}', [SubjectController::class, 'delete'])->name('subjects_delete');
    
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers_index');
        Route::get('/teachers/search', [TeacherController::class, 'search'])->name('teachers_search');
        Route::post('/teachers/create', [TeacherController::class, 'create'])->name('teachers_create');
        Route::patch('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers_update');
        Route::delete('/teachers/{id}', [TeacherController::class, 'delete'])->name('teachers_delete');
    
        Route::get('/accounts', [UserController::class, 'index'])->name('accounts_index');
        Route::delete('/accounts/{id}', [UserController::class, 'delete'])->name('accounts_delete');

        Route::post('/test/{id}', [TestController::class, 'submit'])->name('admin_test');
    });

    Route::group(['middleware' => ['teacher']], function() {
        Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])->name('teacher_dashboard');
        Route::prefix('teachers')->group(function () {

        });
    });

    Route::group(['middleware' => ['student']], function() {
        Route::prefix('student')->group(function () {
            Route::get('/answers/{id}', [AnswerController::class, 'student'])->name('answers_student');
            Route::get('/dashboard', [DashboardController::class, 'student'])->name('student_dashboard');
            Route::get('/modules/{id}', [ModuleController::class, 'student'])->name('moduldes_student_index');
            Route::get('/results/{id}', [ResultController::class, 'student'])->name('results_student_index');
            Route::get('/subjects', [SubjectController::class, 'student'])->name('subjects_student_index');
            Route::get('/questions/{id}', [ExamController::class, 'questions'])->name('exam_question');
            Route::get('/submit/{id}', [ExamController::class, 'submit'])->name('exam_submit');
            Route::post('/skip/{id}', [ExamController::class, 'skip'])->name('exam_skip');
            Route::post('/answer/{id}', [ExamController::class, 'answer'])->name('exam_answer');
        });
    });

    Route::post('/image', [ImageController::class, 'image'])->name('image');
    Route::get('/modules/check/{id}', [ModuleController::class, 'check'])->name('moduldes_check');
});

//public access
Route::post('/admin', [AuthController::class, 'admin'])->name('admin');
Route::post('/teacher', [AuthController::class, 'teacher'])->name('teacher');
Route::post('/student', [AuthController::class, 'student'])->name('student');
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');