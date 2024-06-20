<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'sender_id'
    ];

    public function messages()
    {
        return  $this->hasMany(Message::class);
    }

    public function getReceiver()
    {
        if ($this->sender_id === auth()->id()) {
            return User::with('department')->firstWhere('id', $this->receiver_id);
        } else {
            return User::with('department')->firstWhere('id', $this->sender_id);
        }
    }

    public function scopeWhereNotDeleted($query)
    {
        $userId = auth()->id();
        return $query->where(function ($query) use ($userId) {
            $query->whereHas('messages', function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->whereNull('receiver_deleted_at');
                });
            });
        })->orWhereDoesntHave('messages');
    }

    public function isLastMessageReadByUser(): bool
    {
        $user = Auth()->User();
        $lastMessage = $this->messages()->latest()->first();
        if ($lastMessage) {
            return $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }
    }

    function unreadMessagesCount(): int
    {
        return Message::where('conversation_id', '=', $this->id)->where('receiver_id', auth()->user()->id)->whereNull('read_at')->count();
    }
}
