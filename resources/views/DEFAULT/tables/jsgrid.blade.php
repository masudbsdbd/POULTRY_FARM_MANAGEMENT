@extends('layouts.vertical', ['title' => 'JsGrid Tables'])

@section('css')
    @vite(['node_modules/jsgrid/dist/jsgrid.min.css','node_modules/jsgrid/dist/jsgrid-theme.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'JsGrid Tables','subtitle' => 'Tables'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="jsGrid"></div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/jsgrid.init.js'])
@endsection
