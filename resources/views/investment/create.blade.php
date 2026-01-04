@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Investment Create', 'subtitle' => 'Investment'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route('investment.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Basic datepicker"
                                        value="<?php echo (new DateTime('now', new DateTimeZone('Asia/Dhaka')))->format('Y-m-d H:i'); ?>"
                                        name="entry_date" required>
                                </div>
                                

                                <div class="mb-3">
                                    <p class="mb-1 fw-bold text-muted">Investor Name</p>
                                    <input id="name"
                                        type="text" class="form-control" placeholder="title"
                                        name="name" required>
                                </div>


                               
                                <div class="mb-3">
                                    <label for="account_no" class="form-label">Description</label>
                                    <textarea name="description" id="" cols="30" rows="5" class=" form-control"></textarea>
                                </div>



                                <div class="col-md-12">
                                    <div class="my-3"><label class="form-label"> Investment Amounts</label>
                                        <div class="input-group">
                                            <!-- Select element taking 4 columns -->
                                            <div class="col-lg-3 pe-0">
                                                <select id="paymentMethod" class="form-select rounded-0"
                                                    id="example-select" name="payment_method" required>
                                                    <option value="1" >Cash</option>
                                                    <option value="2">Bank</option>
                                                </select>
                                            </div>
    
                                            <!-- Other elements taking 8 columns -->
                                            <div class="col-lg-9">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control rounded-0" placeholder="Enter Amount"
                                                        name="amount"
                                                        value=""
                                                        required>
                                                    <div class="input-group-text">Tk</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="bankInfo"
                                class="col-lg-12 d-none ">
                                <h4>Bank Information</h4>
                                <div class="row my-3">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="example-Account" class="form-label">Select Bank</label>
                                            <select id="bank_id" class="form-select rounded-0" name="bank_id"
                                                >
                                                <option value="">Select Bank</option>
                                                @foreach ($banks as $item)
                                                <option value="{{ $item->id }}"
                                                    data-balance="{{ $item->balance }}">
                                                    {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="example-check" class="form-label">Check No</label>
                                            <input type="text" id="example-check" class="form-control"
                                                name="check_no"
                                                value=""
                                                placeholder="Check No">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="example-depositor" class="form-label">Depositor Name</label>
                                            <input id="depositor_name" type="text" id="example-depositor"
                                                class="form-control" name="depositor_name"
                                                placeholder="Depositor Name"
                                                value=""
                                                >
                                        </div>
                                    </div>
                                </div>
                            </div>





                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Invest</button>
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
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js','resources/js/pages/form-pickers.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let getBankOnEditPage = $('#bank_id').val();

        $('#paymentMethod').on('change', function() {
            if ($(this).val() == 2) {
                $('#bankInfo').removeClass('d-none');
                $('#bank_id').prop('required', true);
                $('#depositor_name').prop('required', true);
            } else {
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false);
                $('#depositor_name').prop('required', false);
            }
        });

        $('#bank_id').on('change', function() {
            let data = $(this).find(':selected').data();
            let total_price = $('input[name="amount"]').val();

            // console.log(getBankOnEditPage);

            if (total_price > data.balance && $(this).val() != getBankOnEditPage) {
                Toast.fire({
                    icon: 'error',
                    title: 'Insufficient balance in this bank.'
                })

                if (getBankOnEditPage !== "" && getBankOnEditPage !== null) {
                    $(this).val(getBankOnEditPage);
                }
            }
        });

    });
</script>
@endsection