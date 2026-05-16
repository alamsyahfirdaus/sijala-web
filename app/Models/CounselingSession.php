<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingSession extends Model
{
    use HasFactory;

    protected $table = 'counseling_sessions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id', 'id');
    }

    public function elderlyCounselee()
    {
        return $this->belongsTo(ElderlyCounselee::class, 'elderly_counselee_id');
    }
}
