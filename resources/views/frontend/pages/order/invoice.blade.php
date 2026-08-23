<!DOCTYPE html>
<html>
<head>
    <title>Order Invoice | {{config('app.name')}}</title>
    <style>
        /* Add your CSS styling here */
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; font-size: 12px;}
        td span {font-size: 10px;}
        th { background-color: #f4f4f4; text-align: left; }
        .text-right { text-align: right; }
        .text-center {text-align: center;}
        h5 {font-size: 18px; font-weight: 400;margin-bottom: 0;}
        p{margin-top: 10px !important; margin-bottom: 0;}
        h4{letter-spacing: 1px;font-size: 18px; margin-top: 10px;}
        .information {display: flex;}
        .row-1, .row-2 {
            flex: 1; /* Optional: Make rows take equal width */
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            margin-top: 20px;
        }
        .invoice-table td {
            padding: 5px 10px;
            vertical-align: top;
            font-size: 14px;
        }
        .invoice-table .title {
            font-weight: bold;
            font-size: 18px;
        }
        .invoice-table .amount {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <table class="information-table">
        <tr>
            <td style="width: 40%;">
                <img src="{{ config('app.logo') }}" style="width: 140px; margin-bottom: 10px;">
                <div class="customer-info">
                    <h5>{{ $order->name }}</h5>
                    <p>{{ $order->shipping_address }}, {{ $order->district->name ?? $order->city }}</p>
                    <h4>{{ $order->phone }}</h4>
                </div>
            </td>
            <td style="width: 60%;">
                <div class="contact">
                    <h3>Contact Us</h3>
                    <p>Phone: {{ config('app.phone') }}</p>
                    <p>Email: {{ config('app.email') }}</p>
                    <p>Address: {{ config('app.address') }}</p>
                </div>
                <h1>Invoice</h1>
                <table class="invoice-table">
                    <tr>
                        <td>Invoice ID:</td>
                        <td>{{ $order->order_id }}</td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <td>Item Count:</td>
                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                    </tr>
                    <tr>
                        <td>Payment:</td>
                        <td>{{ $order->payment_type == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</td>
                    </tr>
                    @if( $order->paid_amount > 1 )
                        <tr>
                            <td>Paid:</td>
                            <td class="amount">{{ $order->grand_total }} BDT</td>
                        </tr>
                    @else
                        <tr>
                            <td>Payable:</td>
                            <td class="amount">{{ $order->grand_total }} BDT</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        {{-- show varitaion --}}
                        @if( $item->orderItemVariations->count() > 0 )
                            (<span>
                                @foreach( $item->orderItemVariations as $itemVariant )
                                    {{ucfirst($itemVariant->attribute_name) . ':' . ucfirst($itemVariant->attribute_value)}}  @if (!$loop->last) - @endif
                                @endforeach
                            </span>)
                        @endif
                    </td>
                    <td class="text-center">{{ $item->price }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ $item->price * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right">
                    <strong> {{ ($order->orderItems->sum(function ($item) { return $item->price * $item->quantity; })) }} BDT</strong>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Shipping Cost (+):</td>
                <td class="text-right"> {{ ($order->shipping_cost) }} BDT</td>
            </tr>
            @if( !is_null($order->cupon_code) )
            <tr>
                <td colspan="3" class="text-right">Discount (-):</td>
                <td class="text-right">{{($order->coupon_discount)}} BDT</td>
            </tr>
            @endif
            <tr>
                <td colspan="3" class="text-right"><strong style="font-size: 16px;">Grand Total:</strong></td>
                <td class="text-right">
                    <strong style="font-size: 16px;">
                         {{ ($order->grand_total) }} BDT
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>
    <p style="text-align: right;">In Words: {{ numberToWords($order->grand_total) }}</p>
</body>
</html>
