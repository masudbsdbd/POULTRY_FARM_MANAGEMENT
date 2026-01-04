@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Income Management', 'subtitle' => 'Manage Your Income List'])

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{$pageTitle}}</h4>

                    <!-- Expense Form -->
                    <form action="{{ isset($incomeList) ? route('income.list.store', $incomeList->id) : route('income.list.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Account Head Name -->
                            <div class="col-md-12">
                                <p class="mb-1 fw-bold text-muted">Other Income Name</p>
                                <input id="name" type="text" class="form-control" placeholder="name" name="name"
                                    value="{{ isset($incomeList) ? $incomeList->name : '' }}" required>
                            </div>

                            <!-- Account Head Details -->
                            <div class="col-md-12 mt-3">
                                <p class="mb-1 fw-bold text-muted">Other Income Details</p>
                                <textarea class="form-control" id="details" placeholder="Details" rows="5"
                                    name="details" required>{{ isset($incomeList) ? $incomeList->details : '' }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-3">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ isset($incomeList) ? 'Update ' : 'Add New ' }}</button>
                            </div>
                        </div>
                    </form>


                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])


@endsection