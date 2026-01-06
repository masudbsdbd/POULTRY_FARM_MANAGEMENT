@extends('layouts.vertical', ['title' => 'Others Income'])
@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection
@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Others Income', 'subtitle' => 'Batch Additional Revenue'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="header-title">Batch: {{ $bathInfo->batch_name ?? 'N/A' }}</h4>
                            <p class="text-muted">Total Additional Income: <strong>{{ number_format($totalIncome, 2) }} ৳</strong></p>
                        </div>
                        <a href="{{ route('customer.manageBatch', $bathInfo->id) }}" class="btn btn-info">
                            <i class="mdi mdi-arrow-left"></i> Back to Batch
                        </a>
                    </div>

                    <button type="button" class="btn btn-primary mb-3 createIncomeBtn">Add New Income</button>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $income)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $income->income_date->format('d M Y') }}</td>
                                    <td>{{ $income->title }}</td>
                                    <td class="text-success">{{ number_format($income->amount, 2) }} ৳</td>
                                    <td>{{ $income->note ?? '-' }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-primary btn-sm editIncomeBtn"
                                            data-id="{{ $income->id }}"
                                            data-route="{{ route('poultry.others-income.update', $income->id) }}"
                                            data-title="{{ $income->title }}"
                                            data-amount="{{ $income->amount }}"
                                            data-date="{{ $income->income_date->format('Y-m-d') }}"
                                            data-note="{{ $income->note }}">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm confirmationBtn"
                                            data-question="Are you sure to delete this income record?"
                                            data-action="{{ route('poultry.others-income.destroy', $income->id) }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No other income recorded yet.</td>
                                    <td colspan="" class="d-none text-center py-4">No other income recorded yet.</td>
                                    <td colspan="" class="d-none text-center py-4">No other income recorded yet.</td>
                                    <td colspan="" class="d-none text-center py-4">No other income recorded yet.</td>
                                    <td colspan="" class="d-none text-center py-4">No other income recorded yet.</td>
                                    <td colspan="" class="d-none text-center py-4">No other income recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $incomes->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Other Income</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('poultry.others-income.store') }}" method="POST">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $bathInfo->id }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Income Date <span class="text-danger">*</span></label>
                            <input type="date" name="income_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" required placeholder="e.g. 5000.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. Manure Sale, Equipment Rent">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Any additional details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Income</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editIncomeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Edit Other Income</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Income Date <span class="text-danger">*</span></label>
                            <input type="date" name="income_date" id="edit_income_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="note" id="edit_note" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Update Income</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        $(document).ready(function () {
            // Add Modal
            $('.createIncomeBtn').on('click', function () {
                $('#addIncomeModal').modal('show');
            });

            // Edit Modal
            $('.editIncomeBtn').on('click', function () {
                let data = $(this).data();

                $('#editForm').attr('action', data.route);
                $('#edit_income_date').val(data.date);
                $('#edit_amount').val(data.amount);
                $('#edit_title').val(data.title);
                $('#edit_note').val(data.note);

                $('#editIncomeModal').modal('show');
            });
        });
    </script>
@endsection