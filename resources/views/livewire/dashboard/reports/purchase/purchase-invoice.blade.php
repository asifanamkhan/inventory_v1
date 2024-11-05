<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            color: #333;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            /* position: absolute; */
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
            font-size: 10px;
            font-weight: bold
        }
    </style>
</head>

<body>
    <table cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <td style="font-size: 10px; text-align:left;"><b>Bill To:</b></td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Invoice no: {{ $tran_mst->memo_no }}</td>
            </tr>
            <tr>
                <td>{{ $tran_mst->supplier_name }}</td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Purchase date: {{ date('d-M-y',
                    strtotime($tran_mst->date)) }}</td>
            </tr>
            <tr>
                <td>{{ $tran_mst->address }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Phone: {{ $tran_mst->phone }}</td>
                <td></td>
            </tr>
        </thead>

    </table>
    <br />
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th class="invoice-items-head" style="width: 5%">SL</th>
                <th class="invoice-items-head" style="width: 40%">Item</th>
                <th class="invoice-items-head" style="width: 15%; text-align: center">Qty</th>
                <th class="invoice-items-head" style="width: 15%; text-align: center">Rate</th>
                <th class="invoice-items-head" style="width: 25%; text-align: center">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ledgers as $key => $dtl)
            <tr>
                <td style="width: 5%">{{ $key+1 }}</td>
                <td style="width: 40%">{{ $dtl->product_name }}
                    @if($dtl->variant_description) | {{ $dtl->variant_description }} @endif
                </td>
                <td style="text-align: center; width: 15%">{{ $dtl->quantity }}</td>
                <td style="text-align: right; width: 15%">{{ number_format($dtl->rate, 1, '.', '') }}</td>
                <td style="text-align: right; width: 25%">{{ number_format($dtl->total, 1, '.', '') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse

            <tr>
                <td colspan="2" style="text-align: right"><b>Total :</b></td>
                <td style="text-align: center">
                    <b>{{ $tran_mst->qty }}</b>
                </td>
                <td></td>
                <td style="text-align: right">
                    <b>{{ number_format($tran_mst->net_total, 1, '.', ',') }}</b>
                </td>
            </tr>
            <tr>
                <td style="border: none" colspan="7"></td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right; font-weight:bold; border: none">Shipping</th>
                <td style="text-align: right">{{ number_format($tran_mst->shipping, 1, '.', ',') }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right; font-weight:bold; border: none">Discount</th>
                <td style="text-align: right">{{ number_format($tran_mst->discount, 1, '.', ',') }}</td>
            </tr>
            <tr class="grand-total">
                <th colspan="4" style="text-align: right; font-weight:bold; border: none">Total</th>
                <td style="text-align: right">{{ number_format($tran_mst->total, 1, '.', ',') }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right; font-weight:bold; border: none">Paid amount</th>
                <td style="text-align: right"><b>{{ number_format($tran_mst->paid, 1, '.', ',') }}</b></td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: right; font-weight:bold; border: none">Due amount</th>
                <td style="color: darkred;text-align: right"><b>{{ number_format($tran_mst->due, 1, '.', ',')}}</b></td>
            </tr>
        </tbody>


    </table>
</body>

</html>
