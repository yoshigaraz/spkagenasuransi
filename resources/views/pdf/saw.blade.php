<!DOCTYPE HTML>
<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <META http-equiv="X-UA-Compatible" content="IE=8">
    <title>{{config('app.name')}} | SAW</title>
    <link rel="icon" href="{{asset(config('app.app_icon'))}}" type="image/x-icon">
    <STYLE type="text/css">
        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            /*font-size: x-small;*/
            font-size: 10px;
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
        <td align="center" style="font-weight: bold;">MENGGUNAKAN METODE SAW</td>
        <td align="right" style="font-size: 14px; font-weight: bold">Periode {{$period}}</td>
    </tr>
</table>
<br><br>
<h3>Bobot Kriteria</h3>
<table width="400px" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th width="10px">#</th>
        <th>Kode</th>
        <th>Kriteria</th>
        <th>Bobot</th>
        <th>Atribut</th>
    </tr>
    </thead>
    <tbody>
    @foreach($criteria as $key => $c)
        <tr align="center">
            <td>{{++$key}}</td>
            <td>{{$c->code}}</td>
            <td>{{$c->name}}</td>
            <td>{{$c->weight}}</td>
            <td>{{$c->attribute}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<h3>Nilai Crips</h3>
@foreach($criteria as $key => $c)
    <table width="300px" cellpadding="5" cellspacing="0" border="1px">
        <thead>
        <tr>
            <th width="10px">#</th>
            <th>{{$c->name}}</th>
            <th>Bobot</th>
        </tr>
        </thead>
        <tbody>
        @foreach($c->alternative as $k => $alt)
            <tr align="center">
                <td>{{++$k}}</td>
                <td>{{$alt->description}}</td>
                <td>{{$alt->weight}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
@endforeach
<h3>Alternatif</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($saw as $key => $value)
        <tr>
            <td>{{$num}}</td>
            <td>{{$value->code}}</td>
            <td>{{$value->name}}</td>
            @foreach($value->alternative as $k => $v)
                <td align="center">{{number_format($v->value)}}</td>
            @endforeach
        </tr>
        <?php
        $num++;
        ?>
    @endforeach
    </tbody>
</table>
<br>
<h3>Matriks Keputusan</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($saw as $key => $value)
        <tr>
            <td>{{$num}}</td>
            <td>{{$value->code}}</td>
            <td>{{$value->name}}</td>
            @foreach($value->alternative as $k => $v)
                <td align="center">{{$v->weight}}</td>
            @endforeach
        </tr>
        <?php
        $num++;
        ?>
    @endforeach
    </tbody>
</table>
<br>
<h3>Matriks Ternormalisasi <small><i>(Benefit = Nilai Kriteria/Nilai Maks)</i></small></h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($saw as $key => $value)
        <tr>
            <td>{{$num}}</td>
            <td>{{$value->code}}</td>
            <td>{{$value->name}}</td>
            @foreach($value->alternative as $k => $v)
                <td align="center">{{round($v->benefit, 4)}}</td>
            @endforeach
        </tr>
        <?php
        $num++;
        ?>
    @endforeach
    </tbody>
</table>
<br>
<h3>Perangkingan</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($saw as $key => $value)
        <tr>
            <td>{{$num}}</td>
            <td>{{$value->code}}</td>
            <td>{{$value->name}}</td>
            @foreach($value->alternative as $k => $v)
                <td align="center">{{round($v->point, 4)}}</td>
            @endforeach
            <th>{{round($value->total_point,4)}}</th>
        </tr>
        <?php
        $num++;
        ?>
    @endforeach
    </tbody>
</table>
</body>
</html>