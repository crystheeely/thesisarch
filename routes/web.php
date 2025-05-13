<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\{
    ProfileController,
    DashboardController,
    ThesisController,
    AboutController,
    ApprovedController,
    CommentController,
    AdminProfileController,
    ReportController,
    SettingsController,
    NotificationController,
    CustomNotificationController,
    AdminDashboardController,
    FacultyDashboardController,
    StudentDashboardController,
    Auth\RegisteredUserController,
    ThesisRequirementController,
    Auth\LoginController,
    homeController
};

// Public Route
// Route::get('/', fn() => view('welcome'));
Route::get('/', [homeController::class, 'index']);


// Static Page
Route::get('/about-us', [AboutController::class, 'index'])->name('about-us');

// Authentication
require __DIR__ . '/auth.php';

// General Dashboard
Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        'faculty' => redirect()->route('faculty.dashboard'),
        default => abort(403, 'Unauthorized access.')
    };
})->middleware(['auth'])->name('dashboard');

// Awaiting Approval
Route::middleware('auth')->get('/student/awaiting-approval', fn() => view('student.awaiting-approval'))->name('student.awaiting-approval');

// QR Test
Route::get('/test-qr', fn() => QrCode::size(200)->generate('https://example.com'));

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', [AdminDashboardController::class, 'listStudents'])->name('students');
    Route::get('/students/graduated', [AdminDashboardController::class, 'graduatedStudents'])->name('students.graduated');
    Route::get('/students/create', [AdminDashboardController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
    Route::delete('/student/{id}', [AdminDashboardController::class, 'deleteStudent'])->name('deleteStudent');
    Route::patch('/approve/{id}', [AdminDashboardController::class, 'approveStudent'])->name('approve');
    Route::get('/faculty', [AdminDashboardController::class, 'facultyList'])->name('faculty.index');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
});

//Route::post('/admin/students/store', [AdminDashboardController::class, 'storeStudent'])->name('admin.students.store');
Route::get('admin/students', [AdminDashboardController::class, 'studentList'])->name('admin.students.index');
Route::get('/admin/students', [AdminDashboardController::class, 'students'])->name('admin.students');
Route::get('/admin/faculty', [AdminDashboardController::class, 'facultyList'])->name('admin.faculty');


Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'destroy'])->name('admin.users.destroy');
Route::get('/admin/students', [AdminDashboardController::class, 'students'])->name('admin.students');



// Faculty Routes
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyDashboardController::class, 'index'])->name('dashboard');
});

Route::get('faculty/dashboard', [FacultyDashboardController::class, 'index'])->name('faculty.dashboard');
Route::patch('/faculty/approve/{id}', [FacultyDashboardController::class, 'approveStudent'])
    ->name('faculty.approve');
Route::delete('/faculty/delete-student/{id}', [FacultyDashboardController::class, 'deleteStudent'])->name('faculty.deleteStudent');

// Student Dashboard
Route::middleware(['auth'])->get('/student/dashboard', [StudentDashboardController::class, 'showDashboard'])->name('student.dashboard');

// Profile Routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::get('/edit-info', [ProfileController::class, 'editPersonal'])->name('editPersonal');
    Route::post('/update-info', [ProfileController::class, 'updatePersonal'])->name('updatePersonal');
    Route::patch('/photo', [ProfileController::class, 'updatePhoto'])->name('updatePhoto');
});

// Settings Routes
Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::patch('/update-email', [SettingsController::class, 'updateEmail'])->name('updateEmail');
    Route::patch('/update-password', [SettingsController::class, 'updatePassword'])->name('updatePassword');
    Route::delete('/delete-account', [SettingsController::class, 'deleteAccount'])->name('deleteAccount');
});

// Thesis Routes
Route::middleware('auth')->prefix('theses')->name('theses.')->group(function () {
    Route::get('/', [ThesisController::class, 'index'])->name('index');
    Route::get('/create', [ThesisController::class, 'create'])->name('create');
    Route::post('/', [ThesisController::class, 'store'])->name('store');
    Route::get('/{id}', [ThesisController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ThesisController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ThesisController::class, 'update'])->name('update');
    Route::put('/{id}/uploadNewRequirements', [ThesisController::class, 'uploadNewRequirements'])->name('uploadNewRequirements');
    Route::put('replaceThesisFile/{id}', [ThesisController::class, 'replaceThesisFile'])->name('replaceThesisFile');
    Route::put('replaceRequirementFile/{id}', [ThesisController::class, 'replaceRequirementFile'])->name('replaceRequirementFile');
    Route::delete('/{id}', [ThesisController::class, 'destroy'])->name('destroy');
    Route::get('/download/{id}', [ThesisController::class, 'download'])->name('download');

    Route::patch('/{id}/submitTheses', [ThesisController::class, 'submitTheses'])->name('submitTheses');

    Route::patch('/{id}/approve', [ThesisController::class, 'approve'])->name('approve');
    Route::patch('/{id}/revise', [ThesisController::class, 'revise'])->name('revise');
    Route::patch('/{id}/update-status', [ThesisController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{id}/comment', [ThesisController::class, 'comment'])->name('comment');

    // Nested Comments under Theses
    Route::prefix('/{thesis}/comments')->name('comments.')->group(function () {
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/{comment}', [CommentController::class, 'show'])->name('show');
        Route::get('/{comment}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });
});

// Standalone Comment Routes (if needed)
Route::middleware('auth')->resource('comments', CommentController::class)->only([
    'index', 'create', 'edit', 'update', 'destroy'
]);

// Report Routes
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/upload', [ReportController::class, 'upload'])->name('upload');
    Route::get('/{report}', [ReportController::class, 'show'])->name('show');
    Route::get('/{report}/download/{field}', [ReportController::class, 'download'])->name('download');
});

// Notification Routes
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
    Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
});
