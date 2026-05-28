<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CounselingController;
use App\Http\Controllers\Api\CounselorController;
use App\Http\Controllers\Api\ElderlyCounseleeController;
use App\Http\Controllers\Api\EmpowermentController;
use App\Http\Controllers\Api\FallRiskController;
use App\Http\Controllers\Api\PuskesmasController;
use App\Http\Controllers\Api\QaController;
use App\Http\Controllers\Api\CounselingChatController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\UserDeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint REST API untuk aplikasi SIJALA
| Prefix otomatis: /api
|
*/

Route::get('/ping', function () {
    return response()->json([
        'status' => true,
        'message' => 'API OK'
    ]);
});


/*
|--------------------------------------------------------------------------
| AUTH API
|--------------------------------------------------------------------------
*/

// Registrasi pengguna
Route::post('/register', [AuthController::class, 'register']);

// Login pengguna
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('puskesmas')->group(function () {
    Route::get('/', [PuskesmasController::class, 'index']);
    Route::match(['get', 'post'], '/search', [PuskesmasController::class, 'search']);
});


/*
|--------------------------------------------------------------------------
| PROTECTED API (BUTUH LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/image/{filename}', function ($filename) {
    // Mencegah path traversal
    $filename = basename($filename);

    // Path file di folder public/images
    $path = public_path('images/' . $filename);

    // Jika file tidak ditemukan
    if (!file_exists($path)) {
        return response()->json([
            'status' => false,
            'message' => 'File tidak ditemukan',
            'filename' => $filename,
            'path' => $path,
        ], 404);
    }

    // Tampilkan file gambar
    return response()->file($path);
});

Route::middleware('api.auth')->group(function () {

    // Auto login (cek token)
    Route::get('/auto-login', [AuthController::class, 'autoLogin']);

    // Profil pengguna
    Route::get('/profile', [AuthController::class, 'profile']);

    // Update profil
    Route::match(['post', 'put'], '/profile/update', [AuthController::class, 'updateProfile']);

    // Ganti password
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Refresh token
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('elderly-counselee')->group(function () {
        Route::get('/', [ElderlyCounseleeController::class, 'index']);
        Route::match(['post', 'put'], '/store', [ElderlyCounseleeController::class, 'store']);
        Route::get('/count', [ElderlyCounseleeController::class, 'count']);
    });

    Route::prefix('counseling')->group(function () {
        Route::get('/', [CounselingController::class, 'index']);
        Route::get('/count/{counseleeId?}', [CounselingController::class, 'countCounselingSessions']);
        Route::get('/{elderlyCounseleeId}/show', [CounselingController::class, 'getCounselingSessionsById']);
        Route::get('/today', [CounselingController::class, 'getTodayCounselingSessions']);
        Route::get('/statistics',[CounselingController::class, 'getCounselingStatistics']);
    });

    Route::prefix('fall-risk')->group(function () {
        Route::get('/', [FallRiskController::class, 'index']);
        Route::match(['put', 'post'], '/store', [FallRiskController::class, 'store']);
    });

    Route::prefix('empowerment')->group(function () {
        Route::get('/', [EmpowermentController::class, 'index']);
        Route::match(['put', 'post'], '/store', [EmpowermentController::class, 'store']);
    });

    Route::prefix('qa')->group(function () {
        Route::get('/', [QaController::class, 'index']);
        Route::get('/{id}', [QaController::class, 'show']);
        Route::post('/question', [QaController::class, 'storeQuestion']);
        Route::post('/{id}/answer', [QaController::class, 'storeAnswer']);
        Route::delete('/{id}', [QaController::class, 'destroy']);
    });

    Route::prefix('chat')->group(function () {
        Route::get('/{sessionId}/show', [CounselingChatController::class, 'showChatSessions']);
        Route::post('/send', [CounselingChatController::class, 'storeChatMessage']);
        Route::get('/{sessionId}/messages', [CounselingChatController::class, 'fetchMessages']);
        Route::post('/{sessionId}/read', [CounselingChatController::class, 'markMessagesAsRead']);
    });

    Route::prefix('evaluation')->group(function () {
        Route::get('/', [EvaluationController::class, 'index']);
        Route::get('/{id}/questions', [EvaluationController::class, 'getEvaluationQuestions']);
        Route::match(['put', 'post'], 'store', [EvaluationController::class, 'saveEvaluationQuestions']);
    });

    Route::get('/education-contents', [CounselingController::class, 'showEducationContents']);
    Route::post('/save-device-token', [UserDeviceController::class, 'saveToken']);

    Route::prefix('consultations')->group(function () {
        Route::get('/', [ConsultationController::class, 'index']);
        Route::get('/{id}/show', [ConsultationController::class, 'consultationDetail']);
        Route::post('/request-call', [ConsultationController::class, 'requestCall']);
        Route::post('/accept-call', [ConsultationController::class, 'acceptCall']);
        Route::post('/reject-call', [ConsultationController::class, 'rejectCall']);
        Route::post('/end-call', [ConsultationController::class, 'endCall']);
    });
});

/*
|--------------------------------------------------------------------------
| REGION API
|--------------------------------------------------------------------------
| Endpoint wilayah untuk provinsi, kabupaten, kecamatan, dan kelurahan
|
*/

Route::prefix('regions')->group(function () {

    // daftar provinsi
    Route::get('/provinces', [RegionController::class, 'provinces']);

    // daftar kabupaten berdasarkan provinsi
    Route::get('/regencies/{province_id}', [RegionController::class, 'regencies']);

    // daftar kecamatan berdasarkan kabupaten
    Route::get('/districts/{regency_id}', [RegionController::class, 'districts']);

    // daftar kelurahan berdasarkan kecamatan
    Route::get('/villages/{district_id}', [RegionController::class, 'villagesByDistrict']);

    // semua kelurahan + relasi lengkap
    Route::get('/villages', [RegionController::class, 'villages']);

    // pencarian kelurahan (autocomplete)
    Route::get('/search-village', [RegionController::class, 'searchVillage']);
});
