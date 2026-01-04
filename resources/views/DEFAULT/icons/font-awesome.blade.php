@extends('layouts.vertical', ['title' => 'Font Awesome 5 Icons'])


@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Font Awesome Icons','subtitle' => 'Icons'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Solid</h4>
                        <p class="sub-header">Use <code>&lt;i class="fas fa-ad"&gt;&lt;/i&gt;</code> <span class="badge bg-success">v 5.13.0</span>.</p>

                        <div class="row icons-list-demo" id="solid">
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Regular</h4>
                        <p class="sub-header">Use <code>&lt;i class="far fa-address-book"&gt;&lt;/i&gt;</code> <span class="badge bg-success">v 5.13.0</span>.</p>

                        <div class="row icons-list-demo" id="regular">
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Brands</h4>
                        <p class="sub-header">Use <code>&lt;i class="fab fa-500px"&gt;&lt;/i&gt;</code> <span class="badge bg-success">v 5.13.0</span>.</p>

                        <div class="row icons-list-demo" id="brand">
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/fontawesome.init.js'])
@endsection
