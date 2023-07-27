
@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Form Gaji Dan Bonus</h1>
        </div>


        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-8 mb-4">
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Bonus </h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 mb-3" method="POST" action="{{route('addBonus')}}">
                            @csrf
                            <div class="col-6">
                                <input type="text" class="form-control" id="inputCriteria" placeholder="Nama Bonus" name="name">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="inputCriteria" placeholder="Nominal" name="value">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary mb-3">Tambah</button>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $data->bonus as $bonuses)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <td>{{$bonuses['name']}}</td>
                                    <td>{{$bonuses['value']}}</td>
                                    <td>
                                        <button id="button-edit-bonus" class="btn btn-info btn-circle" data-toggle="modal" data-target="#modalBonus" data-modal="{{json_encode($bonuses)}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a href="{{route('deleteBonus',['id' => $bonuses['id']])}}" class="btn btn-danger btn-circle" >
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
        </div>
        <div class="row">
            <!-- List -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Gaji</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <form class="row g-3" method="GET" action="{{route('filterpayout')}}">
                                @csrf
                                <div class="col-auto">
                                    <input type="month" class="form-control" id="filterPeriode" name="date" value="@if($data->date == null){{Carbon\Carbon::now()->format('Y-m')}}@else{{$data->date}}@endif">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mb-3">Filter</button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{route('print', ['date' => $data->date !== null ? $data->date : Carbon\Carbon::now()->format('Y-m')])}}" id="print" class="btn btn-info mb-3">Print Report</a>
                                </div>
                            </form>
                        </div>
                        <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jabatan</th>
                                    <th scope="col">Gaji</th>
                                    <th scope="col">Bonus</th>
                                    <th class="text-center" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($data->payout as $payouts)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$payouts['name']}}</td>
                                    <td>{{$payouts['position']}}</td>
                                    <td>{{$payouts['value']}}</td>
                                    @if ($payouts['bonus_value'] == null)
                                    <td>-</td>
                                    @else
                                    <td>{{$payouts['bonus_name']}}</td>
                                    @endif
                                    <td class="text-center">
                                        <button id="button-edit" class="btn btn-info btn-circle" data-toggle="modal" data-target="#exampleModal" data-modal="{{json_encode($payouts)}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a href="{{route('deletePayout',['id' => $payouts['id']])}}" class="btn btn-danger btn-circle" >
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

            <!-- Form Tambah Karyawan -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Gaji Karyawan</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('addPayout')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="inputPeriode">Periode</label>
                                    <input type="date" class="form-control" id="inputNama" name="period">
                                </div>
                                <div class="form-group">
                                    <label for="bonusOption">Karyawan</label>
                                    <select class="form-control" id="bonusOption" name="employe_id" >
                                        @foreach ($data->employe as $employes)
                                        <option value="{{$employes['id']}}">{{$employes['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="bonusOption">Bonus</label>
                                    <select class="form-control" id="bonusStatus" name="bonus_id" >
                                        <option value=''>Tidak Menerima Bonus</option>
                                        @foreach ($data->bonus as $bonuses)
                                        <option value="{{$bonuses['id']}}">{{$bonuses['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputAlamat">Gaji Pokok</label>
                                    <input class="form-control" id="inputGaji" rows="3" name="value">
                                </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<!-- /Modal-->


        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('updatePayout')}}">
                    <div class="modal-body">
                        @csrf
                        <input id="id-gaji" type="hidden" name="id">
                        <div class="form-group">
                            <label for="inputNama">Nama</label>
                            <input type="text" class="form-control" id="inputNamaBonus" name="name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="bonusOption">Bonus</label>
                            <select class="form-control" id="bonusOption" name="bonus" >
                                <option value=''>Tidak Menerima Bonus</option>
                                @foreach ($data->bonus as $bonuses)
                                <option value="{{$bonuses['id']}}">{{$bonuses['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputAlamat">Gaji Pokok</label>
                            <input class="form-control" id="inputGaji" rows="3" name="value">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalBonus" tabindex="-1" role="dialog" aria-labelledby="modalBonusLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBonusLabel">Edit Data Bonus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('updateBonus')}}">
                    <div class="modal-body">
                        @csrf
                        <input id="id-bonus" type="hidden" name="id">
                        <div class="form-group">
                            <label for="inputNama">Nama</label>
                            <input type="text" class="form-control" id="inputNama" name="name">
                        </div>
                        <div class="form-group">
                            <label for="inputAlamat">Bonus</label>
                            <input class="form-control" id="inputBonus" rows="3" name="value">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('js')
    <script>
    $(document).on("click", "#button-edit", function () {
     var raw = $(this).attr('data-modal');
     var datas = JSON.parse(raw);
        console.log(datas);
    $(".modal-body #id-gaji").attr("value", datas.id);
    $(".modal-body #inputNamaBonus").val(datas.name);
    $(".modal-body #bonusOption").find("option").each(function(){
            if ($(this).text() == datas.bonus_name){
                $(this).attr("selected","selected");
            }
        });
    $(".modal-body #inputGaji").val(datas.value);
    });

    $(document).on("click", "#button-edit-bonus", function () {
     var raw = $(this).attr('data-modal');
     var datas = JSON.parse(raw);
    $(".modal-body #id-bonus").attr("value", datas.id);
    $(".modal-body #inputNama").val(datas.name);
    $(".modal-body #inputBonus").val(datas.value);
    });

    $('#bonusOption').on('change', function() {
        var karyawan = $(this).find(":selected").text();

    });
  </script>
@endsection
