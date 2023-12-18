<!DOCTYPE html>

<html>

<head>

    <title>Barcode of Products</title>

</head>

<body>

  

<div>

    <h1 style="text-align: center">Barcode of Products</h1>

    

    <table border="0" width="100%">

        @foreach($products->sortBy('brand_name')->groupBy('brand_name') as $brand => $productsByBrand)
            <tr>
                <td colspan="2" align="center">
                    <h2>Brand: {{ $brand }}</h2>
                </td>
            </tr>
            @foreach($productsByBrand->chunk(2) as $chunk)
                <tr>
                    @foreach($chunk as $product)
                        <td align="center" style="text-align: center;" @if(count($chunk) == 1) colspan="2" @endif>
                            <img src="data:image/png;base64,{{ $barcodes[$loop->index] }}" width="300" height="100" style="padding-top: 1rem">
                            <div style="display: flex; justify-content: start;">
                                <p style="">Model/Size: {{ $product->model_size }}</p>
                            </div>
                        </td>
                    @endforeach
                    @if(count($chunk) < 2 && count($productsByBrand) > 1)
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endforeach

    </table>
    

</div>

    

</body>

</html>