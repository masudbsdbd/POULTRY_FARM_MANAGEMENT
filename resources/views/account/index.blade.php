@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection


@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                                            <h5>
                        @if(request('date'))
                        Search Date: ( {{ request('date') }} )
                        @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                        @else

                        @endif

                    </h5>
                    @if (Route::currentRouteName() === 'account.statements')
                    <form action="{{ route('account.statements') }}" method="get">
                        @elseif(Route::currentRouteName() === 'account.cash.statements')
                        <form action="{{ route('account.cash.statements') }}" method="get">
                            @elseif(Route::currentRouteName() === 'account.bank.statements')
                            <form action="{{ route('account.bank.statements') }}" method="get">                        
                                @endif
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
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"
                                    style="margin-top: 36px;">Search</button>
                            </div>

                        </div>
                    </form>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Reference</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Entry By</th>
                                    <th class="text-center">Payment Method</th>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">(Purchase/Sell) Amount</th>
                                    <th class="text-center">Balance</th>
                                    <th class="text-center">Staus</th>
                                    <th class="text-end">Description</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach ($statements as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ showDateTime($item->created_at, true) }}</td>
                                        <td class="text-center">
                                            @if ($item->customer_id != 0)
                                                {{ $item->customer->name . ' (' . $item->customer->code . ')' }}
                                            @elseif($item->supplier_id != 0)
                                                {{ $item->supplier->name . ' (' . $item->supplier->code . ')' }}
                                            @elseif($item->employee_id != 0)
                                                {{ $item->employee->name . ' (' . $item->employee->code . ')' }}
                                            @else
                                                ---
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($item->type == 1)
                                                <span class="badge badge-soft-secondary">Purchase</span>
                                            @elseif($item->type == 2)
                                                <span class="badge badge-soft-secondary">Sell</span>
                                            @elseif($item->type == 3)
                                                <span class="badge badge-soft-secondary">Bank Deposit</span>
                                            @elseif($item->type == 4)
                                                <span class="badge badge-soft-secondary">Bank Withdraw</span>
                                            @elseif($item->type == 5)
                                                <span class="badge badge-soft-secondary">Customer Advance</span>
                                            @elseif($item->type == 6)
                                                <span class="badge badge-soft-secondary">Supplier Advance</span>
                                            @elseif($item->type == 7)
                                                <span class="badge badge-soft-secondary">Customer Due</span>
                                            @elseif($item->type == 8)
                                                <span class="badge badge-soft-secondary">Supplier Due</span>
                                            @elseif($item->type == 9)
                                                <span class="badge badge-soft-secondary">Expense</span>
                                            @elseif($item->type == 10)
                                                <span class="badge badge-soft-secondary">Sell Return</span>
                                            @elseif($item->type == 11)
                                                <span class="badge badge-soft-secondary">Purchase Return</span>
                                            @elseif($item->type == 12)
                                                <span class="badge badge-soft-secondary">Employee Salary</span>
                                            @elseif($item->type == 13)
                                                <span class="badge badge-soft-secondary">Customer Payment</span>
                                            @elseif($item->type == 14)
                                                <span class="badge badge-soft-secondary">Supplier Payment</span>
                                            @elseif($item->type == 15)
                                                <span class="badge badge-soft-secondary">Income</span>
                                            @elseif($item->type == 16)
                                                <span class="badge badge-soft-secondary">Investment</span>
                                            @elseif($item->type == 17)
                                                <span class="badge badge-soft-secondary">Receivable</span>
                                            @elseif($item->type == 18)
                                                <span class="badge badge-soft-secondary">Payable</span>
                                            @elseif($item->type == 0)
                                                <span class="badge badge-soft-secondary">Jounal</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $item->user->name }}
                                        </td>

                                        <td class="text-center">
                                            @if ($item->payment_method == 1)
                                                <span class="badge badge-soft-secondary">Cash</span>
                                            @elseif ($item->payment_method == 2)
                                                <span class="badge badge-soft-secondary">Bank</span>
                                            @else
                                                ---
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ showAmount($item->debit) }}
                                        </td>

                                        <td class="text-center">
                                            {{ showAmount($item->credit) }}
                                        </td>

                                        <td class="text-center">
                                            {{ showAmount($item->amount) }}
                                        </td>
                                        <td class="text-center">
                                            {{ showAmount($item->balance) }}
                                        </td>

                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span class="badge badge-soft-danger">Debit</span>
                                            @elseif ($item->status == 2)
                                                <span class="badge badge-soft-success">Credit</span>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        </td>
                                        <td class="text-end">
                                            @if (strlen($item->description) < 25)
                                                {{ $item->description }}
                                            @else
                                                {{ substr($item->description, 0, 24) }}...
                                                <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                                    class="text-primary descBtn" href="">
                                                    See More</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if (!$statements->isEmpty())
                                <tfoot>
                                    <tr style="font-size: 16px;">
                                        <td class="fw-bold">Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <strong>
                                                @if (Route::currentRouteName() === 'account.statements')
                                                    {{ showAmount(getSum()[0]) }}
                                                @elseif(Route::currentRouteName() === 'account.cash.statements')
                                                    {{ showAmount(getSum(1)[0]) }}
                                                @elseif(Route::currentRouteName() === 'account.bank.statements')
                                                    {{ showAmount(getSum(2)[0]) }}
                                                @endif
                                                Tk
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <strong>

                                                @if (Route::currentRouteName() === 'account.statements')
                                                    {{ showAmount(getSum()[1]) }}
                                                @elseif(Route::currentRouteName() === 'account.cash.statements')
                                                    {{ showAmount(getSum(1)[1]) }}
                                                @elseif(Route::currentRouteName() === 'account.bank.statements')
                                                    {{ showAmount(getSum(2)[1]) }}
                                                @endif

                                                Tk
                                            </strong>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end">
                                            <strong>Current Balance:
                                                @if (Route::currentRouteName() === 'account.statements')
                                                    {{ showAmount(statement()) }}
                                                @elseif(Route::currentRouteName() === 'account.cash.statements')
                                                    {{ showAmount(statement(1)) }}
                                                @elseif(Route::currentRouteName() === 'account.bank.statements')
                                                    {{ showAmount(statement(2)) }}
                                                @endif
                                                Tk
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $statements->appends(request()->query())->links() }}
                        </div>
                        <!-- end row-->
                    </div> <!-- container -->
                </div> <!-- container -->
            </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- container -->

    <div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Account Description</h4>
                    @csrf
                    <p class="descParagraph"></p>
                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->


    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js','resources/js/pages/form-pickers.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.descBtn', function() {
                    var modal = $('#descModal');
                    let data = $(this).data();
                    modal.find('.descParagraph').text(`${data.paragraph}`);
                    modal.modal('show');
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

