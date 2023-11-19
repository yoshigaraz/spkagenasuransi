@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Hasil Perangkingan Agen</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form class="form-inline" method="GET" action="{{route('getRank')}}">
                            <div class="form-group">
                                <label for="period">Pilih Periode</label>
                                <div>
                                    @php
                                        $bulanRange = range(1,12);
                                        $tahunRange = range(2015,2025);
                                    @endphp
                                    <select id="bulanSelect" class="form-control" name="month">
                                        <option value="">Pilih Bulan</option>
                                        @foreach($bulanRange as $bulan)
                                            <option value="{{$bulan}}">{{$bulan}}</option>
                                        @endforeach
                                    </select>
                                    {{--<select class="form-control" name="month">--}}
                                        {{--<option value="">Pilih Bulan</option>--}}
                                        {{--<option value="01">01</option>--}}
                                        {{--<option value="02">02</option>--}}
                                        {{--<option value="03">03</option>--}}
                                        {{--<option value="04">04</option>--}}
                                        {{--<option value="05">05</option>--}}
                                        {{--<option value="06">06</option>--}}
                                        {{--<option value="07">07</option>--}}
                                        {{--<option value="08">08</option>--}}
                                        {{--<option value="09">09</option>--}}
                                        {{--<option value="10">10</option>--}}
                                        {{--<option value="11">11</option>--}}
                                        {{--<option value="12">12</option>--}}
                                    {{--</select>--}}
                                </div>

                                <div>
                                    <select class="form-control" name="year">
                                        <option value="">Pilih Tahun</option>
                                        @foreach($tahunRange as $tahun)
                                            <option value="{{$tahun}}">{{$tahun}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--<input type="number" class="form-control"  id="yearInput" name="year" min="2020" max="2025" placeholder="Tahun">--}}
                                {{--<input type="month" class="form-control" name="period" id="period" onchange="ubahTampilanBulan(this)" value="{{$period}}"/>--}}
                            </div>
                            <button type="submit" onclick="getMonthNumber()" class="btn btn-primary">Hitung</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            {{--<div class="col-lg-4 mb-4">--}}
                {{--<div class="card shadow mb-4">--}}
                    {{--<div class="card-header py-3">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-10">--}}
                                {{--<h6 class="m-0 font-weight-bold text-primary">Metode Konvensional</h6>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-2 float-right">--}}
                                {{--<a class="btn btn-sm btn-circle btn-info" data-toggle="modal"--}}
                                   {{--data-target="#modalKonvensional"><i class="fa fa-info-circle"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body">--}}
                        {{--<table class="table" id="tableKonvensional">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th width="10">#</th>--}}
                                {{--<th>Kode</th>--}}
                                {{--<th>Nama</th>--}}
                                {{--<th>Total Poin</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<?php--}}
                            {{--$num = 1;--}}
                            {{--?>--}}
                            {{--@foreach($conventional as $key => $value)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$num}}</td>--}}
                                    {{--<td>{{$value->code}}</td>--}}
                                    {{--<td>{{$value->name}}</td>--}}
                                    {{--<td>{{$value->total}}</td>--}}
                                {{--</tr>--}}
                                {{--<?php--}}
                                {{--$num++;--}}
                                {{--?>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-lg-6 mb-6">
                <div class="card shadow mb-6">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-10">
                                <h6 class="m-0 font-weight-bold text-primary">Metode AHP</h6>
                            </div>
                            <div class="col-md-2 float-right">
                                <a class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                   data-target="#modalAhp"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Hasil</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ahp as $key => $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->code}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{round($value->total_point,4)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-6">
                <div class="card shadow mb-6">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-10">
                                <h6 class="m-0 font-weight-bold text-primary">Metode SAW</h6>
                            </div>
                            <div class="col-md-2 float-right">
                                <a class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                   data-target="#modalSaw"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="myTableSaw">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($saw as $key => $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->code}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{round($value->total_point,4)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('up')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

@endpush
@push('down')

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        let table = new DataTable('#myTable');

        let tableSaw = new DataTable('#myTableSaw');
    </script>
    <script>
        function ubahTampilanBulan(input) {
            const date = new Date(input.value);
            const year = date.getFullYear();
            const month = date.getMonth() + 1; // Mengambil bulan (mulai dari 0)
            const monthString = month < 10 ? '0' + month : '' + month; // Format bulan 2 digit

            console.log(month)
            input.value = year + '-' + monthString // Menampilkan dalam format YYYY-MM
        }

    </script>
@endpush
