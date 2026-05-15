<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpowermentAnswer extends Model
{
    use HasFactory;

    protected $table = 'family_empowerment_answers';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
