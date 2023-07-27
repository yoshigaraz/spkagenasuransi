@extends('layouts.app')

@section('content')
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        @if (Auth::user()->is_admin)
                        <div class="col-lg-12 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">About This System</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="...">
                                    </div>
                                    <p>Sistem yang akan dibangun adalah sebuah sistem pendukung keputusan pemberian gaji kepada karyawan menggunakan metode analytical hierarchy proses dimana kinerja karyawan dapat dinilai dengan parameter yang ditentukan.
                                     Seluruh kinerja karyawan tersebut dapat dirangking dan pemberian bonus dapat diberikan kepada karyawan dengan peringkat terbaik</p>
                                </div>
                            </div>
                        </div>
                        @else
                            @if($data->target)
                            @foreach ($data->target as $target)
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    {{$target->name}}</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    @if ($target->value > 10000)
                                                    Rp. {{number_format($target->value)}}
                                                    @else
                                                    {{$target->value}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-bookmark fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                                {{-- <div class="col-lg-12 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Total Penilaian dari semua kriteria anda :
                                            <div class="text-white-50 small">{{ round($data->rank * 100 , 2)}} Poins</div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-lg-4 mb-4">
                                    <!-- Illustrations -->
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Hasil Perhitungan AHP</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">Rank</th>
                                                    <th scope="col">Nama</th>
                                                    <th scope="col">Poin</th>
                                                    <th scope="col">Persentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @dd($data->rank) --}}
                                                    @foreach ($data->rank as $rank => $value)
                                                    @if ($rank == 'totalpoins')
                                                        @continue
                                                    @else
                                                    <tr>
                                                        <th scope="row">{{$loop->iteration}}</th>
                                                        <td >{{$rank}}</td>
                                                        <td >{{round($value,3)}}</td>
                                                        <td >{{round(($value/$data->rank['totalpoins']) * 100 ,1)}} %</td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-6">
                                    <!-- Illustrations -->
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Rekap Penerimaan Gaji</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Tanggal</th>
                                                    <th scope="col">Gaji Pokok</th>
                                                    <th scope="col">Bonus</th>
                                                    <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @dd($data->rank) --}}
                                                    @foreach ($data->payout as $payouts)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$payouts['period']}}</td>
                                                        <td>{{$payouts['value']}}</td>
                                                        @if ($payouts['bonus_value'] == null)
                                                        <td>-</td>
                                                        @else
                                                        <td>{{$payouts['bonus_value']}}</td>
                                                        @endif
                                                        <td>{{$payouts['bonus_value'] + $payouts['value']}}</td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                @else
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Anda belum memiliki data
                                            <div class="text-white-50 small"></div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                    </div>
                    {{-- @dd($data) --}}

                </div>
                <!-- /.container-fluid -->
@endsection
