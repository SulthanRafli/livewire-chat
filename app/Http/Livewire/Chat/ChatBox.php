<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Topic;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body = "";
    public $loadedMessages;

    public $paginate_var = 10;

    protected $listeners = [
        'loadMore'
    ];

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return  [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatchBrowserEvent('scroll-bottom');
                $newMessage = Message::find($event['message_id']);
                $this->loadedMessages->push($newMessage);

                $newMessage->read_at = now();
                $newMessage->save();

                $this->selectedConversation->getReceiver()
                    ->notify(new MessageRead($this->selectedConversation->id));
            }
        }
    }

    public function loadMore(): void
    {
        $this->paginate_var += 10;
        $this->loadMessages();
        $this->dispatchBrowserEvent('update-chat-height');
    }

    public function loadMessages()
    {
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();

        if ($this->paginate_var == 10) {
            $lastMessage = $this->loadedMessages->last();
            if ($lastMessage?->conversation_id == $this->selectedConversation->id) {
                $newMessage = Message::find($lastMessage->id);
                if ($newMessage?->sender_id != auth()->id()) {
                    $newMessage->read_at = now();
                    $newMessage->save();

                    $this->selectedConversation->getReceiver()
                        ->notify(new MessageRead($this->selectedConversation->id));
                }
            }
        }
    }

    public function sendTopic($topics_id)
    {
        $topic = Topic::find($topics_id);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $topic->name,
            'topics_id' => $topic->id,
        ]);

        $this->reset('body');
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->loadedMessages->push($createdMessage);
        // $this->selectedConversation->update_at = now();
        // $this->selectedConversation->save();
        $this->emitTo('chat.chat-list', 'refresh');
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));

        $conversation = Conversation::find($this->selectedConversation->id);
        $conversation->topics_id = $topics_id;
        $conversation->end_conversation_at = null;
        $conversation->save();
    }

    public function endSession()
    {
        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => 'End Session',
            'end_conversation_at' => now(),
        ]);

        $this->reset('body');
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->loadedMessages->push($createdMessage);
        // $this->selectedConversation->update_at = now();
        // $this->selectedConversation->save();
        $this->emitTo('chat.chat-list', 'refresh');
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));

        $conversation = Conversation::find($this->selectedConversation->id);
        $conversation->end_conversation_at = now();
        $conversation->save();
    }

    public function sendMessage()
    {
        $this->validate(['body' => 'required|string']);
        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body

        ]);

        $this->reset('body');
        $this->dispatchBrowserEvent('scroll-bottom');
        $this->loadedMessages->push($createdMessage);
        // $this->selectedConversation->update_at = now();
        // $this->selectedConversation->save();
        $this->emitTo('chat.chat-list', 'refresh');
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));
    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function render()
    {

        return view('livewire.chat.chat-box', ['topic' => Topic::orderBy('priority', 'desc')->orderBy('id', 'asc')->get()]);
    }
}
