<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingChatMessage extends Model
{
    use HasFactory;

    protected $table = 'counseling_chat_messages';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(CounselingChat::class, 'counseling_chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
