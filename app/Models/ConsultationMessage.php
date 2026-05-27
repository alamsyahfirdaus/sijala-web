<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationMessage extends Model
{
     use HasFactory;

    protected $table = 'consultation_messages';
    protected $primaryKey = 'id';

    protected $guarded = [];

    // Relasi ke konsultasi/video call
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');   
    }

    // Relasi ke pengguna (pengirim pesan)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
