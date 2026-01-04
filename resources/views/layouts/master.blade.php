<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>asdfasdf</title>
    {{-- <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') .'/' . @$general->image->favicon) }}">
    <link rel="stylesheet" href="{{ asset('assets/general/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/general/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/general/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/general/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/plugins/datepicker.min.css')}}"> --}}

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}">

    @stack('style')
</head>

<body>

    <div class="dashboard-body">
        @yield('content')
    </div>

    {{-- @php
        $generals = App\Models\SiteSetting::first()->date_format;
    @endphp --}}
    {{-- <script src="{{ asset('assets/general/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/general/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/general/js/select2.min.js')}}"></script>

    @include('partials.notify')
    @stack('script-lib')

    <script src="{{ asset('assets/admin/js/plugins/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script> --}}



    @stack('script')
</body>

</html>
