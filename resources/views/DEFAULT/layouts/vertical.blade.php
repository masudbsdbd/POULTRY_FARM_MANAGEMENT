<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ $theme ?? 'light' }}" data-topbar-color="{{ $topbar ?? 'dark' }}"
      dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $title])
    @yield('css')
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
    @vite(['resources/scss/icons.scss', 'resources/js/head.js'])

</head>

<body>
<!-- Begin page -->
<div id="wrapper">
    @include('layouts.shared/left-sidebar')

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

@include('layouts.shared/right-sidebar')

@include('layouts.shared/footer-script')

@yield('script')

@vite(['resources/js/app.js', 'resources/js/layout.js'])
</body>


</html>
