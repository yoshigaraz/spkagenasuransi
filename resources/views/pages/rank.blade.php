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
                        <div class="row">
                            <div class="col-md-10">
                                <h6 class="m-0 font-weight-bold text-primary">Metode Konvensional</h6>
                            </div>
                            <div class="col-md-2 float-right">
                                <a class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                   data-target="#modalKonvensional"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>
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
                        <table class="table" id="tableAhp">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
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
                                    <td>{{round($value->total_point,4)}}</td>
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
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
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
                                    <td>{{round($value->total_point,4)}}</td>
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
        </div>
        <!-- Modal Konvensional-->
        <div class="modal fade" id="modalKonvensional" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Metode Konvensional</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="tableKonvensionalModal">
                            <thead>
                            <tr>
                                <th width="10">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                @foreach($criteria as $c)
                                    <th class="text-center">{{$c->name}}</th>
                                @endforeach
                                <th class="text-center">Total Poin</th>
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
                                        <td class="text-center">{{$v->point}}</td>
                                    @endforeach
                                    <th class="text-center">{{$value->total}}</th>
                                </tr>
                                <?php
                                $num++;
                                ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{url('/rank/print/conventional/'.$period)}}" target="_blank" type="button"
                           class="btn btn-danger"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal AHP-->
        <div class="modal fade" id="modalAhp" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Metode AHP</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="tableAhp">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                @foreach($criteria as $c)
                                    <th class="text-center">{{$c->name}}</th>
                                @endforeach
                                <th class="text-center">Hasil</th>
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
                                        <td class="text-center">{{round($v->point, 4)}}</td>
                                    @endforeach
                                    <th>{{round($value->total_point,4)}}</th>
                                </tr>
                                <?php
                                $num++;
                                ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{url('/rank/print/ahp/'.$period)}}" target="_blank" type="button"
                           class="btn btn-danger"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal SAW-->
        <div class="modal fade" id="modalSaw" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Metode SAW</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                @foreach($criteria as $c)
                                    <th class="text-center">{{$c->name}}</th>
                                @endforeach
                                <th class="text-center">Total</th>
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
                                        <td class="text-center">{{round($v->point, 4)}}</td>
                                    @endforeach
                                    <th class="text-center">{{round($value->total_point,4)}}</th>
                                </tr>
                                <?php
                                $num++;
                                ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{url('/rank/print/saw/'.$period)}}" target="_blank" type="button"
                           class="btn btn-danger"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
