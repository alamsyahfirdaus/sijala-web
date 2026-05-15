<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FallRiskAnswer extends Model
{
    use HasFactory;

    protected $table = 'elderly_fall_risk_answers';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
