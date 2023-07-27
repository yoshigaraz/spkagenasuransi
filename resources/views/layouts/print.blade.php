@extends('layouts.guest')

@section('content')
            <!-- Main Content -->
            <div id="content">

                <br>
                <h1 class="text-center">Report Gaji</h1>
                <h3 class="text-center">Periode : {{$data->date}} </h3>
                <br>
                <hr>
                <br>

                <table class="table table-bordered">
                    <h4 class="text-center">Rekap Gaji Karyawan</h4>
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Gaji Pokok</th>
                    <th scope="col">Bonus</th>
                    <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data->employe as $employeData)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>{{$employeData->name}}</td>
                            <td>{{$employeData->position}}</td>
                            <td>{{$employeData->value}}</td>
                            <td>{{$employeData->bonus_value == null ?  '-' : $employeData->value}}</td>
                            <td>{{$employeData->value + $employeData->bonus_value }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
                <br>
                <hr>
                <br>
                <table class="table table-bordered">
                    <h4 class="text-center">Penilaian Karyawan</h4>
                    @foreach ($data->eigen as $criteriaName => $value)
                     <tr>
                         @if ($loop->first)
                         <th>Nama</th>
                         @endif
                         <th class="text-center">{{$criteriaName}}</th>
                     </tr>
                     @foreach ($value['eigen'] as $keyName => $prop)
                     <tr>
                         @if ($keyName != 'sumEigen')
                         <th scope="col">{{ $keyName; }}</th>
                            @foreach ($prop as $key => $props)
                            @if ($key == 'totalEigen')
                            <td class="text-center" >{{  round( $props / $value['eigen']['sumEigen']['totalEigen'], 3); }}</td>
                            @endif
                            @endforeach

                            @endif
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
                </table>
@endsection
