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
                                <input type="month" class="form-control" name="period" value="{{$period}}"/>
                            </div>
                            <button type="submit" class="btn btn-primary">Hitung</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Metode Konvensional</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" id="tableKonvensional">
                            <thead>
                            <tr>
                                <th width="10">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
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
                                    <td>{{$value->total}}</td>
                                </tr>
                                <?php
                                        $num++;
                                ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Metode AHP</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" id="tableAhp">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Hasil</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Metode SAW</h6>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
