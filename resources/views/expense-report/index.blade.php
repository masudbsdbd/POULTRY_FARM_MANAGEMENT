@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Bank Info'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <h4>Today ({{ $todayTime }}) </h4>
                    <h5>
                        @if(request('date'))
                        Search Date: ( {{ request('date') }} )
                        @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                        @else

                        @endif

                    </h5>
                    <form action="{{ route('expense.report.index') }}" method="get">
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

                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Select Employee</p>
                                <select id="select-product" class="form-control" data-toggle="select2" data-width="100%"
                                    name="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $item)
                                    <option value="{{ $item->id }}" @selected(request('employee_id') ? $item->id == request('employee_id') ?? true : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->
                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Select Head</p>
                                <select id="select-product" class="form-control" data-toggle="select2" data-width="100%"
                                    name="expense_head_id">
                                    <option value="">Select Head</option>
                                    @foreach ($expenseHeads as $item)
                                    <option value="{{ $item->id }}" @selected(request('expense_head_id') ? $item->id == request('expense_head_id') ?? true : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->


                            <!-- Search Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"
                                    style="margin-top: 36px;">Search</button>
                            </div>

                        </div>
                    </form>

                    <div class="mt-3">
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Expense Head</th>
                                    <th class="text-center">Employee</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $totalAmount = 0;
                                @endphp
                                @foreach ($expenses as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                    <td class="text-center"> {{ $item->expenseHead->name }}</td>

                                    <td class="text-center">
                                        @if( $item->employee_id )
                                        {{ $item->employee->name }}
                                        @else
                                        --
                                        @endif
                                    </td>
                                    <td class="text-center">{{ showAmount($item->amount) }} Tk</td>
                                    <td class="text-end">
                                        <button title="View" type="button"
                                            class="btn btn-success waves-effect waves-light expenseDetailBtn"
                                            data-id="{{ $item->id }}"
                                            data-created_at="{{ showDateTime($item->created_at) }}"
                                            data-employee_name="{{ $item->employee ? $item->employee->name : '--' }}"
                                            data-title="{{ $item->title }}"
                                            data-amount="{{ showAmount($item->amount) }}"
                                            data-description="{{ $item->description }}"
                                            data-head_name="{{ $item->expenseHead->name }}">
                                            <i class="mdi mdi-eye"></i></button>
                                    </td>
                                </tr>
                                @php
                                $totalAmount += $item->amount;
                                @endphp
                                @endforeach
                            </tbody>


                            @if (!$expenses->isEmpty())
                            <tfoot>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold">Total: </td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong> </strong></td>
                                    <td class="text-center"><strong> {{ $totalAmount }} Tk</strong></td>
                                    <td class="text-center"><strong> </strong></td>

                                </tr>
                            </tfoot>
                            @endif

                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $expenses->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="expenseDetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header text-white">
                <h5>
                    <h4 class="mb-3">Head - <span id="headName"></span></h4>

                </h5>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover mb-4">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="modalDate">---</td>
                                    <td id="modalEmployee">---</td>
                                    <td id="modalTitle">---</td>
                                    <td id="modalAmount">---</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription">No description available.</p>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

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

            $(document).on('click', '.expenseDetailBtn', function() {
                var modal = $('#expenseDetails');
                // modal.find('#headName').text(data.head_name);

                var createdAt = $(this).data('created_at');
                var employeeName = $(this).data('employee_name');
                var title = $(this).data('title');
                var amount = $(this).data('amount');
                var description = $(this).data('description');

                modal.find('#headName').text($(this).data('head_name'));
                modal.find('#modalDate').text(createdAt);
                modal.find('#modalEmployee').text(employeeName || '---');
                modal.find('#modalTitle').text(title);
                modal.find('#modalAmount').text(amount + ' Tk');
                modal.find('#modalDescription').text(description || 'No description available.');

                // Show the modal
                modal.modal('show');
            });



        })(jQuery);
    });
</script>
@endsection