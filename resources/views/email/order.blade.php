<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Online Shop</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            font-size: 16px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        li:last-child {
            border-bottom: none;
        }

        li strong {
            color: #2c3e50;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>

<body style="font-family: Arial,Helvetica,sans-serif; font-size:16px;">
    <div class="container">
        @if ($mailData['userType']=='customer')
        <h1>Thanks for your order!!</h1>
        <h2>Your Order Id is :#{{ $mailData['order']->id }}</h2>
        @else
        <h1>You have recived an order!!</h1>
        <h2>Order Id:#{{ $mailData['order']->id }}</h2>
        @endif

        <h2 class="">Shipping Address</h2>
        <address>
            <strong>{{ $mailData['order']->first_name . '' . $mailData['order']->last_name }}</strong><br>
            {{ $mailData['order']->address }}<br>
            {{ $mailData['order']->city }},{{ $mailData['order']->zip }},{{ getCountryInfo($mailData['order']->country_id)->name }}<br>

            Phone: {{ $mailData['order']->mobile }}<br>
            Email: {{ $mailData['order']->email }}
        </address>

        <h2>Products</h2>

        <table cellpadding="3" cellspacing="3" border="0">
            <thead>
                <tr style="background:#ccc;">
                    <th>Product</th>
                    <th width="100">Price</th>
                    <th width="100">Qty</th>
                    <th width="100">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mailData['order']->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->qty, 2) }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>



                    </tr>
                @endforeach

                <tr>
                    <th colspan="3" class="text-right">Subtotal:</th>
                    <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
                </tr>

                {{-- <tr>
                <th colspan="3" class="text-right">Shipping:</th>
                <td>$5.00</td>
            </tr> --}}
                <tr>
                    <th colspan="3" class="text-right">Grand Total:</th>
                    <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
                </tr>
            </tbody>
        </table>




    </div>
</body>

</html>
