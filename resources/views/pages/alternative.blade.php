
@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Alternatif</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 mb-12">
                <!-- Illustrations -->
                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Alternatif </h6>
                    </div>
                    <div class="card-body">
                        <form class="row g-12 mb-12" method="POST" action="{{route('addAlternative')}}">
                            @csrf
                            <div class="form-group col-3">
                                <select class="form-control" name="criteria">
                                    <option selected>Pilih Kriteria</option>
                                    @foreach ($data->criteria as $criteria)
                                        <option value={{ $criteria['id'] }}>{{ $criteria['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" id="inputCriteria" placeholder="Deskripsi"
                                       name="description">
                            </div>
                            <div class="col-2">
                                <input type="number" class="form-control" id="inputWeight" placeholder="Min"
                                       name="min_value">
                            </div>
                            <div class="col-2">
                                <input type="number" class="form-control" id="inputWeight" placeholder="Max"
                                       name="max_value">
                            </div>
                            <div class="col-2">
                                <input type="number" class="form-control" id="inputWeight" placeholder="Bobot"
                                       name="weight">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary mb-3">Tambah</button>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" width="5">#</th>
                                <th scope="col">Kriteria</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Nilai Min</th>
                                <th scope="col">Nilai Max</th>
                                <th scope="col" width="200">Bobot</th>
                                <th scope="col" width="15">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data->alternative as $value)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{ $value['criteria']}}</td>
                                    <td>{{ $value['description']}}</td>
                                    <td>{{ number_format($value['min_value'])}}</td>
                                    <td>{{ number_format($value['max_value'])}}</td>
                                    <td>{{ $value['weight']}}</td>
                                    <td>
                                        <a href="{{route('deleteAlternative', ['alternative' => $value['id']])}}"
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
