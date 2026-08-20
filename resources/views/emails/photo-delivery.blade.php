<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f5;
            padding: 20px;
            margin: 0;
        }

        .card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            margin: 0 auto;
        }

        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            color: #6b7280;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Terima Kasih, {{ $order->customer_name }}!</h2>
        <p>Pembayaran Anda untuk pesanan <strong>#{{ $order->order_number }}</strong> telah berhasil kami terima.</p>
        <p>Silakan klik tombol di bawah ini untuk mengunduh file foto asli Anda (resolusi tinggi & tanpa watermark):</p>

        <div style="text-align: center;">
            <a href="{{ $downloadUrl }}" class="btn">Unduh Foto Asli</a>
        </div>

        <p style="margin-top: 25px; font-size: 13px; color: #ef4444;">
            *Catatan: Link download ini berlaku selama 24 jam demi alasan keamanan.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} Studio Photography. All rights reserved.
        </div>
    </div>
</body>

</html>
