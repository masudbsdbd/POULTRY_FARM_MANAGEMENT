@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">
                            <div class="d-flex justify-content-between">
                                <div>{{ $pageTitle }}</div>
                                <div> Delivery Status:
                                    @if ($sell->delivery_status == 1)
                                        <span class="text-success">Delivered</span>
                                    @else
                                        <span class="text-danger">Not Delivered</span>
                                    @endif
                                </div>
                            </div>
                        </h4>

                        <div class="border rounded p-3 bg-light">
                            <h4 class="text-danger">Customer Details</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Name:</strong> {{ $sell->customer->name }}
                                        ({{ $sell->customer->customerType->name }})</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Code:</strong> {{ $sell->customer->code }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Company:</strong> {{ $sell->customer->company }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Mobile:</strong> {{ $sell->customer->mobile }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Email:</strong> {{ $sell->customer->email }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Address:</strong> {{ $sell->customer->address }}</p>
                                </div>
                            </div>

                            <h4 class="text-danger">Payment Method</h4>
                            <div class="row">
                                <div class="payment-table">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Payment Method:</strong>
                                                {{ $sell->account->payment_method == 1 ? 'Cash' : 'Bank' }}</p>
                                        </div>

                                        <div class="col-md-3">
                                            <p><strong>Amount:</strong> {{ showAmount($sell->total_price) }} Tk
                                            </p>
                                        </div>

                                        <div class="col-md-3">
                                            <p><strong>Due:</strong> {{ showAmount($sell->due_to_company) }} Tk
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($sell->account->payment_method == 2)
                                <h4 class="text-danger">Bank Details</h4>
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Bank:</strong>
                                                {{ $sell->account->bankTransaction->bank->bank_name }}
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Check No:</strong>
                                                {{ $sell->account->bankTransaction->check_no }}
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Depositor Name:</strong>
                                                {{ $sell->account->bankTransaction->depositor_name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <h4 class="mt-4">Sell Item List</h4>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Batch</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Avg Purchase Price</th>
                                            <th>Total</th>
                                            <th>Discount (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if (isset($sellRecords) && !$sellRecords->isEmpty())
                                            @foreach ($sellRecords as $item)
                                                @php
                                                    $findProduct = App\Models\Product::find($item->product_id);
                                                @endphp
                                                <tr class="tableRow">
                                                    <td>
                                                        <p>{{ $findProduct->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $item->purchaseBatch->batch_code }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $item->sell_qty }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ number_format($item->avg_sell_price, 2, '.', '') }}
                                                            {{ $findProduct->unit->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ number_format($item->avg_purchase_price, 2, '.', '') }}
                                                            {{ $findProduct->unit->name }}</p>
                                                    </td>

                                                    <td>
                                                        <p>{{ number_format($item->total_amount, 2, '.', '') }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ number_format($item->discount, 2, '.', '') }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="empty-row">
                                                <td colspan="8" class="text-center">
                                                    <h4 class="my-3">Insert Products</h4>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- Row outside the table -->
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row"></th>
                                            <td>
                                                <h4>Total Quantity: <span
                                                        id="total-quantity">{{ isset($sell) ? $sell->total_qty : 0 }}</span>
                                                </h4>
                                            </td>
                                            <td>
                                                <h4>Total Price: <span
                                                        id="total-price">{{ isset($sell) ? showAmount($sell->total_price) : 0 }}</span>
                                                </h4>
                                            </td>
                                            <td>
                                                <h4>Less Amount: <span
                                                        id="total-price">{{ isset($sell) ? showAmount($sell->discount) : '' }}</span>
                                                </h4>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light confirmationBtn"
                                data-question="@lang('Are you sure to confirm this delivery?')"
                                data-action="{{ route('sell.delivery.confirm', $sell->id) }}">{{ $sell->delivery_status == 0 ? 'Confirm Delivery' : 'Cancel Delivery' }}</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.goBack').on('click', function() {
                history.back();
            });
        });
    </script>
@endsection
