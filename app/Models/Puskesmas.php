<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puskesmas extends Model
{
    use HasFactory;

    protected $table = 'puskesmas';
    protected $primaryKey = 'id';
    // public $timestamps = false;

    protected $guarded = [];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
