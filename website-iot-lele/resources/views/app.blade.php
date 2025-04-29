<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Kolam</title>
    <link rel="shortcut icon" href="{{ asset('images/simko-icon.png') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gaugeJS/dist/gauge.min.js"></script>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
    <style>

        @font-face {
            font-family: 'Play-Bold';
            src: url('/fonts/Play-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Montserrat-Regular';
            src: url('/fonts/Montserrat-Medium.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>

<body>
    <div class="opening">

        <!-- Top Bar Start -->
        <div class="topbar d-print-none">
                <div class="container-fluid">
                    <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">    
                        <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">                        
                            <li>
                                <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                                    <i class="iconoir-menu"></i>
                                </button>
                            </li> 
                            <li class="mx-2 welcome-text">
                                <h5 class="mb-0 fw-semibold text-truncate">Selamat Datang, {{ Auth::user()->name }}!</h5>
                            </li>                   
                        </ul>
                        <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                            <li class="date-time">
                                <h5 class="mb-0 fw-semibold text-truncate">
                                    <span id="tanggal-jam" style="display: inline-block; min-width: 320px; font-family: monospace; background-color: #4682b4; text-align: center; padding: 6px 20px; border-radius: 20px; color: white;"></span>
                                </h5>
                                <script>
                                    function updateTanggalJam() {
                                        const now = new Date();

                                        const optionsTanggal = {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                        };

                                        const tanggal = now.toLocaleDateString('id-ID', optionsTanggal);

                                        const jam = String(now.getHours()).padStart(2, '0');
                                        const menit = String(now.getMinutes()).padStart(2, '0');
                                        const detik = String(now.getSeconds()).padStart(2, '0');

                                        document.getElementById('tanggal-jam').textContent = `${tanggal} | ${jam}:${menit}:${detik}`;
                                    }

                                    updateTanggalJam(); 
                                    setInterval(updateTanggalJam, 1000);
                                </script>
                            </li> 
                        </ul>
                    </nav>
                </div>
            </div>
        
        <!-- leftbar-tab-menu -->
        <div class="startbar d-print-none">
            <!--start brand-->
            <div class="brand">
                <a href="/dasbor" class="logo">
                    <span>
                        <img src="{{ asset('images/simko-icon.png') }}" alt="logo-small" class="logo-sm mt-3">
                    </span>
                    <span class="">
                        <img src="{{ asset('images/simko.png') }}" alt="logo-large" class="logo-lg logo-dark">
                    </span>
                </a>
            </div>

            <!--start startbar-menu-->
            <div class="startbar-menu" >
                <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                    <div class="d-flex align-items-start flex-column w-100">
                        <!-- Navigation -->
                        <ul class="navbar-nav mb-auto w-100">
                            <li class="menu-label mt-2">
                                <span>Menu</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/dasbor">
                                    <i class="iconoir-graph-up menu-icon"></i>
                                    <span>Dasbor</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/kolam">
                                    <i class="iconoir-fish menu-icon"></i>
                                    <span>Kolam</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/riwayat">
                                    <i class="iconoir-archive menu-icon"></i>
                                    <span>Riwayat</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Navigation -->
                        <ul class="navbar-nav mb-auto w-100">
                            <li class="menu-label mt-2">
                                <span>Akun</span>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/pengaturan">
                                    <i class="iconoir-user-square menu-icon"></i>
                                    <span>Pengaturan</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="iconoir-undo-circle menu-icon"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="startbar-overlay d-print-none"></div>
    </div>

    <!-- Main content -->

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid mb-4">
                @yield('content')
            </div>

            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        ©
                                        <script> document.write(new Date().getFullYear()) </script>
                                        SIMKO - Sistem Monitoring Kolam
                                        <span class="text-muted d-none d-sm-inline-block float-end">| by Information Systems UPH 2022</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div> 
    </div>


    <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/pages/index.init.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
