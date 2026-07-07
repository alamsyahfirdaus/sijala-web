<?php

// use App\Http\Controllers\Web\AssessmentCategoryController;
// use App\Http\Controllers\Web\AssessmentController;
// use App\Http\Controllers\Web\AssessmentOptionController;
// use App\Http\Controllers\Web\AssessmentQuestionController;
// use App\Http\Controllers\Web\AssessmentResultController;
// use App\Http\Controllers\Web\CounselingSessionController;
// use App\Http\Controllers\Web\DashboardController;
// use App\Http\Controllers\Web\EducationArticleController;
// use App\Http\Controllers\Web\EducationVideoController;
// use App\Http\Controllers\Web\ElderlyController;
// use App\Http\Controllers\Web\ElderlyFamilyController;
// use App\Http\Controllers\Web\NotificationController;
// use App\Http\Controllers\Web\RoleController;
// use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CounselingController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/image/{filename}', function ($filename) {
    $path = public_path('images/'.$filename);
    if (! File::exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

Route::get('/', [HomeController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users');
        Route::get('{id}/show', [UserController::class, 'show'])->name('user.show');
        Route::match(['post', 'put'], 'save', [UserController::class, 'save'])->name('user.save');
        Route::match(['delete', 'post'], '{id}/delete', [UserController::class, 'destroy'])->name('user.delete');
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('user.bulk-delete');
    });

    Route::prefix('counselings')->group(function () {
        Route::get('/', [CounselingController::class, 'index'])->name('counselings');
        Route::get('/{id}/session', [CounselingController::class, 'session'])->name('counseling.session');
        Route::match(['post', 'put'], '/scores/update', [CounselingController::class, 'updateScore'])->name('scores.update');
        Route::match(['delete', 'post'], '{id}/delete', [CounselingController::class, 'destroy'])->name('counseling.delete');
    });

    Route::prefix('reports')->group(function () {
        Route::match(['get', 'post'], '/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::match(['get', 'post'], '/{report}/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::match(['get', 'post'], '/{report}/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    });
});

Route::fallback(function () {
    return redirect()->route('landing');
});

//     /*
//     |--------------------------------------------------------------------------
//     | Profile
//     |--------------------------------------------------------------------------
//     */

//     Route::get('/profile', [AuthController::class, 'profile'])
//         ->name('profile');

//     Route::put('/profile', [AuthController::class, 'updateProfile'])
//         ->name('profile.update');

//     Route::put('/change-password', [AuthController::class, 'changePassword'])
//         ->name('password.change');

//     /*
//     |--------------------------------------------------------------------------
//     | Master User
//     |--------------------------------------------------------------------------
//     */

// Route::middleware(['auth', 'role:admin'])->group(function () {

// Route::resource('users', UserController::class);

// Route::resource('roles', RoleController::class);

// Route::resource('settings', SettingController::class)
//     ->only(['index', 'update']);
// });

//     /*
//     |--------------------------------------------------------------------------
//     | Lansia
//     |--------------------------------------------------------------------------
//     */

//     Route::resource('elderlies', ElderlyController::class);

//     Route::resource(
//         'elderly-families',
//         ElderlyFamilyController::class
//     );

//     /*
//     |--------------------------------------------------------------------------
//     | Konseling
//     |--------------------------------------------------------------------------
//     */

//     Route::resource(
//         'counselings',
//         CounselingController::class
//     );

//     Route::resource(
//         'counseling-sessions',
//         CounselingSessionController::class
//     );

//     /*
//     |--------------------------------------------------------------------------
//     | Assessment
//     |--------------------------------------------------------------------------
//     */

//     Route::resource(
//         'assessment-categories',
//         AssessmentCategoryController::class
//     );

//     Route::resource(
//         'assessment-questions',
//         AssessmentQuestionController::class
//     );

//     Route::resource(
//         'assessment-options',
//         AssessmentOptionController::class
//     );

//     Route::resource(
//         'assessments',
//         AssessmentController::class
//     );

//     Route::resource(
//         'assessment-results',
//         AssessmentResultController::class
//     );

//     /*
//     |--------------------------------------------------------------------------
//     | Edukasi
//     |--------------------------------------------------------------------------
//     */

//     Route::resource(
//         'education-articles',
//         EducationArticleController::class
//     );

//     Route::resource(
//         'education-videos',
//         EducationVideoController::class
//     );

//     /*
//     |--------------------------------------------------------------------------
//     | Notifikasi
//     |--------------------------------------------------------------------------
//     */

//     Route::resource(
//         'notifications',
//         NotificationController::class
//     );

//     /*
//     |--------------------------------------------------------------------------
//     | Laporan
//     |--------------------------------------------------------------------------
//     */

//     Route::prefix('reports')
//         ->name('reports.')
//         ->group(function () {

//             Route::get(
//                 '/elderlies',
//                 [ReportController::class, 'elderlies']
//             )->name('elderlies');

//             Route::get(
//                 '/counselings',
//                 [ReportController::class, 'counselings']
//             )->name('counselings');

//             Route::get(
//                 '/assessments',
//                 [ReportController::class, 'assessments']
//             )->name('assessments');

//             Route::get(
//                 '/export-pdf',
//                 [ReportController::class, 'exportPdf']
//             )->name('export.pdf');

//             Route::get(
//                 '/export-excel',
//                 [ReportController::class, 'exportExcel']
//             )->name('export.excel');
//         });
// });
