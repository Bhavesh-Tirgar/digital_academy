<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UiController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\LectureCommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LiveLectureController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\QuizController;

// 1. Home & Public Navigation
Route::get('/', [UiController::class, 'index'])->name('frontend.home');

// 2. Course Catalog & Details
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.catalog');
Route::get('/courses/{id}', [CourseCatalogController::class, 'show'])->name('courses.detail');
Route::post('/comments', [LectureCommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{id}', [LectureCommentController::class, 'destroy'])->name('comments.destroy');

// 3. Transparent Membership & Subscription Model (Student Subscription vs Instructor Pro)
Route::get('/pricing', [SubscriptionController::class, 'index'])->name('pricing.index');
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/subscribe/{tier?}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
Route::post('/subscribe', [SubscriptionController::class, 'process'])->name('subscription.process');

// 4. Purchase & Enrollment Flow (Auth Gated)
Route::get('/courses/{id}/checkout', [EnrollmentController::class, 'checkout'])->name('courses.checkout');
Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'process'])->name('courses.enroll');

// 4. Live Lectures & In-Platform Native Virtual Classroom
Route::get('/schedule', [LiveLectureController::class, 'index'])->name('schedule.index');
Route::get('/live-classroom/{id}', [LiveLectureController::class, 'classroom'])->name('live.classroom');
Route::get('/live-classroom/{id}/messages', [LiveLectureController::class, 'getMessages'])->name('live.messages');
Route::post('/live-classroom/{id}/messages', [LiveLectureController::class, 'postMessage'])->name('live.messages.store');
Route::post('/live-classroom/{id}/poll-vote', [LiveLectureController::class, 'votePoll'])->name('live.poll.vote');
Route::post('/live-classroom/{id}/poll-create', [LiveLectureController::class, 'createPoll'])->name('live.poll.create');
Route::post('/live-classroom/{id}/signal', [LiveLectureController::class, 'sendSignal'])->name('live.signal.send');
Route::get('/live-classroom/{id}/signal', [LiveLectureController::class, 'getSignal'])->name('live.signal.get');
Route::post('/live-classroom/{id}/raise-hand', [LiveLectureController::class, 'raiseHand'])->name('live.hand.raise');
Route::post('/live-classroom/{id}/dismiss-hand/{handId}', [LiveLectureController::class, 'dismissHand'])->name('live.hand.dismiss');

// 5. E-Book Library Slot
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{id}/download', [EbookController::class, 'download'])->name('ebooks.download');

// 6. Teacher / Instructor Marketplace (80/20 platform fee)
Route::get('/become-instructor', [InstructorController::class, 'landing'])->name('become.instructor');
Route::get('/teach', function() { return redirect()->route('become.instructor'); });
Route::get('/subjects', [App\Http\Controllers\SubjectController::class, 'index'])->name('subjects.index');
Route::get('/instructor/register', [InstructorController::class, 'showRegister'])->name('instructor.register.form');
Route::post('/instructor/register', [InstructorController::class, 'register'])->name('instructor.register');
Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
Route::post('/instructor/courses', [InstructorController::class, 'storeCourse'])->name('instructor.courses.store');
Route::post('/instructor/courses/{id}/complete', [InstructorController::class, 'markCourseComplete'])->name('instructor.course.complete');
Route::post('/instructor/lectures', [InstructorController::class, 'storeLecture'])->name('instructor.lectures.store');

// 7. Notifications
Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

// 8. Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/blogs', BlogController::class);
    Route::resource('/courses', CourseController::class);
    Route::get('/reviews', [ReviewAdminController::class, 'index'])->name('admin.reviews.index');
    Route::get('/reviews/{id}/edit', [ReviewAdminController::class, 'edit'])->name('admin.reviews.edit');
    Route::put('/reviews/{id}', [ReviewAdminController::class, 'update'])->name('admin.reviews.update');
    Route::delete('/reviews/{id}', [ReviewAdminController::class, 'destroy'])->name('admin.reviews.destroy');
});

// 8b. Course Material & Syllabus Management (Instructors & Admins)
Route::middleware(['auth'])->group(function () {
    Route::get('/courses/{id}/materials/upload/{plan?}', [CourseController::class, 'uploadMaterial'])->name('courses.uploadMaterial'); 
    Route::post('/courses/{id}/materials/upload', [CourseController::class, 'storeMaterial'])->name('courses.storeMaterial'); 
    Route::get('/courses/{id}/materials/{plan?}', [CourseController::class, 'viewMaterials'])->name('courses.viewMaterials');
    Route::delete('/courses/materials/bulk-delete', [CourseController::class, 'bulkDelete'])->name('materials.bulkDelete');
    Route::post('/courses/add-subject', [CourseController::class, 'addSubject'])->name('courses.addSubject');
    Route::delete('/materials/{id}', [CourseController::class, 'deleteMaterial'])->name('materials.delete');
});

// 9. Authentication Routes
Auth::routes(); 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard')->middleware('auth'); 
Route::get('/student-dashboard', function() { return redirect()->route('student.dashboard'); }); 

// 10. Course Learning Workspace & Full Materials Access (No Arbitrary Plan Restrictions)
Route::get('/select-plan/{plan?}', [CourseCatalogController::class, 'selectPlan'])->name('select.plan'); 
Route::get('/frontend/materials/{id}/{plan?}', [MaterialController::class, 'show'])->name('frontend.materials');

// 11. Course Progress Tracking & Verified Digital Certificates
Route::post('/courses/{id}/progress', [CourseProgressController::class, 'updateProgress'])->name('courses.progress.update');
Route::get('/courses/{id}/certificate', [CourseProgressController::class, 'showCertificate'])->name('courses.certificate');
Route::get('/verify-certificate/{certId}', [CourseProgressController::class, 'verifyCertificate'])->name('certificate.verify');

// 11. Review Routes
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// 12. Academic Quizzes & Certification Exams
Route::get('/courses/{id}/quiz/{quizId?}', [QuizController::class, 'show'])->name('courses.quiz.show');
Route::post('/courses/{id}/quiz/{quizId}/submit', [QuizController::class, 'submit'])->name('courses.quiz.submit');

// 13. Q&A Community Doubt Resolution & Calendar Sync
Route::post('/comments/{id}/accept', [LectureCommentController::class, 'acceptAnswer'])->name('comments.accept');
Route::post('/comments/{id}/like', [LectureCommentController::class, 'toggleLike'])->name('comments.like');
Route::get('/schedule/{id}/calendar', [LiveLectureController::class, 'downloadCalendarEvent'])->name('schedule.calendar.ics');

// 14. Professor AI Code Debugger & Socratic Sandbox Lab
Route::get('/sandbox', [App\Http\Controllers\AiDebuggerController::class, 'index'])->name('ai.sandbox');
Route::post('/api/ai/debug-code', [App\Http\Controllers\AiDebuggerController::class, 'debug'])->name('api.ai.debug');

// 15. Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'You have been successfully logged out.');
})->name('logout');
