@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Kriteria</h1>
        </div>


        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-12">
                <!-- Illustrations -->
                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Kriteria </h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-12 mb-12" method="POST" action="{{route('addCriteria')}}">
                            @csrf
                            <div class="col-3">
                                <input type="text" class="form-control" id="inputCode" placeholder="Kode Kriteria"
                                       name="code">
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" id="inputCriteria" placeholder="Nama Kriteria"
                                       name="name">
                            </div>
                            <div class="col-2">
                                <input type="number" class="form-control" id="inputWeight" placeholder="Bobot"
                                       name="weight">
                            </div>
                            <div class="col-2">
                                <select name="attribute" class="form-control">
                                    <option value="BENEFIT">BENEFIT</option>
                                    <option value="COST">COST</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary mb-3">Tambah</button>
                            </div>
                        </form>
                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <th scope="col" width="5">#</th>
                                <th scope="col" width="200">Kode Kriteria</th>
                                <th scope="col">Nama Kriteria</th>
                                <th scope="col" width="200">Atribut</th>
                                <th scope="col" width="200">Bobot</th>
                                <th scope="col" width="15">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data->criteria as $criteria)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{ $criteria['code']}}</td>
                                    <td>{{ $criteria['name']}}</td>
                                    <td>{{ $criteria['attribute']}}</td>
                                    <td>{{ $criteria['weight']}}</td>
                                    <td>
                                        <a href="{{route('deleteCriteria', ['criteria' => $criteria['id']])}}"
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
    </div>
    <!-- /.container-fluid -->
@endsection
@section('js')
    <script>
        new DataTable('#table');
    </script>
@endsection
