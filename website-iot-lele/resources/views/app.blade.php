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

        .custom-tab {
            font-size: 1.1rem;
            font-weight: 600;
            color: #6c757d; /* Soft gray */
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
            border: none;
            border-bottom: 2px solid transparent;
        }

        .custom-tab:hover, .custom-tab:focus {
            color: #212529; /* Darker text */
            border-bottom: 2px solid #6c757d;
        }

        .custom-tab.active {
            color: #212529;
            border-bottom: 3px solid #007bff; /* Blue accent for active tab */
            background: none;
        }

        .nav-tabs {
            border-bottom: 1px solid #ddd;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="opening">
    
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            <a href="/dasbor" class="logo">
                <span>
                    <img src="{{ asset('images/simko-icon.png') }}" alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="{{ asset('images/simko.png') }}" alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu" >
            <div class="startbar-toggle-pointer nav-link mobile-menu-btn" id="toggleStartbar">
                <i class="fas fa-align-justify"></i>
            </div>
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label mt-2">
                            <span>Menu</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/dasbor">
                                <i class="iconoir-reports menu-icon"></i>
                                <span>Dasbor</span>
                            </a>
                        </li><!--end nav-item-->

                        <li class="nav-item">
                            <a class="nav-link" href="/kolam">
                                <i class="iconoir-report-columns menu-icon"></i>
                                <span>Kolam</span>
                            </a>
                        </li><!--end nav-item-->

                        <li class="nav-item">
                            <a class="nav-link" href="/riwayat">
                                <i class="iconoir-cinema-old menu-icon"></i>
                                <span>Riwayat</span>
                            </a>
                        </li><!--end nav-item-->
                    </ul><!--end navbar-nav--->
                </div>
            </div><!--end startbar-collapse-->
        </div><!--end startbar-menu-->    
    </div><!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->
   
</div>

  <!-- Main content -->

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            

            @yield('content')
        </div>
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
