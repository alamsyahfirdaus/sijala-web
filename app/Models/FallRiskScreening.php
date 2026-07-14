<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FallRiskScreening extends Model
{
    use HasFactory;

    protected $table = 'elderly_fall_risk_screenings';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'counseling_session_id');
    }
}
