<!DOCTYPE html>
<html>
<head>
    <title>Print Barcode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }

        .barcode-container img {
            width: 300px;
            height: auto;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="barcode-container">
        <img src="{{ url('product/barcode/' . $product->barcode) }}" alt="Barcode">
        <h3>{{ $product->name }}</h3>
        <p>Price: {{ number_format($product->price, 2) }} Tk</p>
    </div>
</body>
</html>
