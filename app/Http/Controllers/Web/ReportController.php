<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ReportController extends Controller
{
    public function index()
    {
        // 
    }

    public function show($report)
    {
        

        try {

            $report = Crypt::decryptString(
                urldecode($report)
            );

            $title = match ($report) {

                'elderly' => 'Lansia',
                'counselor' => 'Konselor',
                'counseling' => 'Konseling',
                'screening' => 'Skrining',
                'evaluation' => 'Evaluasi',

                default => abort(404),
            };

            return view(
                'reports',
                compact('title', 'report')
            );

        } catch (\Exception $e) {

            abort(404);

        }
    }
}