@extends('layouts.app')

@section('content')
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Input Data Angkutan</h1>
                    </div>


                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-lg-8 mb-4">
                            <!-- List Karyawan -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Angkutan</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <!-- <th scope="col">Jabatan</th>
                                            {{-- <th scope="col">Alamat</th> --}}
                                            <th scope="col">Jenis Kelamin</th> -->
                                            <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->employe as $users)
                                            <tr>
                                                <th scope="row">{{$loop->iteration}}</th>
                                                <td>{{ $users->name; }}</td>
                                                <!-- <td>{{ $users->position; }}</td>
                                                {{-- <td>{{ $users->address; }}</td> --}}
                                                <td>{{ $users->gender; }}</td>
                                                <td> -->
                                                {{-- @dd($users) --}}
                                                <button id="button-edit" class="btn btn-info btn-circle" data-toggle="modal" data-target="#exampleModal" data-modal="{{$users}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <a href="{{route('deleteKaryawan', ['employe' => $users->id ])}}" class="btn btn-danger btn-circle" >
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
                            <!-- Form Tambah Karyawan -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tambah Angkutan</h6>
                                </div>
                                <div class="card-body">
                                <form method="POST" action="{{ route('inputKaryawan') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputNama">Nama</label>
                                        <input type="text" class="form-control" id="inputNama" name="name">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="position" value="1">
                                        <!-- <label for="inputJabatan">Jabatan</label> -->
                                        <!-- <select class="form-control" id="inputJabatan" name="position">
                                            @foreach ($data->position as $jabatan)
                                            <option value="{{$jabatan->id}}">{{$jabatan->name}}</option>
                                            @endforeach

                                        </select> -->
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="inputGender">Gender</label>
                                        <select class="form-control" id="inputGender" name="gender">
                                            <option value="Laki-Laki">Laki-Laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAlamat">Alamat</label>
                                        <textarea class="form-control" id="inputAlamat" rows="3" name="address"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Date">Tanggal Masuk</label>
                                        <input type="date" class="form-control" id="Date" name="date">
                                    </div> -->

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Angkutan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('updateKaryawan') }}">
                                <div class="modal-body">
                                    @csrf
                                    <input id="id-karyawan" type="hidden" name="id">
                                    <div class="form-group">
                                        <label for="inputNama">Nama</label>
                                        <input type="text" class="form-control" id="inputNama" name="name">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="position" value="1">
                                        <!-- <label for="inputJabatan">Jabatan</label>
                                        <select class="form-control" id="inputJabatan" name="position">
                                            @foreach ($data->position as $jabatan)
                                            <option value="{{$jabatan->id}}">{{$jabatan->name}}</option>
                                            @endforeach
                                        </select> -->
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="inputGender">Gender</label>
                                        <select class="form-control" id="inputGender" name="gender">
                                            <option value="Laki-Laki">Laki-Laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAlamat">Alamat</label>
                                        <textarea class="form-control" id="inputAlamat" rows="3" name="address"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Date">Tanggal Masuk</label>
                                        <input type="date" class="form-control" id="inputTanggal" name="date">
                                    </div> -->
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
    $(".modal-body #id-karyawan").attr("value", datas.id);
    $(".modal-body #inputNama").val(datas.name);
    $(".modal-body #inputJabatan").val(datas.position);
    // $(".modal-body #inputJabatan").find("option").each(function(){
    //         if ($(this).text() == datas.position){
    //             $(this).attr("selected","selected");
    //         }
    //     });
    // $(".modal-body #inputGender").find("option").each(function(){
    //         if ($(this).text() == datas.gender){
    //             $(this).attr("selected","selected");
    //         }
    //     });
    // $(".modal-body #inputAlamat").val(datas.address);
    // $(".modal-body #inputTanggal").val(datas.date_in);
    });
  </script>
@endsection
