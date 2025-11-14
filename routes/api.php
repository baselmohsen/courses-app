<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AuthController;

// Import Middleware (لو عامل Middleware اسمه RoleMiddleware)
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/courses', [CourseController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {

    // Categories - Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('/categories',CategoryController::class);
    });

    // Courses
    Route::post('/courses', [CourseController::class, 'store'])->middleware('role:admin,instructor');
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('role:admin,instructor');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('role:admin');

    // Lessons
    Route::post('/lessons', [LessonController::class, 'store'])->middleware('role:admin,instructor');
    Route::delete('/lessons/{id}', [LessonController::class, 'destroy'])->middleware('role:admin,instructor');

    // Enrollment - Student only
    Route::post('/enroll', [EnrollmentController::class, 'store'])->middleware('role:student');
});
