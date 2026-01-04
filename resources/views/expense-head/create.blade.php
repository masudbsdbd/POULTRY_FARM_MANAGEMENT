@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Expense Management', 'subtitle' => 'Manage Your Expense Heads'])

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Expense Head</h4>

                    <!-- Expense Form -->
                    <form action="{{ isset($expenseHead) ? route('expense.head.store', $expenseHead->id) : route('expense.head.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Account Head Name -->
                            <div class="col-md-12">
                                <p class="mb-1 fw-bold text-muted">Account Head Name</p>
                                <input id="name" type="text" class="form-control" placeholder="name" name="name"
                                    value="{{ isset($expenseHead) ? $expenseHead->name : '' }}" required>
                            </div>

                            <!-- Account Head Details -->
                            <div class="col-md-12 mt-3">
                                <p class="mb-1 fw-bold text-muted">Account Head Details</p>
                                <textarea class="form-control" id="details" placeholder="Details" rows="5"
                                    name="details" required>{{ isset($expenseHead) ? $expenseHead->details : '' }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Add</button>
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