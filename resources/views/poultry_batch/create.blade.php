@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection


@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Customer'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($batch) ? route('poultrybatch.update', $batch->id) : route('poultrybatch.store') }}"
                                method="POST">
                                @csrf
                                @isset($batch)
                                    @method('PUT')
                                @endisset

                                <div class="row">

                                    {{-- Customer --}}
                                    <div class="mb-3 col-md-6 {{ isset($customer_id) ? 'd-none' : '' }}">
                                        <label for="customer_id" class="form-label">Select Customer</label>
                                        <select class="form-select" name="customer_id" required data-toggle="select2">
                                            <option value="">Choose Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    @selected((old('customer_id', $batch->customer_id ?? '') == $customer->id) ||( isset($customer_id) && $customer_id == $customer->id))>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Batch Name --}}
                                    <div class="mb-3  {{ isset($customer_id) ? 'col-md-12' : 'col-md-6' }}">
                                        <label for="batch_name" class="form-label">Batch Name</label>
                                        <input type="text" class="form-control" name="batch_name" placeholder="Enter batch name" required
                                            value="{{ old('batch_name', $batch->batch_name ?? '') }}">
                                    </div>

                                    {{-- Batch Number --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="batch_number" class="form-label">Batch Number(not required!)</label>
                                        <input type="text" class="form-control" name="batch_number" placeholder="Enter batch number"
                                            value="{{ old('batch_number', $batch->batch_number ?? '') }}">
                                    </div>

                                    {{-- Chicken Type Input --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="chicken_type" class="form-label">Chicken Type (write manually)</label>
                                        <input type="text" class="form-control" id="chicken_type" name="chicken_type"
                                            placeholder="Enter chicken type" required
                                            value="{{ old('chicken_type', $batch->chicken_type ?? '') }}">
                                    </div>

                                    {{-- Chicken Type Select --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="chickenTypeSelect" class="form-label">Or Select Chicken Type</label>
                                        <select id="chickenTypeSelect" class="form-control">
                                            <option value="">Select Chicken Type</option>
                                            <option value="broiler">Broiler</option>
                                            <option value="shonali">Sonali</option>
                                            <option value="sonali_hybrid">Sonali Hybrid</option>
                                            <option value="color_bird">Color Bird</option>
                                            <option value="layer">Layer</option>
                                            <option value="deshi">Deshi</option>
                                            <option value="fayoumi">Fayoumi</option>
                                            <option value="koel">Koel</option>
                                        </select>

                                        <!-- Hidden Input to store value for form submission -->
                                        <input type="hidden" name="chicken_type" id="chickenTypeInput"
                                            value="{{ old('chicken_type', $batch->chicken_type ?? '') }}">
                                    </div>

                                    {{-- Total Chickens --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="total_chickens" class="form-label">Total Chickens</label>
                                        <input type="number" class="form-control" id="total_chickens" name="total_chickens" placeholder="Enter total number of chickens"
                                            required value="{{ old('total_chickens', $batch->total_chickens ?? '') }}">
                                    </div>

                                    {{-- Price Per Chicken --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="price_per_chicken" class="form-label">Price Per Chicken</label>
                                        <input type="number" step="0.01" class="form-control" name="price_per_chicken"
                                            placeholder="Enter price per chicken" required
                                            value="{{ old('price_per_chicken', $batch->price_per_chicken ?? '') }}">
                                    </div>

                                    {{-- Chicken Grade --}}
                                    <div class="col-md-4" style="margin-top: 35px;">
                                        <label>
                                            <input type="radio" name="chicken_grade" {{ isset($batch->chicken_grade) && $batch->chicken_grade == 'A' ? 'checked' : '' }} value="A"> A
                                        </label>
                                        <label>
                                            <input type="radio" name="chicken_grade" {{ isset($batch->chicken_grade) && $batch->chicken_grade == 'B' ? 'checked' : '' }} value="B"> B
                                        </label>
                                        <label>
                                            <input type="radio" name="chicken_grade" {{ isset($batch->chicken_grade) && $batch->chicken_grade == 'C' ? 'checked' : '' }} value="C"> C
                                        </label>
                                        <label>
                                            <input type="radio" name="chicken_grade" {{ isset($batch->chicken_grade) && $batch->chicken_grade == 'D' ? 'checked' : '' }} value="D"> D
                                        </label>
                                    </div>

                                    {{-- Hatchery Name --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="hatchery_name" class="form-label">Hatchery Name</label>
                                        <input type="text" class="form-control" name="hatchery_name" placeholder="Enter hatchery name" required
                                            value="{{ old('hatchery_name', $batch->hatchery_name ?? '') }}">
                                    </div>

                                    {{-- Shed Number --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="shed_number" class="form-label">Shed Number(not required!)</label>
                                        <input type="text" class="form-control" name="shed_number" placeholder="Enter shed number"
                                            value="{{ old('shed_number', $batch->shed_number ?? '') }}">
                                    </div>

                                    {{-- Target Feed Qty --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="target_feed_qty" class="form-label">Target Feed Quantity(not required!)</label>
                                        <input type="number" step="0.01" class="form-control" id="target_feed_qty" name="target_feed_qty"
                                            placeholder="Enter target feed quantity"
                                            value="{{ old('target_feed_qty', $batch->target_feed_qty ?? '') }}">
                                    </div>

                                    {{-- Feed Unit --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="terget_feed_unit" class="form-label">Unit(not required!)</label>
                                        <select name="terget_feed_unit" class="form-control">
                                            <option value="bag" @selected(old('terget_feed_unit', $batch->terget_feed_unit ?? '') == 'bag')>Bag</option>
                                            <option value="kg" @selected(old('terget_feed_unit', $batch->terget_feed_unit ?? '') == 'kg')>KG</option>
                                        </select>
                                    </div>

                                    {{-- @dd($batch->batch_start_date) --}}

                                    {{-- Batch Start Date --}}
                                    <div class="mb-3 col-md-4">
                                        <label for="batch_start_date" class="form-label">Batch Start Date</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            name="batch_start_date"
                                            required
                                            value="{{ old('batch_start_date', optional($batch->batch_start_date ?? null)->format('Y-m-d')) }}"
                                        >
                                    </div>

                                    {{-- Batch Close Date --}}
                                    {{-- <div class="mb-3 col-md-3">
                                        <label for="batch_close_date" class="form-label">Close Date</label>
                                        <input type="date" class="form-control" name="batch_close_date"
                                            value="{{ old('batch_close_date', $batch->batch_close_date ?? '') }}">
                                    </div> --}}

                                    {{-- Description --}}
                                    <div class="mb-3 col-12">
                                        <label for="batch_description" class="form-label">Batch Description</label>
                                        <textarea class="form-control" rows="4" name="batch_description"
                                            placeholder="Enter batch description">{{ old('batch_description', $batch->batch_description ?? '') }}</textarea>
                                    </div>

                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($batch) ? 'Update Batch' : 'Create Batch' }}
                                    </button>
                                </div>
                            </form>

                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('chickenTypeSelect');
        const chicken_type = document.getElementById('chicken_type');
        const hiddenInput = document.getElementById('chickenTypeInput');
        const target_feed_qty = document.getElementById('target_feed_qty');
        const total_chickens = document.getElementById('total_chickens');

        // Page load: preselect if edit
        hiddenInput.value && (select.value = hiddenInput.value);

        // On change, update hidden input
        select.addEventListener('input', function() {
            hiddenInput.value = this.value;
            chicken_type.value = this.value;

            if (this.value === 'broiler') {
                target_feed_qty.value = total_chickens.value * 5 / 100;
            }

            if (this.value === 'shonali') {
                target_feed_qty.value = total_chickens.value * 4 / 100;
            }
        });


        total_chickens.addEventListener('input', function() {
            if (select.value === 'broiler') {
                target_feed_qty.value = total_chickens.value * 5 / 100;
            }
            if (select.value === 'shonali') {
                target_feed_qty.value = total_chickens.value * 4 / 100;
            }
        });
    });
</script>

@endsection