<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingResumeOption extends Model
{
    use HasFactory;

    protected $table = 'counseling_resume_options';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(CounselingResumeOption::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(CounselingResumeOption::class, 'category_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc');
    }

}
