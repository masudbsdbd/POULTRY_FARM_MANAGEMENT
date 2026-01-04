@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => 'Supplier Ledger',
            'subtitle' => 'Supplier Ledger',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ $pageTitle }}</h4>

                        <div class="mt-3 mb-2">
                            <a href="{{ route('supplier.ledger.purchase.index', $id) }}"
                                class="btn btn-info waves-effect waves-light">Purchase History
                            </a>
                            <a href="{{ route('supplier.ledger.account.index', $id) }}"
                                class="btn btn-warning waves-effect waves-light">Accounts Ledger
                            </a>
                        </div>

                        <div class="border rounded p-3 mb-3 bg-light">
                            <h4 class="text-danger">Supplier Details</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Name:</strong> {{ !empty($supplierData->name) ? $supplierData->name : '-' }}
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Code:</strong> {{ !empty($supplierData->code) ? $supplierData->code : '-' }}
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Company:</strong>
                                        {{ !empty($supplierData->company) ? $supplierData->company : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Mobile:</strong>
                                        {{ !empty($supplierData->mobile) ? $supplierData->mobile : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Address:</strong>
                                        {{ !empty($supplierData->address) ? $supplierData->address : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Email:</strong>
                                        {{ !empty($supplierData->email) ? $supplierData->email : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Advance:</strong>
                                        {{ !empty(showAmount($supplierData->advance)) ? showAmount($supplierData->advance) : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="button-list">
                            <form id="myForm" action="{{ route('supplier.ledger.account.index', $id) }}" method="GET">

                                <input type="hidden" name="action" id="form-action" value="search">

                                <div class="row justify-content-end">
                                    <!-- Start Date Field -->
                                    <div class="col-md-2 p-0">
                                        <p class="fw-bold text-muted">Search Type</p>
                                        <select class="form-select" id="type-select" name="type">
                                            <option value="">Select Type</option>
                                            <option value="1" @selected(request('type') == '1')>Single Date</option>
                                            <option value="2" @selected(request('type') == '2')>Date Range</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 pe-0">
                                        <div class="basicDatepicker {{ request('range') ? 'd-none' : '' }}">
                                            <p class="fw-bold text-muted">Select Date</p>
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
                                            <div class="col-md-6 ps-0">
                                                <button type="button" class="btn btn-success w-100 printBtn"
                                                    style="margin-top: 36px;">
                                                    <i class="mdi mdi-printer"></i> Print
                                                </button>
                                            </div>
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

                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Invoice Number</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Balance</th>
                                </tr>
                            </thead>


                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    $totalDiscount = 0;
                                    $balance = 0;
                                    $updatedCredit = 0;
                                    $updatedDebit = 0;
                                    $serial = 1;
                                @endphp
                                @foreach ($supplierAccData as $item)
                                    <tr>
                                    @php
                                        $debit = $item->type == 11 ? $item->credit : $item->debit;
                                        $credit = $item->type == 6 ? $item->credit : $item->amount;

                                        $totalDebit += $debit;
                                        $totalCredit += $credit;
                                        $updatedCredit += $credit;   
                                        $updatedDebit += $debit;   

                                    @endphp
                                        <td>{{ $serial++ }}</td>
                                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                        <td class="text-center">{{ $item->purchase->invoice_no ?? 'N/A' }} </td>
                                        <td class="text-center">{{ $item->description }}</td>
                                        <td class="text-center">{{ showAmount($debit) }} Tk</td>
                                        <td class="text-center">{{ showAmount($credit) }} Tk</td>
                                        <td class="text-center">{{ showAmount($updatedDebit - $updatedCredit ) }} Tk</td>

                                    </tr>
                                @endforeach

                            </tbody>

                            <tfoot>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong>{{ $totalDebit }} Tk</strong></td>
                                    <td class="text-center"><strong>{{ $totalCredit }} Tk</strong></td>
                                    <td class="text-center"></td>

                                </tr>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold"></td>
                                    <td class="fw-bold"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center">Closing Balance: </td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <strong>
                                            @if ($totalDebit - $totalCredit > 0)
                                                Advance: {{ $totalDebit - $totalCredit }} Tk
                                            @elseif ($totalDebit - $totalCredit < 0)
                                                Due: {{ abs($totalDebit - $totalCredit) }} Tk
                                            @else
                                                --
                                            @endif
                                        </strong>
                                    </td>
                                  <td class="text-center"><strong></strong></td>

                                </tr>
                            </tfoot>
                        </table>


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
