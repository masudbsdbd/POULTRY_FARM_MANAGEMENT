@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        {{-- @include('layouts.shared.page-title', [
            'title' => $pageTitle,
            'subtitle' => $pageTitle,
        ]) --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h4 class="header-title">{{ $pageTitle }}</h4> --}}

                        <div class="button-list">
                            <form id="myForm" action="" method="get">

                                <input type="hidden" name="action" id="form-action" value="search">

                                <div class="row justify-content-start">
                                    <!-- Start Date Field -->


                                    <div class="col-md-3 pe-0">
                                        <div class="basicDatepicker {{ request('range') ? 'd-none' : '' }}">
                                            <p class="fw-bold text-muted">As of Date</p>
                                            <div class="input-group input-group-merge">
                                                <input type="text" name="date" id="basic-datepicker"
                                                    class="form-control" placeholder="Select Date"
                                                    value="{{ old('date', request('date')) }}">
                                                <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                            </div>
                                        </div>


                                        <div class="rangeDatepicker {{ request('range') ? '' : 'd-none' }}">
                                            <p class="fw-bold text-muted">Choose Date Range</p>
                                            <div class="input-group input-group-merge">
                                                <input type="text" id="range-datepicker" class="form-control"
                                                    name="range" placeholder="Ex. 2018-10-03 to 2018-10-10"
                                                    value="{{ old('date', request('range')) }}">
                                                <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-md-6 ps-0">
                                                <button type="submit" class="btn btn-primary w-100"
                                                    onclick="document.getElementById('form-action').value='search'"
                                                    style="margin-top: 36px;">
                                                    Search
                                                </button>
                                            </div>
                                            {{-- <div class="col-md-6 ps-0">
                                                <button type="button" class="btn btn-success w-100 printBtn"
                                                    style="margin-top: 36px;">
                                                    <i class="mdi mdi-printer"></i> Print
                                                </button>
                                            </div> --}}
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <p>
                            @if (request('date'))
                                Search Date: ( {{ request('date') }} )
                            @elseif(request('range'))
                                Search Date: ( {{ request('range') }} )
                            @endif
                        </p>


                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->


    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.printBtn', function() {
                    document.getElementById('form-action').value = 'print';
                    $('#myForm').attr('target', '_blank');
                    $('#myForm').submit();
                });

                $(document).on('click', '.clear-btn', function() {
                    $('.basicDatepicker input').val('');
                    $('.rangeDatepicker input').val('');
                    $('#type-select').val('');
                });

                $('#type-select').on('change', function() {
                    const selectedValue = $(this).val();

                    if (selectedValue == '') {
                        $('.basicDatepicker input').val('');
                        $('.rangeDatepicker input').val('');
                        $('.basicDatepicker input').prop('disabled', true);
                        $('.rangeDatepicker input').prop('disabled', true);
                    } else if (selectedValue == '1') {
                        $('.basicDatepicker').removeClass('d-none');
                        $('.basicDatepicker input').prop('disabled', false);

                        $('.rangeDatepicker').addClass('d-none');
                        $('.rangeDatepicker input').val('');
                    } else if (selectedValue == '2') {
                        $('.rangeDatepicker').removeClass('d-none');
                        $('.rangeDatepicker input').prop('disabled', false);

                        $('.basicDatepicker').addClass('d-none');
                        $('.basicDatepicker input').val('');
                    }
                });


            })(jQuery);
        });
    </script>
@endsection
