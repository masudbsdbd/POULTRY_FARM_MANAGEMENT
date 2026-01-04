@extends('layouts.vertical', ['title' => 'C3 Charts'])

@section('css')
    @vite(['node_modules/c3/c3.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'C3 Charts','subtitle' => 'Charts'])

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Bar Chart</h4>
                        <div id="chart" style="height: 300px;" data-colors="#dcdcdc,#4a81d4,#1abc9c" dir="ltr">
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Stacked Area Chart</h4>
                        <div id="chart-stacked" style="height: 300px;" data-colors="#1abc9c,#4a81d4" dir="ltr"></div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- End row -->


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Roated Chart</h4>
                        <div id="roated-chart" style="height: 300px;" data-colors="#1abc9c,#4a81d4" dir="ltr"></div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Combine Chart</h4>
                        <div id="combine-chart" style="height: 300px;" data-colors="#dcdcdc,#4a81d4,#36404a,#fb6d9d,#1abc9c" dir="ltr"></div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- End row -->


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Donut Chart</h4>
                        <div id="donut-chart" style="height: 300px;" data-colors="#f4f8fb,#4a81d4,#1abc9c" dir="ltr">
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Pie Chart</h4>
                        <div id="pie-chart" style="height: 300px;" data-colors="#f4f8fb,#4a81d4,#1abc9c" dir="ltr">
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- End row -->


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Scatter Plot</h4>
                        <div id="scatter-plot" style="height: 300px;" data-colors="#4a81d4,#1abc9c,#4a81d4,#1abc9c" dir="ltr"></div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Line Chart with Regions</h4>
                        <div id="line-regions" style="height: 300px;" data-colors="#4a81d4,#fb6d9d" dir="ltr"></div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- End row -->


    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/c3.init.js'])
@endsection
