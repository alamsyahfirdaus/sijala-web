<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationPresentation extends Model
{
    use HasFactory;

    protected $table = 'consultation_presentations';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
