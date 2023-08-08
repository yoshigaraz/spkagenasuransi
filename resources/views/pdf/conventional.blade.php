<!DOCTYPE HTML>
<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <META http-equiv="X-UA-Compatible" content="IE=8">
    <title>{{config('app.name')}} | Commodity Traffic Report</title>
    <link rel="icon" href="{{asset(config('app.app_icon'))}}" type="image/x-icon">
    <STYLE type="text/css">
        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            /*font-size: x-small;*/
            font-size: small;
        }

        .footer-bold {
            font-weight: bold;
            font-family: Arial, serif;
            font-size: small;
        }

        .footer-italic {
            font-style: italic;
            font-family: Arial, serif;
            font-size: small;
        }

        .footer-normal {
            font-family: Arial, serif;
            font-size: small;
        }

        .bold {
            font-weight: bold;
        }

        .italic {
            font-style: italic;
        }

        .bold-italic {
            font-weight: bold;
            font-style: italic;
        }
    </STYLE>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="20%">&nbsp;</td>
        <td align="center" style="font-weight: bold">HASIL PERANGKINGAN AGEN</td>
        <td align="right" width="20%" style="font-size: 11px; font-style: italic">Tanggal Cetak {{date("d/m/Y")}}</td>
    </tr>
    <tr>
        <td class="italic">&nbsp;</td>
        <td align="center" style="font-weight: bold;">MENGGUNAKAN METODE KONVENSIONAL</td>
        <td align="right" style="font-size: 14px; font-weight: bold">Periode {{$period}}</td>
    </tr>
</table>
<br><br>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th width="10">#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
        <th>Total Poin</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($conventional as $key => $value)
        <tr>
            <td>{{$num}}</td>
            <td>{{$value->code}}</td>
            <td>{{$value->name}}</td>
            @foreach($value->points as $k => $v)
                <td align="center">{{$v->point}}</td>
            @endforeach
            <th>{{$value->total}}</th>
        </tr>
        <?php
        $num++;
        ?>
    @endforeach
    </tbody>
</table>
</body>
</html>