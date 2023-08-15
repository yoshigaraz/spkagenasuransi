<!DOCTYPE HTML>
<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <META http-equiv="X-UA-Compatible" content="IE=8">
    <title>{{config('app.name')}} | AHP</title>
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
        <td align="center" style="font-weight: bold;">MENGGUNAKAN METODE AHP</td>
        <td align="right" style="font-size: 14px; font-weight: bold">Periode {{$period}}</td>
    </tr>
</table>
<br>
<h3>Matrix Perbandingan Kriteria</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <tr>
        <th scope="col">#</th>
        @foreach($matrixCriteria as $key => $props)
            @if ($key != 'sumCol')
                <th class="text-center" scope="col">{{ $key  }}</th>
            @endif
        @endforeach
    </tr>
    @foreach($matrixCriteria as $key => $val)
        <tr>
            @if ($key != 'sumCol')
                <th scope="col">{{ $key }}
            @else
                <th scope="col">Jumlah
            @endif
            @foreach($val as $k => $value)
                @if ($key == 'sumCol')
                    <th class="text-center">{{$value}}</th>
                @else
                    <td align="center"
                        @if($value == 'N/A') style="color: red" @endif>{{$value}}</td>
                @endif
            @endforeach
        </tr>
    @endforeach
</table>
<br>
<h3>Matrix Nilai Kriteria</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th scope="col">#</th>
        @foreach ($eigenCriteria as $key => $props)
            @if ($key == 'sumEigen')
                <th class="text-center" scope="col">Jumlah</th>
                <th class="text-center" scope="col">Prioritas</th>
            @else
                <th class="text-center" scope="col">{{ $key  }}</th>
            @endif
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach ($eigenCriteria as $keyName => $value)
        <tr>
            @if ($keyName != 'sumEigen')
                <th scope="col">{{ $keyName }}
            @else
                <th scope="col">Jumlah</th>
            @endif
            @foreach ($value as $key => $valueMatrix)
                @if ($key == 'totalEigen')
                    <td align="center">
                        @if(is_numeric($valueMatrix))
                            {{  round($valueMatrix, 3) }}
                        @else
                            {{$valueMatrix}}
                        @endif
                    </td>
                    <?php
                    $avg = \App\Http\Controllers\RatioAlternativeController::getAverage($valueMatrix, $eigenCriteria['sumEigen']['totalEigen']);
                    ?>
                    <td align="center">{{$avg}}</td>
                @else
                    <td align="center">
                        @if(is_numeric($valueMatrix))
                            {{round($valueMatrix, 3)}}
                        @else
                            {{$valueMatrix}}
                        @endif
                    </td>
                @endif
            @endforeach

        </tr>
    @endforeach
    <tr>
        <td colspan='{{(count($eigenCriteria) + 1)}}' align="right">Lamda Max</td>
        <td colspan='1' align="center">{{$lamdaCriteria['sumLamda']}}</td>
    </tr>
    <tr class="text-center">
        <td colspan='{{(count($eigenCriteria) + 1)}}' align="right">IR Variable</td>
        <td colspan='1' align="center">{{$lamdaCriteria['IR']}}</td>
    </tr>
    <tr class="text-center">
        <td colspan='{{(count($eigenCriteria) + 1)}}' align="right">Consistency Index (CI)</td>
        <td colspan='1' align="center">{{$lamdaCriteria['CI']}}</td>
    </tr>
    <tr class="text-center">
        <td colspan='{{(count($eigenCriteria) + 1)}}' align="right">Consistency Ratio = CI / IR</td>
        <td colspan='1' align="center">{{$lamdaCriteria['constant']}}</td>
    </tr>
    <tr class="text-center">
        <td colspan='{{(count($eigenCriteria) + 1)}}' align="right">Consistency Status</td>
        <td colspan='1' align="center">
            @if (is_numeric($lamdaCriteria['constant']) && $lamdaCriteria['constant'] < 0.1)
                <span class="badge badge-success">Consistent</span>
            @else
                <span class="badge badge-danger">inConsistent</span>
            @endif
        </td>
    </tr>
    </tbody>
