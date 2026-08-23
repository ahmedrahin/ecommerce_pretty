<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectContent }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="background-color: #2c3e50; color: white; padding: 20px;">
            <h2 style="margin: 0;">{{ config('app.name') }} – Reply to Your Question</h2>
        </div>

        <div style="padding: 20px;">
            {{-- Product Information --}}
            <h3 style="margin-top: 0;">Product Details</h3>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                @if($messageData->product && $messageData->product->thumbnail)
                    <img src="{{ asset($messageData->product->thumbnail) }}" alt="Product Image" style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px; border: 1px solid #ddd; border-radius: 4px;">
                @endif
                <div>
                    <strong>Product:</strong> <a href="{{ route('product-details',$messageData->product->slug) }}">{{ $messageData->product->name ?? 'N/A' }}</a>
                </div>
            </div>

            {{-- Question Info --}}
            <h3>Customer Question</h3>
            <p><strong>From:</strong> {{ $messageData->user->email ?? 'Guest' }}</p>
            <p><strong>Question:</strong></p>
            <blockquote style="background: #f9f9f9; padding: 10px; border-left: 4px solid #3498db;">
                {{ $messageData->question ?? 'No question provided.' }}
            </blockquote>

            {{-- Your Reply --}}
            <h3>Your Answer</h3>
            <p style="white-space: pre-wrap;">{!! $body !!}</p>

            <hr style="margin: 30px 0;">

            <p style="font-size: 13px; color: #888;">This message was sent by {{ config('app.name') }} to respond to your inquiry. If you have more questions, feel free to reach out.</p>
        </div>
    </div>
</body>
</html>
