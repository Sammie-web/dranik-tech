<div style="font-family: Arial, sans-serif; line-height:1.5;">
    <h3>New message about your booking #{{ $message->booking_id }}</h3>

    <p><strong>From:</strong> {{ $message->sender->name }}</p>

    <p>{{ $message->body }}</p>

    <p>
        <a href="{{ route('chat.thread', $message->booking_id) }}">Open chat</a>
    </p>

    <p style="color:#888; font-size:12px">You received this email because you're a participant in the booking chat.</p>
</div>
