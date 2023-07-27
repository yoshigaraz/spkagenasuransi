@extends('layouts.app')

@section('content')
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Input Data Karyawan</h1>
                    </div>


                    <!-- Content Row -->
                    <div class="row">


                        <div class="col-lg-4 mb-4">
                            <!-- Form Tambah Karyawan -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Register User</h6>
                                </div>
                                <div class="card-body">
                                <form method="POST" action="{{ route('addUser') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputJabatan">User</label>
                                        <select class="form-control" id="inputJabatan" name="name">
                                            @foreach ($data->karyawan as $karyawans)
                                            <option value="{{$karyawans->name}}">{{$karyawans->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">Password</label>
                                        <input  type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputGender">Role Admin</label>
                                        <select class="form-control" id="inputGender" name="role">
                                            <option value='false' >Tidak</option>
                                            <option value='true' >Ya</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Create User</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 mb-4">
                            <!-- List Karyawan -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">List Data User</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->user as $users)
                                            <tr>
                                                <th scope="row">{{$loop->iteration}}</th>
                                                <td>{{ $users->name; }}</td>
                                                <td>
                                                    @if ($users->is_admin)
                                                        Admin
                                                    @else
                                                        User
                                                    @endif</td>
                                                <td>
                                                <button id="button-edit" class="btn btn-info btn-circle" data-toggle="modal" data-target="#exampleModal" data-modal="{{$users->name}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <a href="{{route('deleteUser', ['id' => $users->id ])}}" class="btn btn-danger btn-circle" >
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

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Karyawan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('addUser') }}">
                                <div class="modal-body">
                                    @csrf
                                    <input id="id-karyawan" type="hidden" name="id">
                                    <div class="form-group">
                                        <label for="inputNama">Nama</label>
                                        <input type="text" class="form-control" id="inputNama" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">Change Password</label>
                                        <input  type="password" class="form-control" id="Password" placeholder="Password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputRole">Role Admin</label>
                                        <select class="form-control" id="inputRole" name="role">
                                            <option value=false >Tidak</option>
                                            <option value=true >Ya</option>
                                        </select>
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
        console.log(raw);
    $(".modal-body #inputNama").val(raw);
    });
  </script>
@endsection
