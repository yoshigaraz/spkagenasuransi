{{-- @dd($data) --}}
@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Perbandingan Kriteria</h1>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Perbandingan Kriteria </h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 mb-3" method="POST" action="{{route('addRatioCriteria')}}">
                            @csrf
                            <div class="form-group col-3">
                                <select class="form-control" name="v_criteria">
                                    <option selected>Pilih Kriteria 1</option>
                                    @foreach ($data->criteria as $criteria)
                                        <option value={{ $criteria['id'] }}>{{ $criteria['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                {{--<input type="decimal" class="form-control" id="inputCriteria" placeholder="Nilai"--}}
                                {{--name="value">--}}
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
                                <select class="form-control" name="h_criteria">
                                    <option selected>Pilih Kriteria 2</option>
                                    @foreach ($data->criteria as $criteria)
                                        <option value={{ $criteria['id'] }}>{{ $criteria['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary mb-3">Tambah</button>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kriteria 1</th>
                                <th scope="col">Kriteria 2</th>
                                <th scope="col">Value</th>
                                <th scope="col">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <p hidden>{{$looping = 1}}</p>
                            {{--@dd($data->ratio)--}}
                            @foreach ($data->ratio as $r)
                                <tr>
                                    <th scope="row">{{$looping++}}</th>
                                    <td>{{ $r['v_name'] }}</td>
                                    <td>{{ $r['h_name'] }}</td>
                                    <td>{{ $r['value'] }}</td>
                                    <td>
                                        <a href="{{route('deleteRatioCriteria' , ['v_id' => $r['v_id'], 'h_id' => $r['h_id']])}}"
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

        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Perbandingan Kriteria </h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>#</th>
                                @foreach($data->criteria as $key => $val)
                                    <th>{{$val['name']}}</th>
                                @endforeach
                            </tr>
                            @foreach($data->criteria as $key => $val)
                                <tr>
                                    <th>{{$val['name']}}</th>
                                    @foreach($data->criteria as $key2 => $val2)
                                        {{--@if($key == $key2)--}}
                                            {{--<td>1</td>--}}
                                        {{--@else--}}
                                            <?php
                                            $value = \App\Http\Controllers\RatioCriteriaController::getRatio($val['id'], $val2['id']);
                                            ?>
                                            @if($value == "")
                                                <td style="color: red">N/A</td>
                                            @else
                                                <td>{{$value}}</td>
                                            @endif
                                        {{--@endif--}}
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <th>Jumlah</th>
                                @foreach($data->criteria as $key => $val)
                                    <th>{{\App\Http\Controllers\RatioCriteriaController::getTotalRatio($data->criteria,$val['id'])}}</th>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 mb-4">
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Eigen Table </h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                @foreach ($data->eigen as $key => $props)
                                    @if ($key == 'sumEigen')
                                        <th class="text-center" scope="col">Tot. Eigen</th>
                                        <th class="text-center" scope="col">Avg. Eigen</th>
                                    @else
                                        <th class="text-center" scope="col">{{ $key  }}</th>
                                    @endif
                                @endforeach

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data->eigen as $keyName => $value)
                                <tr>
                                    @if ($keyName != 'sumEigen')
                                        <th scope="col">{{ $keyName }}
                                    @else
                                        <th scope="col">Jumlah
                                    @endif
                                    @foreach ($value as $key => $valueMatrix)
                                        @if ($key == 'totalEigen')
                                            <td class="text-center">{{  round($valueMatrix, 3) }}</td>
                                            <td class="text-center">{{  round( $valueMatrix / $data->eigen['sumEigen']['totalEigen'], 3) }}</td>
                                        @else
                                            <td class="text-center">{{  round($valueMatrix, 3) }}</td>
                                            @endif
                                            @endforeach
                                            </th>
                                </tr>
                            @endforeach
                            <tr class="text-center">
                                <td colspan='{{(count($data->eigen) + 1)}}'>Lamda Max</td>
                                <td colspan='1'>{{round($data->lamda['sumLamda'], 5)}}</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan='{{(count($data->eigen) + 1)}}'>IR Variable</td>
                                <td colspan='1'>{{round($data->lamda['IR'], 2)}}</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan='{{(count($data->eigen) + 1)}}'>Consistency Index (CI)</td>
                                <td colspan='1'>{{round($data->lamda['CI'], 5)}}</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan='{{(count($data->eigen) + 1)}}'>Consistency Ratio = CI / IR</td>
                                <td colspan='1'>{{round($data->lamda['constant'], 5)}}</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan='{{(count($data->eigen) + 1)}}'>Consistency Status</td>
                                <td colspan='1'>
                                    @if ($data->lamda['constant'] < 0.1)
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
    <!-- /.container-fluid -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('massRatioCriteria')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input id="_rowCriteria" type="text" name="row" hidden>
                        @foreach ($data->matrix as $key => $value )
                            @if ($key == 'sumCol')
                                @continue
                            @endif
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nilai terhadap : {{$key}}</label>
                                <input type="text" class="form-control" id="recipient-name" name="{{$key}}">
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var datas = button.data('whatever') // Extract info from data-* attributes
            var title = button.data('title') // Extract info from data-* attributes

            var modal = $(this)
            modal.find('.modal-title').text('Edit row Data = ' + title)
            modal.find('#_rowCriteria').val(title)
            modal.find('.modal-body input').attr('readonly', false)
            $.each(datas, function (indexInArray, valueOfElement) {
                modal.find('.modal-body input[name="' + indexInArray + '"]').val(valueOfElement)
                if (valueOfElement == 1) {
                    modal.find('.modal-body input[name="' + indexInArray + '"]').attr('readonly', true)
                }

            });
        })


    </script>


@endsection