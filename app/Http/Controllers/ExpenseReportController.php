<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Employee;
use App\Models\ExpenseHead;

class ExpenseReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:expense-report-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $pageTitle = 'All Expense Reports';
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }
        $employee_id = $request->employee_id;
        $expense_head_id = $request->expense_head_id;
        $expenses = Expense::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $expenses = $expenses->$clause('created_at', $givenDate);
        }
        if ($employee_id) {
            $expenses = $expenses->where('employee_id', $employee_id);
        }
        if ($expense_head_id) {
            $expenses = $expenses->where('expense_head_id', $expense_head_id);
        }

        $expenses = $expenses->with('employee', 'expenseHead')->latest()->paginate(gs()->pagination);
        $employees = Employee::latest()->notDeleted()->get();
        $expenseHeads = ExpenseHead::latest()->notDeleted()->get();


        // dd($expenses);
        return view('expense-report.index', compact('todayTime', 'pageTitle', 'expenses', 'employees', 'expenseHeads'));
    }
}
