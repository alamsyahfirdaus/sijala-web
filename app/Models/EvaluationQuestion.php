<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_questions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function topic()
    {
        return $this->belongsTo(EvaluationTopic::class, 'evaluation_topic_id');
    }

    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class, 'evaluation_question_id');
    }
}
