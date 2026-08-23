<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Order Alert - {{config('app.name')}}</title>
    <style>
        /* Reset and Base Styles */
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            line-height: 1.6;
            color: #333;
        }
        
        /* Main Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header */
        .email-header {
            background-color: #4f46e5;
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            max-height: 40px;
            margin-bottom: 20px;
        }
        
        .header-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }
        
        /* Content */
        .content {
            padding: 40px 30px;
        }
        
        /* Section */
        .section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .section-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: -15px;
            margin-bottom: 20px;
        }
        
        /* Info Grid - Simple Table Layout */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            padding: 12px 0;
            text-align: left;
            width: 35%;
        }
        
        .info-value {
            color: #111827;
            font-size: 15px;
            font-weight: 500;
            padding: 12px 0;
            text-align: left;
        }
        
        .info-row {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        /* Total Section */
        .total-section {
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin-top: 30px;
        }
        
        .total-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .total-amount {
            color: #111827;
            font-size: 32px;
            font-weight: bold;
            margin: 0;
        }
        
        /* Button */
        .button-container {
            text-align: center;
            margin-top: 40px;
        }
        
        .action-button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
        }
        
        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .social-icons {
            margin-bottom: 20px;
        }
        
        .social-icon {
            display: inline-block;
            margin: 0 8px;
            color: #6b7280;
            font-size: 18px;
            text-decoration: none;
        }
        
        .copyright {
            color: #6b7280;
            font-size: 13px;
            margin-top: 10px;
        }
        
        .company-name {
            color: #111827;
            font-weight: 600;
        }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 480px) {
            .email-header,
            .content {
                padding: 25px 20px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .info-label,
            .info-value {
                display: block;
                width: 100%;
                padding: 8px 0;
            }
            
            .info-label {
                font-weight: 600;
            }
            
            .info-row {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo-container">
                <img src="{{config('app.logo')}}" alt="{{config('app.name')}}" class="logo" />
            </div>
            <h1 class="header-title">New Order Alert</h1>
            <p class="header-subtitle">
                Order Received! A new order has been successfully placed on your store.
            </p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Customer Information -->
            <div class="section">
                <h2 class="section-title">Customer Information</h2>
                <p class="section-subtitle">Details about the customer</p>
                
                <table class="info-grid">
                    <tr class="info-row">
                        <td class="info-label">Customer Name</td>
                        <td class="info-value">{{$order->name}}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Phone Number</td>
                        <td class="info-value">{{$order->phone}}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Email Address</td>
                        <td class="info-value">{{$order->email ?? 'Not provided'}}</td>
                    </tr>
                </table>
            </div>
            
            <!-- Order Information -->
            <div class="section">
                <h2 class="section-title">Order Details</h2>
                <p class="section-subtitle">Complete order information</p>
                
                <table class="info-grid">
                    <tr class="info-row">
                        <td class="info-label">Order ID</td>
                        <td class="info-value">#{{$order->order_id}}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Total Items</td>
                        <td class="info-value">{{$order->orderItems->sum('quantity')}} items</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Order Date</td>
                        <td class="info-value">{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y - h:i A') }}</td>
                    </tr>
                   
                </table>
            </div>
            
            <!-- Grand Total -->
            <div class="total-section">
                <div class="total-label">Grand Total</div>
                <div class="total-amount">${{number_format($order->grand_total, 2)}}</div>
            </div>
            
            <!-- Action Button -->
            <div class="button-container">
                <a href="{{route('order-management.order.show',$order->id)}}" target="_blank" class="action-button" style="color: white;">
                    View Complete Order Details
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="copyright">
                © {{date('Y')}} <span class="company-name">{{config('app.name')}}</span>. All rights reserved.
                <br>
                This is an automated notification email.
            </div>
        </div>
    </div>
</body>
</html>