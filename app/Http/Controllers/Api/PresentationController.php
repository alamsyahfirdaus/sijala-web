<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsultationPresentation;
use App\Models\CounselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresentationController extends Controller
{
    /**
     * ==========================================================
     * SHARE PRESENTATION
     * ==========================================================
     */
    public function share(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consultation_session_id' => 'required|exists:counseling_sessions,id',
            'education_content_id'    => 'required|exists:education_contents,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Nonaktifkan presentasi lama
        ConsultationPresentation::where(
            'consultation_session_id',
            $request->consultation_session_id
        )->update([
            'is_active' => false,
            'status'    => 'stopped',
            'ended_at'  => now(),
        ]);

        $presentation = ConsultationPresentation::create([
            'consultation_session_id' => $request->consultation_session_id,
            'education_content_id'    => $request->education_content_id,
            'presenter_id'            => auth()->id(),
            'status'                  => 'playing',
            'current_position'        => 0,
            'is_active'               => true,
            'started_at'              => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dibagikan.',
            'data'    => $presentation,
        ]);
    }

    /**
     * ==========================================================
     * GET PRESENTATION STATUS
     * ==========================================================
     */
    public function status($sessionId)
    {
        $presentation = ConsultationPresentation::with('educationContent')
            ->where('consultation_session_id', $sessionId)
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$presentation) {
            return response()->json([
                'success' => true,
                'show'    => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'show'    => true,
            'data'    => $presentation,
        ]);
    }

    /**
     * ==========================================================
     * STOP PRESENTATION
     * ==========================================================
     */
    public function stop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consultation_session_id' => 'required|exists:counseling_sessions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        ConsultationPresentation::where(
            'consultation_session_id',
            $request->consultation_session_id
        )->where('is_active', true)
            ->update([
                'status'    => 'stopped',
                'is_active' => false,
                'ended_at'  => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Presentasi dihentikan.',
        ]);
    }

    /**
     * ==========================================================
     * PAUSE PRESENTATION
     * ==========================================================
     */
    public function pause(Request $request)
    {
        ConsultationPresentation::where(
            'consultation_session_id',
            $request->consultation_session_id
        )->where('is_active', true)
            ->update([
                'status' => 'paused',
            ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * ==========================================================
     * RESUME PRESENTATION
     * ==========================================================
     */
    public function resume(Request $request)
    {
        ConsultationPresentation::where(
            'consultation_session_id',
            $request->consultation_session_id
        )->where('is_active', true)
            ->update([
                'status' => 'playing',
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}