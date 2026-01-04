<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ $theme ?? 'light' }}" data-topbar-color="{{ $topbar ?? 'dark' }}"
    dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $title])
    @yield('css')
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
    @vite(['resources/scss/icons.scss', 'resources/js/head.js', 'resources/css/custom.css'])


    <!-- =================== use athother some script for datatables Start ==================== -->
    <script
	  src="https://code.jquery.com/jquery-3.1.1.min.js"
	  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
	  crossorigin="anonymous"></script>

	 <!-- datatables bootstrap script -->
	 <!--<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>-->
	 <!-- datatables style css -->
	 <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">-->
	 <!-- create datatables -->

    <!-- datatables script -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#basic-datatables').DataTable({
                paging: false,
            });
        });
    </script>

    <!-- =================== use athother some script for datatables End ==================== -->


</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">

        @if(Route::is('pos.index'))
        @include('layouts.shared.left-sidebar-pos')
        @else
        @include('layouts.shared.left-sidebar')
        @endif


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            @include('layouts.shared/topbar')
            <div class="content">
                <!-- content -->
                @yield('content')
            </div>
            @include('layouts.shared/footer')
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    @include('layouts.shared/footer-script')
    @include('includes.notify')
    @yield('script')
    @stack('script')
    @vite(['resources/js/app.js', 'resources/js/layout.js', 'resources/js/custom.js'])
</body>


</html>