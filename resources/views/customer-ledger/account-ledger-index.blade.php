@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection


@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Account Ledger', 'subtitle' => 'Customer Ledger'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        <h5>
                            @if(request('date'))
                            Search Date: ( {{ request('date') }} )
                            @elseif(request('range'))
                            Search Date: ( {{ request('range') }} )
                            @endif
                        </h5>
                    </div>
                    {{--<div class="row justify-content-start">
                        <div class="col-md-3 text-start mb-3">
                            <h5>Name: <span>{{ !empty($customerData->name) ? $customerData->name : '-' }}</span></h5>
                            <h5>Code: <span>{{ !empty($customerData->code) ? $customerData->code : '-' }}</span></h5>
                            <h5>Company: <span>{{ !empty($customerData->company) ? $customerData->company : '-' }}</span></h5>
                            <h5>Mobile: <span>{{ !empty($customerData->mobile) ? $customerData->mobile : '-' }}</span></h5>
                        </div>
                        <div class="col-md-3 text-start mb-3">
                            <h5>Address: <span>{{ !empty($customerData->address) ? $customerData->address : '-' }}</span></h5>
                            <h5>Email: <span>{{ !empty($customerData->email) ? $customerData->email : '-' }}</span></h5>
                            <h5>Advance Amount: <span>{{ !empty(showAmount($customerData->advance)) ? showAmount($customerData->advance) : '-' }} Tk</span></h5>
                        </div>
                    </div>--}}

 
                    <div class="mt-3 mb-2">
                        <a href="{{ route('customer.ledger.sell.index',$id) }}" class="btn btn-info waves-effect waves-light">Sell History
                        </a>
                        <a href="{{ route('customer.ledger.account.index',$id) }}" class="btn btn-warning waves-effect waves-light">Accounts Ledger
                        </a>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                            <h4 class="text-danger">Customer Details</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Name:</strong> {{ !empty($customerData->name) ? $customerData->name : '-' }}
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Code:</strong> {{ !empty($customerData->code) ? $customerData->code : '-' }}
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Company:</strong>
                                    {{ !empty($customerData->company) ? $customerData->company : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Mobile:</strong>
                                    {{ !empty($customerData->mobile) ? $customerData->mobile : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Address:</strong>
                                        {{ !empty($customerData->address) ? $customerData->address : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Email:</strong>
                                        {{ !empty($customerData->email) ? $customerData->email : '-' }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Trn Number:</strong>
                                    {{ !empty($customerData->trn_number) ? $customerData->trn_number : '-' }} Tk
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Trn Date:</strong>
                                    {{ !empty($customerData->trn_date) ? $customerData->trn_date : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>


                    <div class="button-list mb-2">

                        <form action="{{ route('customer.ledger.account.index',$id) }}" method="get" id="myForm">
                        <input type="hidden" name="action" id="form-action" value="search">
                            <div class="row">
                                <!-- Start Date Field -->
                                <div class="col-md-2">
                                    <p class="fw-bold text-muted">Search Type</p>
                                    <select class="form-select" id="type-select" name="type">
                                        <option value="">Select Type</option>
                                        <option value="1" @selected(request('type')=='1' )>Single Date</option>
                                        <option value="2" @selected(request('type')=='2' )>Date Range</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="basicDatepicker {{ request('range') ? 'd-none' : '' }}">
                                        <p class="fw-bold text-muted">Select Date</p>
                                        <div class="input-group input-group-merge">
                                            <input type="text" name="date" id="basic-datepicker" class="form-control"
                                                placeholder="Select Date" value="{{ old('date', request('date')) }}">
                                            <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                        </div>
                                    </div>


                                    <div class="rangeDatepicker {{ request('range') ? '' : 'd-none' }}">
                                        <p class="fw-bold text-muted">Choose Date Range</p>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="range-datepicker" class="form-control" name="range"
                                                placeholder="Ex. 2018-10-03 to 2018-10-10"
                                                value="{{ old('date', request('range')) }}">
                                            <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Search Button -->
                                <!-- <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100"
                                        style="margin-top: 36px;">Search</button>
                                </div> -->

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
                            $serial = 1;
                            $balance = 0;
                            $updatedCredit = 0;
                            $updatedDebit = 0;

                            @endphp

                            @foreach ($customerAccData as $item)
                            <tr>
                            @php
                                $debit = $item->type == 5 ? $item->debit : $item->amount;
                                $credit = $item->type == 10 ? $item->debit : $item->credit;

                                $totalDebit += $debit;
                                $totalCredit += $credit;
                                $updatedCredit += $credit;   
                                $updatedDebit += $debit;   

                            @endphp
                                <td>{{ $serial++ }}</td>
                                <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                <td class="text-center">{{ $item->sell->invoice_no ?? 'N/A'}} </td>
                                <td class="text-center">{{$item->description}}</td>
                               
                                <td class="text-center">{{ showAmount($debit) }}</td>
                                <td class="text-center">{{ showAmount($credit) }}</td>
                                <td class="text-center">{{ showAmount($updatedCredit - $updatedDebit ) }}</td>

                            </tr>
                            @endforeach

                        </tbody>

                        <tfoot>

                            <tr style="font-size: 16px;">
                                <td class="fw-bold"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"><strong>{{ $totalDebit }} Tk
                                    </strong></td>
                                <td class="text-center"><strong>{{ $totalCredit }} Tk</strong></td>
                                <td class="text-center"></td>


                            </tr>

                            
                            <tr style="font-size: 16px;">
                                <td class="fw-bold"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">Closing Balance: </td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>

                                <td class="text-center">
                                    <strong>
                                        @if ($totalCredit - $totalDebit> 0)
                                        Advance: {{ $totalCredit - $totalDebit }} Tk
                                        @elseif ($totalCredit - $totalDebit < 0)
                                            Due: {{ abs($totalCredit - $totalDebit) }} Tk
                                            @else
                                            --
                                            @endif
                                            </strong>
                                </td>

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
@vite(['resources/js/app.js','resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

            $(document).on('click', '.printBtn', function() {
                // console.log('print');
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