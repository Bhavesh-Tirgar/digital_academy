<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CourseControllerr;
use App\Http\Controllers\ReviewController; // Moved to top

Route::get('/', [UiController::class, 'index']); // Frontend Route

// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // User Management
    Route::resource('/users', UserController::class);

    // Category Management
    Route::resource('/category', CategoryController::class);

    // Blog Management
    Route::resource('/blogs', BlogController::class);

    // Course Management
    Route::resource('/courses', CourseController::class);

    // **Review Management (Admin)**
    Route::get('/reviews', [ReviewAdminController::class, 'index'])->name('admin.reviews.index');
    Route::delete('/reviews/{id}', [ReviewAdminController::class, 'destroy'])->name('admin.reviews.destroy');

    // Material Upload Routes for Courses
    Route::get('/courses/{id}/materials/upload/{plan}', [CourseController::class, 'uploadMaterial'])
        ->name('courses.uploadMaterial'); 

    // Store uploaded materials
    Route::post('/courses/{id}/materials/upload', [CourseController::class, 'storeMaterial'])
        ->name('courses.storeMaterial'); 

    // View Materials Route
    Route::get('/courses/{id}/materials/{plan}', [CourseController::class, 'viewMaterials'])
        ->name('courses.viewMaterials');

    // Bulk Delete Materials
    Route::delete('/courses/materials/bulk-delete', [CourseController::class, 'bulkDelete'])
        ->name('materials.bulkDelete');

    // Add Subject Route
    Route::post('/courses/add-subject', [CourseController::class, 'addSubject'])
        ->name('courses.addSubject');

    // **Delete Material Route**
    Route::delete('/materials/{id}', [CourseController::class, 'deleteMaterial'])
        ->name('materials.delete');
});

// Authentication Routes
Auth::routes(); 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 

// **Plan Selection Routes**
Route::get('/select-plan/{plan}', [CourseControllerr::class, 'selectPlan'])
    ->name('select.plan'); 

// **Frontend Materials View**
Route::get('/frontend/materials/{id}/{plan}', [MaterialController::class, 'show'])
    ->name('frontend.materials');

// **Review Routes (User)**
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index'); // View all reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store'); // Add a review
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy'); // Delete own review

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'You have been logged out.');
})->name('logout');