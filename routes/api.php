<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorrectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;

use App\Http\Controllers\Teachers\AuditController as TeachersAuditController;
use App\Http\Controllers\Teachers\DashboardController as TeachersDashboardController;
use App\Http\Controllers\Teachers\SectionController as TeachersSectionController;
use App\Http\Controllers\Teachers\StudentController as TeachersStudentController;

use App\Http\Controllers\Students\AuditController as StudentsAuditController;
use App\Http\Controllers\Students\ResultController as StudentsResultController;

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
    Route::get('/admin/audit', [AdminAuditController::class, 'admin'])->name('admin_audit');      
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');
    
    Route::get('/answers/{id}', [AnswerController::class, 'index'])->name('answers_index');

    Route::get('/admins', [AdminAdminController::class, 'index'])->name('admin_index');
    Route::post('/admins/create', [AdminAdminController::class, 'create'])->name('admin_create');
    Route::patch('/admins/{id}', [AdminAdminController::class, 'update'])->name('admin_update');
    Route::delete('/admins/{id}', [AdminAdminController::class, 'delete'])->name('admin_delete');

    Route::post('/corrects/create/{id}', [CorrectController::class, 'create'])->name('correct_create');
    Route::post('/corrects/{id}', [CorrectController::class, 'update'])->name('correct_update');
    Route::delete('/corrects/{id}', [CorrectController::class, 'delete'])->name('correct_delete');

    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses_index');
    Route::post('/courses/create', [AdminCourseController::class, 'create'])->name('courses_create');
    Route::patch('/courses/{id}', [AdminCourseController::class, 'update'])->name('courses_update');
    Route::delete('/courses/{id}', [AdminCourseController::class, 'delete'])->name('courses_delete');

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

    Route::get('/results', [AdminResultController::class, 'index'])->name('results_index');
    Route::get('/results/search', [AdminResultController::class, 'search'])->name('result_search');
    Route::patch('/results/{id}', [AdminResultController::class, 'invalidate'])->name('results_invalidate');

    Route::get('/schools', [AdminSchoolController::class, 'index'])->name('schools_index');
    Route::post('/schools/create', [AdminSchoolController::class, 'create'])->name('schools_create');
    Route::patch('/schools/{id}', [AdminSchoolController::class, 'update'])->name('schools_update');
    Route::delete('/schools/{id}', [AdminSchoolController::class, 'delete'])->name('schools_delete');

    Route::get('/sections', [AdminSectionController::class, 'index'])->name('sections_index');
    Route::post('/sections/create', [AdminSectionController::class, 'create'])->name('sections_create');
    Route::patch('/sections/{id}', [AdminSectionController::class, 'update'])->name('sections_update');
    Route::delete('/sections/{id}', [AdminSectionController::class, 'delete'])->name('sections_delete');

    Route::get('/students', [StudentController::class, 'index'])->name('students_index');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students_search');
    Route::post('/students/create', [StudentController::class, 'create'])->name('students_create');
    Route::patch('/students/{id}', [StudentController::class, 'update'])->name('students_update');
    Route::delete('/students/{id}', [StudentController::class, 'delete'])->name('students_delete');

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects_index');
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
    Route::get('/teacher/audit', [TeachersAuditController::class, 'teacher'])->name('teacher_audit');         
    Route::get('/teacher/dashboard', [TeachersDashboardController::class, 'index'])->name('teacher_dashboard');
    
    Route::prefix('teachers')->group(function () {
      Route::get('/sections', [TeachersSectionController::class, 'index'])->name('teachers_sections_index');

      Route::get('/students', [TeachersStudentController::class, 'index'])->name('teachers_students_index');
    });
  });

  Route::group(['middleware' => ['student']], function() {
    Route::prefix('student')->group(function () {
      Route::get('/answers/{id}', [AnswerController::class, 'student'])->name('answers_student');
      Route::get('/audit', [StudentsAuditController::class, 'student'])->name('student_audit');         
      Route::get('/modules/{id}', [ModuleController::class, 'student'])->name('moduldes_student_index');
      Route::get('/subjects', [SubjectController::class, 'student'])->name('subjects_student_index');
      Route::get('/questions/{id}', [ExamController::class, 'questions'])->name('exam_question');
      Route::get('/submit/{id}', [ExamController::class, 'submit'])->name('exam_submit');
      Route::post('/skip/{id}', [ExamController::class, 'skip'])->name('exam_skip');
      Route::post('/answer/{id}', [ExamController::class, 'answer'])->name('exam_answer');

      Route::get('/results/{id}', [StudentsResultController::class, 'index'])->name('results_student_index');
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