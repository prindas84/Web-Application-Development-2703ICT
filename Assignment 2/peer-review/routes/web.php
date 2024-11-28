<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StudentAssessmentController;
use Illuminate\Support\Facades\Route;

// Home route (loads the homepage view)
Route::get('/', function () {
    return view('index');
})->name('home');

// Dashboard route, requires authentication and email verification
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Grouped routes that require user authentication
Route::middleware('auth')->group(function () {

    // Profile routes for editing, updating, and deleting the user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Resource routes for managing courses (CRUD functionality)
    Route::resource('course', CourseController::class);
    Route::post('/course/upload', [CourseController::class, 'uploadCourseData'])->name('course.uploadCourseData');

    // Resource routes for managing assessments (CRUD functionality)
    Route::resource('assessment', AssessmentController::class);
    Route::post('/assessment/{assessment_id}/assign-groups', [AssessmentController::class, 'assignGroups'])->name('assessment.groups.assign');

    // Resource routes for managing reviews (CRUD functionality)
    Route::resource('review', ReviewController::class);

    // Route to view a students assessment and update score
    Route::get('student-assessment/{assessment_id}/{student_id}', [StudentAssessmentController::class, 'show'])->name('student.assessment.show');
    Route::put('/student-assessment/{assessment_id}/{student_id}/update-score', [StudentAssessmentController::class, 'updateScore'])->name('student.assessment.updateScore');
    
    // Route to add a teacher to a course
    Route::post('/course/{id}/add-teacher', [CourseController::class, 'addTeacher'])->name('course.addTeacher');
    
    // Route to remove a teacher from a course
    Route::post('/course/{id}/remove-teacher', [CourseController::class, 'removeTeacher'])->name('course.removeTeacher');
    
    // Route to enrol a student in a course
    Route::post('/course/{id}/enroll-student', [CourseController::class, 'enrollStudent'])->name('course.enrollStudent');
    
    // Route to unenrol a student from a course
    Route::post('/course/{id}/unenroll-student', [CourseController::class, 'unenrollStudent'])->name('course.unenrollStudent');
});

// Load authentication routes (e.g., login, register)
require __DIR__.'/auth.php';
