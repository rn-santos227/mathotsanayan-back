<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AnswerController as AdminAnswerController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CorrectController as AdminCorrectController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\OptionController as AdminOptionController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\Teachers\AnswerController as TeacherAnswerController;
use App\Http\Controllers\Teachers\AuditController as TeachersAuditController;
use App\Http\Controllers\Teachers\AuthController as TeacherAuthController;
use App\Http\Controllers\Teachers\CourseController as TeacherCourseController;
use App\Http\Controllers\Teachers\DashboardController as TeachersDashboardController;
use App\Http\Controllers\Teachers\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teachers\QuestionController as TeachersQuestionController;
use App\Http\Controllers\Teachers\ResultController as TeacherResultController;
use App\Http\Controllers\Teachers\SchoolController as TeacherSchoolController;
use App\Http\Controllers\Teachers\SectionController as TeachersSectionController;
use App\Http\Controllers\Teachers\StudentController as TeachersStudentController;
use App\Http\Controllers\Teachers\TestController as TeachersTestController;

use App\Http\Controllers\Students\AuditController as StudentsAuditController;
use App\Http\Controllers\Students\AnswerController as StudentsAnswerController;
use App\Http\Controllers\Students\AuthController as StudentAuthController;
use App\Http\Controllers\Students\DashboardController as StudentDashboardController;
use App\Http\Controllers\Students\ExamController as StudentsExamController;
use App\Http\Controllers\Students\ModuleController as StudentsModuleController;
use App\Http\Controllers\Students\ResultController as StudentsResultController;
use App\Http\Controllers\Students\SubjectController as StudentsSubjectController;

