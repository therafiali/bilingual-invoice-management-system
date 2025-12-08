<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice['number'] }}</title>
    <style>
        /* Basic styles for the PDF content */
        body { font-family: sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; line-height: 24px; color: #555; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th, .item-table td { border: 1px solid #eee; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>Invoice</h1>
        <p><strong>Invoice #:</strong> {{ $invoice['number'] }}</p>
        <p><strong>Date:</strong> {{ $invoice['date'] }}</p>
        <p><strong>Client:</strong> {{ $invoice['client'] }}</p>

        <table class="item-table">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice['items'] as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>${{ number_format($item['price'], 2) }}</td>
                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                    <td><strong>${{ number_format($invoice['total'], 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 40px; text-align: center;">
            <p>Scan this QR code to view or verify this invoice:</p>
            {{-- Embed the Base64 encoded QR Code --}}
            <img src="{{ $qrCodeDataUrl }}" alt="Invoice QR Code">
        </div>
    </div>
</body>
</html>