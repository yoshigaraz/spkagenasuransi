
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="sidebar-brand-text mx-3">AHP System</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            @if (Auth::user()->is_admin)


            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Administrator
            </div>

            <!-- Nav Item - Pages Static Menu -->
            <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('karyawan')}}">
                    <i class="fa fa-address-book"></i>
                    <span>Data Karyawan</span></a>
                </li>
            </li>

            <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('criteriaData')}}">
                    <i class="fa fa-address-book"></i>
                    <span>Data Krtiteria</span></a>
                </li>
            </li>
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-clipboard-list"></i>
                <span>Kriteria</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"> Perhitungan Kriteria:</h6>
                        <a class="collapse-item" href="{{route('criteria')}}">Master Kriteria</a>
                        <a class="collapse-item" href="{{route('ratioCriteria')}}">Perbandingan Kriteria</a>
                        <a class="collapse-item" href="{{route('ratioCriteria')}}">Hasil hitung Kriteria</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAlternatif"
                aria-expanded="true" aria-controls="collapseAlternatif">
                <i class="far fa-address-book"></i>
                <span>Alternatif</span>
                </a>
                <div id="collapseAlternatif" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Perhitungan Alternatif:</h6>
                        <a class="collapse-item" href="{{route('alternative')}}">Master Alternatif</a>
                        <a class="collapse-item" href={{route('ratioAlternative')}}>Perbandingan Alternatif</a>
                        <a class="collapse-item" href="{{route('resultAlternative')}}">Hasil hitung Alternatif</a>
                    </div>
                </div>
            </li>

            <!-- <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('payout')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Data Gaji</span></a>
                </li>
            </li>

            <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('userList')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Daftar User</span></a>
                </li>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider">
                {{-- <li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Rekap Penilaian</span></a>
                    </li>
                </li> --}}
            @endif


            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
