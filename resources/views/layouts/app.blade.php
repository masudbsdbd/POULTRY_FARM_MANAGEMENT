@extends('admin.layouts.master')

@section('content')
@include('admin.partials.sidenav')

<div class="main-content-wrap">
    <div class="container-fluid px-lg-0">

        @include('admin.partials.topnav')

        <div class="content-wrap">
            @include('admin.partials.breadcrumb')
            @yield('panel')
        </div>

    </div>
</div>
@endsection