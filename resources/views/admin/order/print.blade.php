<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ $order['id'] }}</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }
    .invoice-container {
      width: 700px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header {
      border-bottom: 2px solid #007bff;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .header h2 {
      margin: 0;
      color: #007bff;
    }
    .info {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .info div {
      width: 48%;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table th, table td {
      border: 1px solid #dee2e6;
      padding: 10px;
      text-align: left;
    }
    table th {
      background-color: #007bff;
      color: #fff;
    }
    .total {
      text-align: right;
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 12px;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <div class="header">
      <h2>Invoice #{{ $order['id'] }}</h2>
      <p>Date: {{ date('d M, Y', strtotime($order['created_at'])) }}</p>
    </div>

    <div class="info">
      <div>
        <h4>Bill To:</h4>
        <p><strong>{{ $order['user']['name'] }}</strong><br>
        Email: {{ $order['user']['email'] }}<br>
        Contact: {{ $order['user']['contact'] }}</p>
      </div>
      <div>
        <h4>Payment Info:</h4>
        <p>
          Stripe Customer ID: {{ $order['stripe_customer_id'] }}<br>
          Payment Intent ID: {{ $order['stripe_payment_intent_id'] }}<br>
          Status: {{ ucfirst($order['status']) }}<br>
          Currency: {{ strtoupper($order['currency']) }}
        </p>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Description</th>
          <th>Type</th>
          <th>Quantity</th>
          <th>Price ({{ strtoupper($order['currency']) }})</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr>
          <td>{{ $item['item_name'] }}</td>
          <td>{{ $item['description'] }}</td>
          <td>{{ $item['type'] }}</td>
          <td>{{ $item['quantity'] }}</td>
          <td>{{ number_format($item['price'], 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="total">
      Total Amount: {{ number_format($order['amount'], 2) }} {{ strtoupper($order['currency']) }}
    </div>

    <div class="footer">
      Thank you for your purchase!<br>
      <strong>Powered by Stripe Payments</strong>
    </div>
  </div>
</body>
</html>
