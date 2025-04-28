<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - T-{{sprintf('%06d', $sale->id)}}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            background: url("{{ public_path('images/invoice.png') }}") no-repeat center center;
            background-size: cover;
            margin: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }

        #client {
            position: absolute;
            top: 263px;
            left: 95px;
            font-size: 10px;
            color: #7C7C7C;
        }

        #phone {
            position: absolute;
            top: 278px;
            left: 105px;
            font-size: 10px;
            color: #7C7C7C;
        }

        #document_number {
            position: absolute;
            top: 292px;
            left: 75px;
            font-size: 10px;
            color: #7C7C7C;
        }

        #address {
            position: absolute;
            top: 345px;
            left: 45px;
            font-size: 10px;
            color: #7C7C7C;
        }

        #number {
            position: absolute;
            top: 227px;
            right: 85px;
            font-size: 20px;
            color: #FFF;
        }

        #date {
            position: absolute;
            top: 285px;
            right: 97px;
            font-size: 12px;
            color: #7C7C7C;
        }

        #tables {
            position: absolute;
            top: 400px;
            left: 45px;
            width: 90%;
        }

        table {
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<p id="client">{{$sale->client->name}}</p>
<p id="phone">{{$sale->client->phone}}</p>
<p id="document_number">{{$sale->client->document_number}}</p>
<p id="address">{{$sale->client->address}}</p>
<p id="number">T-{{sprintf('%06d', $sale->id)}}</p>
<p id="date">{{$sale->date}}</p>
<div id="tables">
    <table class="table table-primary">
        <thead>
        <tr style="background: #7454CC;color:#FFF;font-size:12px; ;">
            <th style="padding: 10px">DESCRIPCIÓN</th>
            <th scope="col">CANTIDAD</th>
            <th scope="col">VALOR</th>
            <th scope="col">TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sale->saleDetails as $item)
            <tr style="text-align: center;background: #ECF0F1;font-size:12px">
                <th style="padding: 5px">{{$item->article->title}}</th>
                <td>{{$item->quantity}}</td>
                <td>S/. {{$item->price}}</td>
                <td>S/. {{$item->price * $item->quantity}}</td>
            </tr>
        @endforeach

        <tr style="text-align: center;font-size:12px;color:#FFF">
            <th colspan="2"></th>
            <td style="background: #F6983E; padding: 5px;">OP. INAFECTAS</td>
            <td style="background: #F6983E;padding: 5px;">S/. {{$sale->total}}</td>
        </tr>
        @if($sale->tax != 0)
            <tr style="text-align: center;font-size:12px;color:#FFF">
                <th colspan="2"></th>
                <td style="background: #36A9E1; padding: 5px;">IGV</td>
                <td style="background: #36A9E1;padding: 5px;">S/. {{$sale->tax}}</td>
            </tr>
        @endif
        @if($sale->delivery ==1)
            <tr style="text-align: center;font-size:12px;color:#FFF">
                <th colspan="2"></th>
                <td style="background: #36A9E1; padding: 5px;">DELIVERY</td>
                <td style="background: #36A9E1;padding: 5px;">S/. {{$sale->delivery_fee}}</td>
            </tr>
        @endif
        <tr style="text-align: center;font-size:12px">
            <th colspan="2"></th>
            <td style="background: #ECF0F1; padding: 5px;">TOTAL</td>
            <td style="background: #ECF0F1;padding: 5px;">S/. {{$sale->total + $sale->delivery_fee}}</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
