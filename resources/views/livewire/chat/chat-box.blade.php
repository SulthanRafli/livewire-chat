<div x-data="{ height: 0, conversationElement: document.getElementById('conversation'), markAsRead: null }" x-init="height = conversationElement.scrollHeight;
$nextTick(() => conversationElement.scrollTop = height);
Echo.private('users.{{ Auth()->User()->id }}').notification((notification) => { if (notification['type'] == 'App\\Notifications\\MessageRead' && notification['conversation_id'] == {{ $this->selectedConversation->id }}) { markAsRead = true; } });"
    @scroll-bottom.window="$nextTick(() => conversationElement.scrollTop = conversationElement.scrollHeight)"
    class="w-full overflow-hidden">
    <div class="border-b flex flex-col overflow-y-scroll grow h-full">
        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b">
            <div class="flex w-full justify-between">
                <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">
                    <a href="#" class="shrink-0 lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <div class="shrink-0">
                        <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" />
                    </div>
                    <div class="flex-row">
                        <h6 class="font-bold truncate">
                            {{ $selectedConversation->getReceiver()?->name }}
                        </h6>
                        <small>{{ $selectedConversation->getReceiver()?->department?->name }}</small>
                    </div>
                </div>
                <div class="self-center">

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-three-dots-vertical  text-gray-700"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="w-full p-1">
                                <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                    wire:click="endSession()"
                                    class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-gray-100 transition-all duration-150 ease-in-out focus:outline-none focus:bg-gray-100">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                                            <path d="M7.5 1v7h1V1z" />
                                            <path
                                                d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812" />
                                        </svg>
                                    </span>
                                    End Session
                                </button>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </header>
        <main @scroll="scropTop = $el.scrollTop; if(scropTop <= 0){ window.livewire.emit('loadMore'); }"
            @update-chat-height.window="newHeight = $el.scrollHeight; oldHeight=height; $el.scrollTop = newHeight - oldHeight; height=newHeight;"
            id="conversation"
            class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto">
            @if ($loadedMessages)
                @foreach ($loadedMessages as $key => $message)
                    <div wire:key="{{ time() . $key }}" @class([
                        'flex w-auto gap-2 relative mt-2',
                        'max-w-[85%] md:max-w-[78%]' =>
                            !$message->topics_id && !$message->end_conversation_at,
                        'ml-auto' => $message->sender_id === auth()->id(),
                        'w-full' => $message->topics_id || $message->end_conversation_at,
                    ])>
                        <div @class([
                            'flex flex-wrap text-[15px] rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]',
                            'rounded-bl-none border border-gray-200/40' => !(
                                $message->sender_id === auth()->id()
                            ),
                            'rounded-br-none bg-blue-500/80 text-white' =>
                                $message->sender_id === auth()->id(),
                            'bg-gray-400/80 rounded-bl-xl rounded-br-xl content-center items-center w-full text-white' =>
                                $message->topics_id || $message->end_conversation_at,
                        ])>
                            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
                                {{ $message->body }}
                            </p>
                            <div class="ml-auto flex gap-2">
                                <p @class([
                                    'text-xs',
                                    'text-gray-500' => !$message->sender_id === auth()->id(),
                                    'text-white' => $message->sender_id === auth()->id(),
                                ])>
                                    {{ optional($message->end_conversation_at)->format('d/m/Y g:i a') ?? $message->created_at->format('d/m/Y g:i a') }}
                                </p>
                                @if ($message->sender_id === auth()->id() && !$message->end_conversation_at)
                                    <div x-data="{ markAsRead: @json($message->isRead()) }">
                                        <span x-cloak x-show="markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0" />
                                                <path
                                                    d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708" />
                                            </svg>
                                        </span>
                                        <span x-show="!markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path
                                                    d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0" />
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </main>
        <footer class="shrink-0 z-10 bg-white inset-x-0">
            <div class="p-2 border-t">
                @if (
                    ($loadedMessages->last()?->end_conversation_at !== null || $loadedMessages->last() === null) &&
                        auth()->user()?->role == 'student')
                    @foreach ($topic as $d)
                        <button type="button"
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2"
                            wire:click="sendTopic('{{ $d->id }}')">{{ $d->name }}</button>
                    @endforeach
                @else
                    <form x-data="{ body: @entangle('body').defer }" @submit.prevent="$wire.sendMessage" method="POST"
                        autocapitalize="off">
                        @csrf
                        <input type="hidden" autocomplete="false" style="display: none">
                        <div class="grid grid-cols-12">
                            <input x-model="body" type="text" autocomplete="off" autofocus
                                placeholder="write your message here" maxlength="1700"
                                class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg  focus:outline-none">
                            <button x-bind:disabled="!body.trim()" class="col-span-2" type='submit'>Send</button>
                        </div>
                    </form>
                    @error('body')
                        <p>{{ $message }}</p>
                    @enderror

                @endif
            </div>
        </footer>
    </div>
</div>
