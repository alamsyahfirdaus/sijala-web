<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpowermentQuestion extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model ini.
     */
    protected $table = 'family_empowerment_questions';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Semua field dapat diisi secara mass assignment.
     */
    protected $guarded = [];

    /**
     * Relasi ke dimensi induk.
     *
     * Jika dimension_id = null, berarti record ini adalah dimensi.
     * Jika dimension_id terisi, berarti record ini adalah pertanyaan
     * yang berada di bawah dimensi tertentu.
     */
    public function dimension()
    {
        return $this->belongsTo(self::class, 'dimension_id');
    }

    /**
     * Relasi ke daftar pertanyaan dalam dimensi.
     *
     * Hanya digunakan untuk record yang berperan sebagai dimensi.
     */
    public function questions()
    {
        return $this->hasMany(self::class, 'dimension_id')
            ->orderBy('order', 'asc');
    }

    /**
     * Scope untuk mengambil data dimensi.
     */
    public function scopeDimensions($query)
    {
        return $query->whereNull('dimension_id');
    }

    /**
     * Scope untuk mengambil data pertanyaan.
     */
    public function scopeItems($query)
    {
        return $query->whereNotNull('dimension_id');
    }

    /**
     * Accessor untuk menentukan apakah record ini adalah dimensi.
     */
    public function getIsDimensionAttribute(): bool
    {
        return is_null($this->dimension_id);
    }

    /**
     * Accessor untuk menentukan apakah record ini adalah pertanyaan.
     */
    public function getIsQuestionAttribute(): bool
    {
        return !is_null($this->dimension_id);
    }

    /**
     * Accessor untuk mengambil min_score efektif.
     * Jika min_score kosong, gunakan nilai dari dimensi.
     */
    public function getEffectiveMinScoreAttribute()
    {
        return $this->min_score ?? $this->dimension?->min_score;
    }

    /**
     * Accessor untuk mengambil max_score efektif.
     * Jika max_score kosong, gunakan nilai dari dimensi.
     */
    public function getEffectiveMaxScoreAttribute()
    {
        return $this->max_score ?? $this->dimension?->max_score;
    }
}