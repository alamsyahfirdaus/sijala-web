<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationTopic extends Model
{
    use HasFactory;

    protected $table = 'evaluation_topics';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(EvaluationQuestion::class, 'evaluation_topic_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluation_topic_id');
    }
}
