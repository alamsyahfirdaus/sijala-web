<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationPresentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PresentationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHARE PRESENTATION
    |--------------------------------------------------------------------------
    */

    public function share(Request $request)
    {
        try {

            $user = $request->attributes->get('user');

            $validator = Validator::make($request->all(), [
                'consultation_id'      => 'required|exists:consultations,id',
                'education_content_id' => 'required|exists:education_contents,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $consultation = Consultation::find($request->consultation_id);

            if (!$consultation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video call tidak ditemukan.',
                ], 404);
            }

            DB::beginTransaction();

            ConsultationPresentation::where(
                'consultation_id',
                $consultation->id
            )->active()->update([
                'status'    => 'stopped',
                'is_active' => false,
                'ended_at'  => now(),
            ]);

            $presentation = ConsultationPresentation::create([
                'consultation_id'      => $consultation->id,
                'education_content_id' => $request->education_content_id,
                'presenter_id'         => $user->id,
                'status'               => 'playing',
                'current_position'     => 0,
                'is_active'            => true,
                'started_at'           => now(),
            ]);

            DB::commit();

            $presentation->load([
                'educationContent',
                'presenter',
                'consultation',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil dibagikan.',
                'data'    => $presentation,
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('SHARE PRESENTATION ERROR', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
            ], 500);
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | GET PRESENTATION STATUS
    |--------------------------------------------------------------------------
    */

    public function status(int $consultationId)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | DEBUG REQUEST
            |--------------------------------------------------------------------------
            */

            Log::info('GET PRESENTATION STATUS', [
                'consultation_id' => $consultationId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | VALIDASI KONSULTASI
            |--------------------------------------------------------------------------
            */

            $consultation = Consultation::find($consultationId);

            if (!$consultation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video call tidak ditemukan.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL PRESENTASI AKTIF
            |--------------------------------------------------------------------------
            */

            $presentation = ConsultationPresentation::with([
                    'consultation',
                    'educationContent',
                    'presenter',
                ])
                ->active()
                ->where('consultation_id', $consultationId)
                ->latest('id')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | BELUM ADA PRESENTASI
            |--------------------------------------------------------------------------
            */

            if (!$presentation) {

                Log::info('PRESENTATION NOT FOUND', [
                    'consultation_id' => $consultationId,
                ]);

                return response()->json([
                    'success' => true,
                    'show'    => false,
                    'data'    => null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DEBUG RESULT
            |--------------------------------------------------------------------------
            */

            Log::info('PRESENTATION FOUND', [
                'presentation_id' => $presentation->id,
                'consultation_id' => $presentation->consultation_id,
                'education_content_id' => $presentation->education_content_id,
                'presenter_id' => $presentation->presenter_id,
                'status' => $presentation->status,
                'is_active' => $presentation->is_active,
            ]);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'show'    => true,
                'data'    => $presentation,
            ]);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERROR LENGKAP
            |--------------------------------------------------------------------------
            */

            Log::error('GET PRESENTATION STATUS ERROR', [
                'consultation_id' => $consultationId,
                'message'         => $e->getMessage(),
                'line'            => $e->getLine(),
                'file'            => $e->getFile(),
                'trace'           => $e->getTraceAsString(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE ERROR
            |--------------------------------------------------------------------------
            | Saat debugging tampilkan pesan asli agar mudah mencari penyebab.
            | Setelah production bisa diganti menjadi:
            | 'Terjadi kesalahan server.'
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | STOP PRESENTATION
    |--------------------------------------------------------------------------
    */

    public function stop(Request $request)
    {
        return $this->updateStatus($request, 'stopped');
    }

    /*
    |--------------------------------------------------------------------------
    | PAUSE PRESENTATION
    |--------------------------------------------------------------------------
    */

    public function pause(Request $request)
    {
        return $this->updateStatus($request, 'paused');
    }

    /*
    |--------------------------------------------------------------------------
    | RESUME PRESENTATION
    |--------------------------------------------------------------------------
    */

    public function resume(Request $request)
    {
        return $this->updateStatus($request, 'playing');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    private function updateStatus(Request $request, string $status)
    {
        try {

            $validator = Validator::make($request->all(), [
                'consultation_id' => 'required|exists:consultations,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $presentation = ConsultationPresentation::active()
                ->where('consultation_id', $request->consultation_id)
                ->latest()
                ->first();

            if (!$presentation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presentasi tidak ditemukan.',
                ], 404);
            }

            $data = [
                'status' => $status,
            ];

            if ($status === 'stopped') {
                $data['is_active'] = false;
                $data['ended_at'] = now();
            }

            $presentation->update($data);

            return response()->json([
                'success' => true,
                'message' => match ($status) {
                    'paused'  => 'Presentasi dijeda.',
                    'playing' => 'Presentasi dilanjutkan.',
                    default   => 'Presentasi dihentikan.',
                },
                'data' => $presentation->fresh([
                    'educationContent',
                    'presenter',
                ]),
            ]);

        } catch (\Throwable $e) {

            Log::error('UPDATE PRESENTATION ERROR', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
            ], 500);
        }
    }
}