use App\Http\Controllers\Shared\AuthController as SharedAuthController;
use App\Http\Controllers\Shared\ImageController as SharedImageController;
use App\Http\Controllers\Shared\ModuleController as SharedModuleController;

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
  Route::get('/admin/user', [AdminAuthController::class, 'user']);
  Route::get('/teacher/user', [TeacherAuthController::class, 'user']);
  Route::get('/student/user', [StudentAuthController::class, 'user']);

  Route::get('/logout', [SharedAuthController::class, 'logout']);
  Route::post('/password', [SharedAuthController::class, 'password']);

  Route::group(['middleware' => ['admin']], function() {
    Route::get('/admin/audit', [AdminAuditController::class, 'index'])->name('admin_audit'); 
    Route::get('/admin/audit/search', [AdminAuditController::class, 'search'])->name('admin_audit_search');           
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');
    Route::get('/admin/dashboard/ratio', [AdminDashboardController::class, 'ratio'])->name('admin_dashboard_ratio');
    Route::get('/admin/dashboard/modules', [AdminDashboardController::class, 'modules'])->name('admin_dashboard_modules');

    Route::get('/answers/{id}', [AdminAnswerController::class, 'index'])->name('answers_index');

    Route::get('/admins', [AdminAdminController::class, 'index'])->name('admin_index');
    Route::post('/admins/create', [AdminAdminController::class, 'create'])->name('admin_create');
    Route::patch('/admins/{id}', [AdminAdminController::class, 'update'])->name('admin_update');
    Route::delete('/admins/{id}', [AdminAdminController::class, 'delete'])->name('admin_delete');

    Route::post('/corrects/create/{id}', [AdminCorrectController::class, 'create'])->name('correct_create');
    Route::post('/corrects/{id}', [AdminCorrectController::class, 'update'])->name('correct_update');
    Route::delete('/corrects/{id}', [AdminCorrectController::class, 'delete'])->name('correct_delete');

    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses_index');
    Route::post('/courses/create', [AdminCourseController::class, 'create'])->name('courses_create');
    Route::patch('/courses/{id}', [AdminCourseController::class, 'update'])->name('courses_update');
    Route::delete('/courses/{id}', [AdminCourseController::class, 'delete'])->name('courses_delete');

    Route::get('/modules', [AdminModuleController::class, 'index'])->name('moduldes_index');
    Route::get('/modules/search', [AdminModuleController::class, 'search'])->name('modules_search');
    Route::post('/modules/create', [AdminModuleController::class, 'create'])->name('moduldes_create');
    Route::patch('/modules/{id}', [AdminModuleController::class, 'update'])->name('moduldes_update');
    Route::delete('/modules/{id}', [AdminModuleController::class, 'delete'])->name('moduldes_delete');

    Route::post('/options/create/{id}', [AdminOptionController::class, 'create'])->name('options_create');
    Route::post('/options/{id}', [AdminOptionController::class, 'update'])->name('options_update');
    Route::delete('/options/{id}', [AdminOptionController::class, 'delete'])->name('options_delete');
  
    Route::get('/questions/{id}', [AdminQuestionController::class, 'index'])->name('questions_index');
    Route::post('/questions/create', [AdminQuestionController::class, 'create'])->name('questions_create');
    Route::post('/questions/create-all/{id}', [AdminQuestionController::class, 'createMany'])->name('questions_create_all');
    Route::post('/questions/{id}', [AdminQuestionController::class, 'update'])->name('questions_update');
    Route::patch('/questions/img-remove/{id}', [AdminQuestionController::class, 'removeImage'])->name('questions_remove_image');
    Route::delete('/questions/{id}', [AdminQuestionController::class, 'delete'])->name('questions_delete');

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

    Route::get('/students', [AdminStudentController::class, 'index'])->name('students_index');
    Route::get('/students/search', [AdminStudentController::class, 'search'])->name('students_search');
    Route::post('/students/create', [AdminStudentController::class, 'create'])->name('students_create');
    Route::patch('/students/{id}', [AdminStudentController::class, 'update'])->name('students_update');
    Route::delete('/students/{id}', [AdminStudentController::class, 'delete'])->name('students_delete');

    Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('subjects_index');
    Route::post('/subjects/create', [AdminSubjectController::class, 'create'])->name('subjects_create');
    Route::patch('/subjects/{id}', [AdminSubjectController::class, 'update'])->name('subjects_update');
    Route::delete('/subjects/{id}', [AdminSubjectController::class, 'delete'])->name('subjects_delete');

    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers_index');
    Route::get('/teachers/search', [AdminTeacherController::class, 'search'])->name('teachers_search');
    Route::post('/teachers/create', [AdminTeacherController::class, 'create'])->name('teachers_create');
    Route::patch('/teachers/{id}', [AdminTeacherController::class, 'update'])->name('teachers_update');
    Route::delete('/teachers/{id}', [AdminTeacherController::class, 'delete'])->name('teachers_delete');

    Route::get('/accounts', [AdminUserController::class, 'index'])->name('accounts_index');
    Route::patch('/accounts/reset/{id}', [AdminUserController::class, 'reset'])->name('accounts_password_reset');
    Route::delete('/accounts/{id}', [AdminUserController::class, 'delete'])->name('accounts_delete');

    Route::post('/test/{id}', [AdminTestController::class, 'submit'])->name('admin_test');
  });

  Route::group(['middleware' => ['teacher']], function() {
    Route::prefix('teacher')->group(function () {
      Route::get('/audit', [TeachersAuditController::class, 'index'])->name('teacher_audit');         
      Route::get('/dashboard', [TeachersDashboardController::class, 'index'])->name('teacher_dashboard');
      Route::get('/dashboard/ratio', [TeachersDashboardController::class, 'ratio'])->name('teacher_dashboard_ratio');
      Route::get('/dashboard/modules', [TeachersDashboardController::class, 'modules'])->name('teacher_dashboard_modules');

      Route::get('/answers/{id}', [TeacherAnswerController::class, 'index'])->name('teacher_answers_index');

      Route::get('/courses', [TeacherCourseController::class, 'index'])->name('teachers_courses_index');
      Route::post('/courses/create', [TeacherCourseController::class, 'create'])->name('teachers_courses_create');
      Route::patch('/courses/{id}', [TeacherCourseController::class, 'update'])->name('teachers_courses_update');
      Route::delete('/courses/{id}', [TeacherCourseController::class, 'delete'])->name('teachers_courses_delete');

      Route::get('/questions/{id}', [TeachersQuestionController::class, 'index'])->name('teachers_questions_index');
      
      Route::get('/modules', [TeacherModuleController::class, 'index'])->name('teachers_modules_index');
      Route::get('/modules/search', [TeacherModuleController::class, 'index'])->name('teachers_modules_search');

      Route::get('/results', [TeacherResultController::class, 'index'])->name('teacher_results_index');
      Route::get('/results/search', [TeacherResultController::class, 'search'])->name('teacher_result_search');
      Route::patch('/results/{id}', [TeacherResultController::class, 'invalidate'])->name('teacher_results_invalidate');

      Route::get('/schools', [TeacherSchoolController::class, 'index'])->name('teachers_schools_index');

      Route::get('/sections', [TeachersSectionController::class, 'index'])->name('teachers_sections_index');
      Route::post('/sections/create', [TeachersSectionController::class, 'create'])->name('teachers_sections_create');
      Route::patch('/sections/{id}', [TeachersSectionController::class, 'update'])->name('teachers_sections_update');
      Route::delete('/sections/{id}', [TeachersSectionController::class, 'delete'])->name('teachers_ssections_delete');

      Route::get('/students', [TeachersStudentController::class, 'index'])->name('teachers_students_index');
      Route::get('/students/search', [TeachersStudentController::class, 'search'])->name('teachers_students_search');
      Route::post('/students/create', [TeachersStudentController::class, 'create'])->name('teachers_students_create');
      Route::patch('/students/reset/{id}', [TeachersStudentController::class, 'reset'])->name('teachers_students_reset');
      Route::patch('/students/{id}', [TeachersStudentController::class, 'update'])->name('teachers_students_update');
      Route::delete('/students/{id}', [TeachersStudentController::class, 'delete'])->name('teachers_students_delete');

      Route::post('/test/{id}', [TeachersTestController::class, 'submit'])->name('teachers_test');
    });
  });

  Route::group(['middleware' => ['student']], function() {
    Route::prefix('student')->group(function () {
      Route::get('/audit', [StudentsAuditController::class, 'index'])->name('student_audit');         
      Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student_dashboard');
      Route::get('/dashboard/ratio', [StudentDashboardController::class, 'ratio'])->name('student_dashboard_ratio');
      Route::get('/dashboard/modules', [StudentDashboardController::class, 'modules'])->name('student_dashboard_modules');

      Route::get('/answers/{id}', [StudentsAnswerController::class, 'index'])->name('student_answers_index');    
      Route::get('/modules/{id}', [StudentsModuleController::class, 'index'])->name('student_moduldes_sindex');
      Route::get('/questions/{id}', [StudentsExamController::class, 'questions'])->name('student_exam_question');
      Route::get('/results/{id}', [StudentsResultController::class, 'index'])->name('student_results_index');
      Route::get('/submit/{id}', [StudentsExamController::class, 'submit'])->name('student_exam_submit');
      Route::get('/subjects', [StudentsSubjectController::class, 'index'])->name('student_subjects_index');
       
      Route::post('/skip/{id}', [StudentsExamController::class, 'skip'])->name('student_exam_skip');
      Route::post('/answer/{id}', [StudentsExamController::class, 'answer'])->name('student_exam_answer');
    });
  });

  Route::post('/image', [SharedImageController::class, 'image'])->name('image');
  Route::get('/modules/check/{id}', [SharedModuleController::class, 'check'])->name('module_check');
});

//public access
Route::post('/admin/login', [AdminAuthController::class, 'index'])->name('login_admin');
Route::post('/teacher/login', [TeacherAuthController::class, 'index'])->name('login_teacher');
Route::post('/student/login', [StudentAuthController::class, 'index'])->name('login_student');

Route::get('/auth', [SharedAuthController::class, 'auth'])->name('auth');