@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Deposite Amount', 'subtitle' => 'Bank'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route('bank.transaction.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="balance" class="form-label">Deposit Amount</label>
                                    <div class="input-group">
                                        <input type="number" id="balance" class="form-control"
                                            placeholder="Deposit Amount" name="deposit_amount">
                                        <div class="input-group-text" data-password="false">Tk
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Deposite Account No</label>
                                    <select name="bank_id" required class=" form-control" id="bank_id">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="account_no" class="form-label">Description</label>
                                    <textarea name="description" id="" cols="30" rows="10" class=" form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="depositor_name" class="form-label">Depositor Name</label>
                                    <input type="text" id="depositor_name" class="form-control" name="depositor_name"
                                        placeholder="Depositor Name" required>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Deposit</button>
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