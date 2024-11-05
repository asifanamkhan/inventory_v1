<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            color: #333;
        }

        .logo {
            width: 90px;
            height: auto
        }

        .company-details {
            width: 70%;
            text-align: right;
        }

        .company-name {
            font-size: 22px;
            text-align: right;
            font-weight: bold
        }

        .pdf-title {
            font-size: 22px;
            text-align: right;
            font-weight: bold;
            color: #4CAF50
        }

    </style>
</head>

<body>
    <table cellpadding="3">
        <tr>
            <td rowspan="5" style="width: 30%">
                <img class="logo" src="{{ $logo }}" alt="">
            </td>
            <td></td>
        </tr>
        <tr>
            <td  style="text-align: right;width: 70%">
                <span class="company-name">{{ $company->name }}</span>
            </td>
        </tr>
        <tr>
            <td class="company-details" >
                <div style="padding: 10px;">{{ $company->address }}</div>
            </td>
        </tr>
        <tr>
            <td  style="text-align: right">
                <b>Contact:</b> {{ $company->email }} | {{ $company->phone }}
            </td>
        </tr>
        <tr>
            <td class="pdf-title">
                {{ $headerTitle }}
            </td>
        </tr>
        <tr>
            <td>Printing date: {{ date('d-M-Y', strtotime($date)) }}</td>
            <td style="text-align: right; font-style:italic">Reporting currency: Taka</td>
        </tr>

    </table>
</body>

</html>
