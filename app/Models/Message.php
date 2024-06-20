<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'receiver_id',
        'sender_id',
        'conversation_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
        'topics_id',
        'end_conversation_at',
    ];

    protected $dates = ['read_at', 'receiver_deleted_at', 'sender_deleted_at', 'end_conversation_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isRead(): bool
    {
        return $this->read_at != null;
    }
}
