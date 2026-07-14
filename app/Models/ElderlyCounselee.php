<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElderlyCounselee extends Model
{
    use HasFactory;

    protected $table = 'elderly_counselee';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function counselee()
    {
        return $this->belongsTo(User::class, 'counselee_id');
    }

    public function counselingSessions()
    {
        return $this->hasMany(CounselingSession::class, 'elderly_counselee_id', 'id');
    }
}
