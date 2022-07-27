<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan</title>
    <style>
        body,
        html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 8pt;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }

        #table-data {
            font-size: 12pt;
            border-collapse: collapse;
            margin: auto 10px;
            width: '350px';
        }

        #table-data td {
            padding: 0px;
            margin: 0px;
        }

        .table-head {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table id="table-data">
        <tr>
            <td colspan="4">
                <hr />
            </td>
        </tr>
        <tr>
            <td class="table-head">Brg</td>
            <td align="center" class="table-head" width="5%">Qty</td>
            <td align="right" class="table-head" width="13%">Hrg</td>
            <td align="right" class="table-head" width="20%">SubTot</td>
        </tr>
        <tr>
            <td colspan="4">
                <hr />
            </td>
        </tr>
        @foreach($data['items'] as $row)
        <tr>
            <td>{{$row->items->name}}</td>
            <td align="center">{{$row['amount']}}</td>
            <td align="right">{{$row['price']}}</td>
            <td align="right">{{$row['subtotal']}}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <hr />
            </td>
        </tr>
        <tr>
            <td colspan="3" align="right">Total :</td>
            <td align="right">Rp. {{$data["total_pay"]}}</td>
        </tr>
        <tr>
            <td colspan="3" align="right">Dibayar :</td>
            <td align="right">Rp. {{$data["paid"]}}</td>
        </tr>
        <tr>
            <td colspan="3" align="right">Kembalian :</td>
            <td align="right">Rp. {{$data["change"]}}</td>
        </tr>
    </table>
</body>

</html>