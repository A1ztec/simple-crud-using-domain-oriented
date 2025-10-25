<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background: #f9f9f9;
        }

        .order-details {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
        }

        .item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🛒 New Order Created!</h1>
        </div>

        <div class="content">
            <p>Hello Admin,</p>
            <p>A new order has been placed on your store.</p>

            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Order ID:</strong> {{ $order->uuid }}</p>
                <p><strong>Client:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
                <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Status:</strong> <span style="color: #FF9800;">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="order-details">
                <h3>Order Items ({{ $order->items->count() }})</h3>
                @foreach($order->items as $item)
                    <div class="item">
                        <p><strong>{{ $item->product_name }}</strong></p>
                        <p>Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }} =
                            ${{ number_format($item->quantity * $item->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Your Store. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
