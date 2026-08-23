<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Reply</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f2f4f8;
            padding: 30px;
        }

        .email-container {
            max-width: 700px;
            margin: auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }

        .header {
            background: linear-gradient(90deg, #007BFF, #0056b3);
            color: #fff;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .section-title {
            margin-top: 30px;
            font-size: 18px;
            font-weight: 600;
            color: #007BFF;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
        }

        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .info-table td.label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }

        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #007BFF;
            padding: 15px 20px;
            margin-top: 12px;
            border-radius: 5px;
            white-space: pre-line;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eaeaea;
            padding-top: 15px;
        }

        p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Reply to Your Contact Message</h2>
        </div>

        <div>
            <div class="section-title">Sender Details</div>
            <table class="info-table">
                <tr>
                    <td class="label">Name:</td>
                    <td>{{ $messageData->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td>{{ $messageData->email }}</td>
                </tr>
                @if($messageData->phone)
                <tr>
                    <td class="label">Phone:</td>
                    <td>{{ $messageData->phone }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Submitted At:</td>
                    <td>{{ $messageData->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <div class="section-title">Original Message</div>
            <div class="message-box">
                {{ $messageData->message }}
            </div>

            <div class="section-title">Our Reply</div>
            <div class="message-box">
                {!! $body !!}
            </div>

            @if($messageData->replied_at)
            <div class="section-title">Reply Timestamp</div>
            <table class="info-table">
                <tr>
                    <td class="label">Replied At:</td>
                    <td>{{ $messageData->replied_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>
            @endif
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
