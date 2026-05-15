<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QaQuestion extends Model
{
    use HasFactory;

    protected $table = 'qa_questions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(QaAnswer::class, 'qa_question_id');
    }

    public function isAnswered()
    {
        return $this->status === 'answered';
    }
}
