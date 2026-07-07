<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationPresentation extends Model
{
    use HasFactory;

    protected $table = 'consultation_presentations';
    protected $primaryKey = 'id';

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Video Call / Consultation
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(
            Consultation::class,
            'consultation_id'
        );
    }

    /**
     * Materi Edukasi
     */
    public function educationContent(): BelongsTo
    {
        return $this->belongsTo(
            EducationContent::class,
            'education_content_id'
        );
    }

    /**
     * Presenter (Konselor)
     */
    public function presenter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'presenter_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPE
    |--------------------------------------------------------------------------
    */

    /**
     * Presentasi yang masih aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Presentasi yang sedang diputar.
     */
    public function scopePlaying(Builder $query): Builder
    {
        return $query->where('status', 'playing');
    }

    /**
     * Presentasi yang sedang dijeda.
     */
    public function scopePaused(Builder $query): Builder
    {
        return $query->where('status', 'paused');
    }

    /**
     * Presentasi yang telah dihentikan.
     */
    public function scopeStopped(Builder $query): Builder
    {
        return $query->where('status', 'stopped');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah presentasi sedang diputar.
     */
    public function getIsPlayingAttribute(): bool
    {
        return $this->is_active &&
               $this->status === 'playing';
    }

    /**
     * Apakah presentasi sedang dijeda.
     */
    public function getIsPausedAttribute(): bool
    {
        return $this->is_active &&
               $this->status === 'paused';
    }

    /**
     * Apakah presentasi telah dihentikan.
     */
    public function getIsStoppedAttribute(): bool
    {
        return !$this->is_active ||
               $this->status === 'stopped';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Menghentikan presentasi.
     */
    public function stop(): bool
    {
        return $this->update([
            'status' => 'stopped',
            'is_active' => false,
            'ended_at' => now(),
        ]);
    }

    /**
     * Menjeda presentasi.
     */
    public function pause(): bool
    {
        return $this->update([
            'status' => 'paused',
        ]);
    }

    /**
     * Melanjutkan presentasi.
     */
    public function resume(): bool
    {
        return $this->update([
            'status' => 'playing',
        ]);
    }

    /**
     * Memperbarui posisi presentasi.
     */
    public function updatePosition(int $position): bool
    {
        return $this->update([
            'current_position' => $position,
        ]);
    }
}
