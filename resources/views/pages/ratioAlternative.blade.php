{{-- @dd($data) --}}
@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Perbandingan Alternatif</h1>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Perbandingan Alternatif </h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 mb-3" method="POST" action="{{route('addRatioAlternative')}}">
                            @csrf
                            <div class="form-group col-3">
                                <select class="form-control" name="criteria">
                                    <option selected>--Pilih Kriteria--</option>
                                    @foreach ($data->criteria as $criteria)
                                        <option value={{ $criteria['id'] }}>{{ $criteria['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select class="form-control" name="v_alternative">
                                    <option selected>Pilih Alternatif 1</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select class="form-control" name="value" required>
                                    <option value="" disabled selected>--Pilih Nilai--</option>
                                    <option value="1">
                                        1 - Equally Important
                                    </option>
                                    <option value="2">
                                        2 - Equally Important / A Little More Important
                                    </option>
                                    <option value="3">
                                        3 - A Little More Important
                                    </option>
                                    <option value="4">
                                        4 - Equally Important / Obviously More Important
                                    </option>
                                    <option value="5">
                                        5 - Obviously More Important
                                    </option>
                                    <option value="6">
                                        6 - Obviously More Important / Very Clearly Important
                                    </option>
                                    <option value="7">
                                        7 - Very Clearly Important
                                    </option>
                                    <option value="8">
                                        8 - Very Clearly Important / Absolutely More Important
                                    </option>
                                    <option value="9">
                                        9 - Absolutely More Important
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select class="form-control" name="h_alternative">
                                    <option selected>Pilih Alternatif 2</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary mb-3">Tambah</button>
                            </div>
                        </form>
                        <table class="table" id="tbAlternativeRatio">
                            <thead>
                            <tr>
                                <th scope="col" width="20">#</th>
                                <th scope="col">Kriteria</th>
                                <th scope="col">Alternatif 1</th>
                                <th scope="col">Alternatif 2</th>
                                <th scope="col">Value</th>
                                <th scope="col" width="20">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <p hidden>{{$looping = 1}}</p>
                            @foreach ($data->ratio as $r)
                                <tr>
                                    <th scope="row">{{$looping++}}</th>
                                    <td>{{ $r['criteria'] }}</td>
                                    <td>{{ $r['v_name'] }}</td>
                                    <td>{{ $r['h_name'] }}</td>
                                    <td>{{ $r['value'] }}</td>
                                    <td>
                                        <a href="{{route('deleteRatioAlternative' , ['v_id' => $r['v_id'], 'h_id' => $r['h_id']])}}"
                                           class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        @foreach ($data->criteria as $criteria)

            <div class="row">
                <div class="col-lg-12 mb-4">
                    <!-- Illustrations -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List Perbandingan
                                Kriteria {{$criteria->name}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        @foreach($criteria->matrix as $key => $props)
                                            @if ($key != 'sumCol')
                                                <th class="text-center" scope="col">{{ $key  }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                    </thead>
                                    @foreach($criteria->matrix as $key => $val)
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
                                                    <td class="text-center"
                                                        @if($value == 'N/A') style="color: red" @endif>{{$value}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <h6 class="m-0 font-weight-bold text-primary">Nilai Eigen
                                Kriteria {{$criteria->name}}</h6>
                            <div class="row">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        @foreach($criteria->eigen as $key => $props)
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
                                    @foreach($criteria->eigen as $keyName => $val)
                                        <tr>
                                            @if ($keyName != 'sumEigen')
                                                <th scope="col">{{ $keyName }}</th>
                                            @else
                                                <th scope="col">Total</th>
                                            @endif
                                            @foreach($val as $key => $valueMatrix)
                                                @if ($key == 'totalEigen')
                                                    <td class="text-center">
                                                        @if(is_numeric($valueMatrix))
                                                            {{  round($valueMatrix, 3) }}
                                                        @else
                                                            {{$valueMatrix}}
                                                        @endif
                                                    </td>
                                                    <?php
                                                    $avg = \App\Http\Controllers\RatioAlternativeController::getAverage($valueMatrix, $criteria->eigen['sumEigen']['totalEigen']);
                                                    ?>
                                                    <td class="text-center">{{$avg}}</td>
                                                @else
                                                    <td class="text-center">
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
                                        <td colspan='{{(count($criteria->eigen) + 1)}}'>Lamda Max</td>
                                        <td colspan='1'>{{$criteria->lamda['sumLamda']}}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan='{{(count($criteria->eigen) + 1)}}'>IR Variable</td>
                                        <td colspan='1'>{{$criteria->lamda['IR']}}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan='{{(count($criteria->eigen) + 1)}}'>Consistency Index (CI)</td>
                                        <td colspan='1'>{{$criteria->lamda['CI']}}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan='{{(count($criteria->eigen) + 1)}}'>Consistency Ratio = CI / IR</td>
                                        <td colspan='1'>{{$criteria->lamda['constant']}}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan='{{(count($criteria->eigen) + 1)}}'>Consistency Status</td>
                                        <td colspan='1'>
                                            @if (is_numeric($criteria->lamda['constant']) && $criteria->lamda['constant'] < 0.1)
                                                <span class="badge badge-success">Consistent</span>
                                            @else
                                                <span class="badge badge-danger">inConsistent</span>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')
    <script>
        new DataTable('#tbAlternativeRatio');
        var v_alternative = $('select[name=v_alternative]');
        var h_alternative = $('select[name=h_alternative]');
        var criteria = $('select[name=criteria]');
        var tbAlternativeRatio = document.getElementById('tbAlternativeRatio');
        criteria.change(function () {
            $.ajax({
                url: '{{route('alternativeByCriteria')}}',
                method: 'GET',
                data: {
                    'criteria': $(this).val()
                },
                success: function (data) {
                    v_alternative.html('');
                    v_alternative.html(data.options);
                    h_alternative.html('');
                    h_alternative.html(data.options);
                }
            });
        });

    </script>
@endsection
