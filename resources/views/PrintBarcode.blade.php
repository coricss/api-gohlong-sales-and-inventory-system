<!DOCTYPE html>

<html>

<head>

    <title>Print Barcode of {{ $brand_name }}</title>

</head>

<body>

  

<div>

    <h1 style="text-align: center">Model/Size: {{ $brand_name }}</h1>

    
    <table border="0" width="100%">
            <tr>
                <td align="center">
                    <img src="data:image/png;base64,{{ $barcode }}" width="300" height="100">
                    <p style="text-align: center;">{{ $model_size }}</p>
                </td>
            </tr>
    </table>
    

</div>

    

</body>

</html>