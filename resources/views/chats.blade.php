<div class="chats">
    @foreach ($chats as $chat)
        <div class="chat">
            <p>{{ $chat->name }}</p>
        </div>
    @endforeach
</div>