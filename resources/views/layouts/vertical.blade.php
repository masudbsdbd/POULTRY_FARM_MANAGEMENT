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

    {{-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600&display=swap" rel="stylesheet"> --}}
    {{-- <style>
        .VIpgJd-ZVi9od-ORHb-OEVmcd skiptranslate {
            display: none !important;
        }

        body {
            top: 0 !important;
            font-weight:bold;
        }
    </style> --}}

</head>

<body style="" id="google_translate_element">
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

    {{-- <script>
document.cookie = "googtrans=/en/bn; path=/";
</script>


<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'bn, en',
        autoDisplay: false
    }, 'google_translate_element');
}

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const el = document.querySelector('.VIpgJd-ZVi9od-ORHb-OEVmcd.skiptranslate');
        if (el) {
            el.style.display = 'none';
        }
    }, 1000);
});
</script> --}}

{{-- <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> --}}

    @include('layouts.shared/footer-script')
    @include('includes.notify')
    @yield('script')
    @stack('script')
    @vite(['resources/js/app.js', 'resources/js/layout.js', 'resources/js/custom.js'])
</body>


</html>