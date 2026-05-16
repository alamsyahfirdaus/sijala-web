<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QaQuestion;
use Illuminate\Http\Request;

class QaController extends Controller
{
    // =========================
    // LIST SEMUA PERTANYAAN
    // =========================
    public function index()
    {
        $data = QaQuestion::with('answers')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pertanyaan',
            'data' => $data
        ]);
    }
}
