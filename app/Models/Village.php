<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'villages';
    protected $primaryKey = 'id';
    // public $timestamps = false;

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function puskesmas()
    {
        return $this->hasMany(Puskesmas::class);
    }
}
