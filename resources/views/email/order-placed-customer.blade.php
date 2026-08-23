<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation - {{config('app.name')}}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f9f9f9; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f9f9f9; padding-bottom: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; font-family: sans-serif; color: #4a4a4a; border-radius: 8px; overflow: hidden; margin-top: 20px; border: 1px solid #eeeeee; }
        .header { background-color: #ffffff; padding: 20px 20px; text-align: center; border-bottom: 1px solid #f0f0f0; }
        .header img { max-height: 80px; width: auto; }
        .content { padding: 40px 30px; text-align: center; }
        .content h1 { font-size: 24px; color: #1a1a1a; margin-bottom: 10px; }
        .content p { font-size: 16px; color: #666666; margin-bottom: 25px; }
        .order-card { background-color: #f8fbfd; border: 1px dashed #3498db; border-radius: 6px; padding: 20px; margin-bottom: 30px; }
        .order-card table { width: 100%; border-spacing: 0; }
        .order-card td { padding: 5px 0; font-size: 14px; }
        .label { color: #888888; text-transform: uppercase; font-size: 12px; font-weight: bold; }
        .value { color: #1a1a1a; font-weight: 600; text-align: right; }
        .button { background-color: #1a1a1a; color: #ffffff !important; text-decoration: none; padding: 15px 30px; border-radius: 4px; font-weight: bold; display: inline-block; font-size: 14px; transition: background 0.3s ease; }
        .footer { text-align: center; padding: 30px; font-size: 13px; color: #999999; }
        .footer a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    @if(config('app.logo'))
                        <img src="{{ url(config('app.logo')) }}" alt="{{ config('app.name') }}">
                    @else
                        <h2 style="margin:0;">{{ config('app.name') }}</h2>
                    @endif
                </td>
            </tr>

            <tr>
                <td class="content">
                    <h1>Order Confirmed!</h1>
                    <p>Hi {{ $order->customer_name ?? 'there' }}, thank you for your purchase. We’re getting your order ready and will notify you once it ships.</p>

                    <div class="order-card">
                        <table>
                            <tr>
                                <td class="label">Order ID</td>
                                <td class="value">#{{ $order->order_id }}</td>
                            </tr>
                            <tr>
                                <td class="label">Order Date</td>
                                <td class="value">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Total Amount</td>
                                <td class="value">${{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <a href="{{ route('order.invoice.pdf', $order->order_id) }}" class="button">
                        DOWNLOAD INVOICE
                    </a>
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p>Need help? <a href="{{ config('app.email') }}">Contact Support</a></p>
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>