<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'counseling_session_id');
    }

    public function topic()
    {
        return $this->belongsTo(EvaluationTopic::class, 'evaluation_topic_id');
    }
}
