<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingChat extends Model
{
    use HasFactory;

    protected $table = 'counseling_chats';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'counseling_session_id');
    }

    public function messages()
    {
        return $this->hasMany(
            CounselingChatMessage::class,
            'counseling_chat_id'
        )->orderBy('created_at', 'asc');
    }
}
