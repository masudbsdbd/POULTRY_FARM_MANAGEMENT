@extends('layouts.vertical', ['title' => 'Material Symbols'])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Material Symbols Icons (Google Icon)','subtitle' => 'Icons'])

        <div class="row icons-list-demo">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">All Icons<a class="badge badge-soft-primary ms-2" href="https://fonts.google.com/icons" target="_blank">Google Icon</a></h4>
                        <div class="row icon-list-demo" id="icons"> </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/material-symbols.init.js'])
@endsection