</table>
<br>
@foreach ($alternative as $alt)
    <h3>Matrix Perbandingan Alternatif {{$alt->name}}</h3>
    <table width="100%" cellpadding="5" cellspacing="0" border="1px">
        <thead>
        <tr>
            <th scope="col">#</th>
            @foreach($alt->matrix as $key => $props)
                @if ($key != 'sumCol')
                    <th class="text-center" scope="col">{{ $key  }}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        @foreach($alt->matrix as $key => $val)
            <tr>
                @if ($key != 'sumCol')
                    <th scope="col">{{ $key }}
                @else
                    <th scope="col">Jumlah
                @endif
                @foreach($val as $k => $value)
                    @if ($key == 'sumCol')
                        <th class="text-center">{{$value}}</th>
                    @else
                        <td align="center"
                            @if($value == 'N/A') style="color: red" @endif>{{$value}}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>
    <br>
    <h3>Matrix Nilai Alternatif {{$alt->name}}</h3>
    <table width="100%" cellpadding="5" cellspacing="0" border="1px">
        <thead>
        <tr>
            <th scope="col">#</th>
            @foreach($alt->eigen as $key => $props)
                @if ($key == 'sumEigen')
                    <th class="text-center" scope="col">Jumlah</th>
                    <th class="text-center" scope="col">Prioritas</th>
                    {{--<th class="text-center" scope="col">Eigen Value</th>--}}
                @else
                    <th class="text-center" scope="col">{{ $key  }}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($alt->eigen as $keyName => $val)
            <tr>
                @if ($keyName != 'sumEigen')
                    <th scope="col">{{ $keyName }}</th>
                @else
                    <th scope="col">Total</th>
                @endif
                @foreach($val as $key => $valueMatrix)
                    @if ($key == 'totalEigen')
                        <td align="center">
                            @if(is_numeric($valueMatrix))
                                {{  round($valueMatrix, 3) }}
                            @else
                                {{$valueMatrix}}
                            @endif
                        </td>
                        <?php
                        $avg = \App\Http\Controllers\RatioAlternativeController::getAverage($valueMatrix, $alt->eigen['sumEigen']['totalEigen']);
                        ?>
                        <td align="center">{{$avg}}</td>
                    @else
                        <td align="center">
                            @if(is_numeric($valueMatrix))
                                {{  round($valueMatrix, 3) }}
                            @else
                                {{$valueMatrix}}
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        <tr class="text-center">
            <td colspan='{{(count($alt->eigen) + 1)}}' align="right">Lamda Max</td>
            <td colspan='1' align="center">{{$alt->lamda['sumLamda']}}</td>
        </tr>
        <tr class="text-center">
            <td colspan='{{(count($alt->eigen) + 1)}}' align="right">IR Variable</td>
            <td colspan='1' align="center">{{$alt->lamda['IR']}}</td>
        </tr>
        <tr class="text-center">
            <td colspan='{{(count($alt->eigen) + 1)}}' align="right">Consistency Index (CI)</td>
            <td colspan='1' align="center">{{$alt->lamda['CI']}}</td>
        </tr>
        <tr class="text-center">
            <td colspan='{{(count($alt->eigen) + 1)}}' align="right">Consistency Ratio = CI / IR</td>
            <td colspan='1' align="center">{{$alt->lamda['constant']}}</td>
        </tr>
        <tr class="text-center">
            <td colspan='{{(count($alt->eigen) + 1)}}' align="right">Consistency Status</td>
            <td colspan='1' align="center">
                @if (is_numeric($alt->lamda['constant']) && $alt->lamda['constant'] < 0.1)
                    <span class="badge badge-success">Consistent</span>
                @else
                    <span class="badge badge-danger">inConsistent</span>
                @endif
            </td>
        </tr>
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
    @foreach($ahp as $key => $value)
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
<h3>Hasil Akhir</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama</th>
        @foreach($criteria as $c)
            <th>{{$c->name}}</th>
        @endforeach
        <th>Hasil</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $num = 1;
    ?>
    @foreach($ahp as $key => $value)
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