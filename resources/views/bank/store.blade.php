@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Bank Info Save', 'subtitle' => 'Bank Info'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ isset($bank) ? route('bank.store', $bank->id) : route('bank.store') }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Account Holder</label>
                                        <input type="text" id="simpleinput" class="form-control"
                                            placeholder="Account Holder Name"
                                            value="{{ old('account_name', isset($bank) ? $bank->account_name : '') }}"
                                            name="account_name" required>
                                    </div>


                                    <div class="mb-3">
                                        <label for="account_no" class="form-label">Account No</label>
                                        <input type="text" id="account_no"
                                            value="{{ old('account_no', isset($bank) ? $bank->account_no : '') }}"
                                            class="form-control" placeholder="Account No" name="account_no" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <input type="text" id="bank_name" class="form-control" name="bank_name"
                                            placeholder="Bank name"
                                            value="{{ old('bank_name', isset($bank) ? $bank->bank_name : '') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="branch_name" class="form-label">Branch Name</label>
                                        <input type="text" id="branch_name" name="branch_name"
                                            value="{{ old('branch_name', isset($bank) ? $bank->branch_name : '') }}"
                                            class="form-control" placeholder="Branch name">
                                    </div>

                                    @if (!isset($bank))
                                        <div class="mb-3">
                                            <label for="balance" class="form-label">Opening Deposit</label>
                                            <div class="input-group">
                                                <input type="number" id="balance" class="form-control"
                                                    placeholder="Opening Deposit"
                                                    value="{{ old('balance', isset($bank) ? $bank->balance : '') }}"
                                                    name="balance">
                                                <div class="input-group-text" data-password="false">Tk
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3 d-flex">
                                        <label for="status">Status</label>
                                        <label class="switch m-0">
                                            <input id="status" type="checkbox" class="toggle-switch"
                                                name="{{ isset($bank) ? 'editcatstatus' : 'status' }}"
                                                @checked(isset($bank) ? ($bank->status == 1 ? true : false) : true)>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                            {{ isset($bank)
                                                ? 'Update Bank Info'
                                                : 'Add New Bank
                                                                                        Info' }}</button>
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
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
@endsection
