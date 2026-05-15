<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    use HasFactory;

    protected $table = 'counselors';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class);
    }
}
