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
// use App\Http\Controllers\Web\ReportController;
// use App\Http\Controllers\Web\RoleController;
// use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\CounselingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/image/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('filename', '.*');

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

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
        // Route::get('/counselees', [UserController::class, 'getCounseleeList'])->name('users.counselees');
        // Route::get('/counselors', [UserController::class, 'getCounselorList'])->name('users.counselors');
    });

    Route::prefix('counselings')->group(function () {
        Route::get('/', [CounselingController::class, 'index'])->name('counselings');
        Route::get('/{id}/session', [CounselingController::class, 'session'])->name('counseling.session');
    });

    // Route::prefix('screenings')->group(function () {
    //     Route::get('/', [CounselingController::class, 'screeningList'])->name('screenings');
    // });

    // Route::prefix('evaluations')->group(function () {
    //     Route::get('/', [CounselingController::class, 'evaluationList'])->name('evaluations');
    // });
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
