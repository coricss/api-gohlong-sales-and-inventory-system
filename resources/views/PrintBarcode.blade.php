<!DOCTYPE html>

<html>

<head>

    <title>Laravel 10 Generate PDF Example - ItSolutionStuff.com</title>

</head>

<body>

  

<div>

    <h1 style="text-align: center">Model/Size: {{ $model_size }}</h1>

    
    <table border="0" width="100%">
        @for ($i = 0; $i < $stocks; $i+=2)
            <tr>
                <td align="center">
                    <img src="data:image/png;base64,{{ $barcode }}" width="300" height="100">
                    <p style="text-align: center;">{{ $product_id }}</p>
                </td>
                <td align="center">
                    @if ($i+1 < $stocks)
                    <img src="data:image/png;base64,{{ $barcode }}" width="300" height="100">
                    <p style="text-align: center;">{{ $product_id }}</p>
                    @endif
                </td>
            </tr>
        @endfor
    </table>
    

</div>

    

</body>

</html>