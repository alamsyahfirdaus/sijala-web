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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
