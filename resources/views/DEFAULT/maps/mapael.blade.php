@extends('layouts.vertical', ['title' => 'Mapeal Maps'])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Mapeal Maps','subtitle' => 'Maps'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Map with a legend where slices are specified with a fixed value instead of min and max values</h4>
                        <div class="row justify-content-center" dir="ltr">
                            <div class="col-md-6">
                                <div class="map-usa">
                                    <div class="map">
                                        <span>Alternative content for the map</span>
                                    </div>
                                    <div class="plotLegend">
                                        <span>Alternative content for the legend</span>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Map with links between the plotted cities</h4>
                        <div class="mapcontainer">
                            <div class="map">
                                <span>Alternative content for the map</span>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/mapeal-maps.init.js'])
@endsection
