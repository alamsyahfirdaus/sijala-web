<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpowermentAssessment extends Model
{
    use HasFactory;

    protected $table = 'family_empowerment_assessments';
    protected $primaryKey = 'id';

    protected $guarded = [];
    
}
