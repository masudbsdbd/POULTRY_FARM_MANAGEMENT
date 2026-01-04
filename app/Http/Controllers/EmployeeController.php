<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Employee;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee-list', ['only' => ['index']]);
        $this->middleware('permission:employee-create|employee-edit', ['only' => ['store']]);
        $this->middleware('permission:employee-create', ['only' => ['create']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit']]);
        $this->middleware('permission:employee-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'All Employes';
        $employes = Employee::latest()->notDeleted()->paginate(gs()->pagination);
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('employee.index', compact('pageTitle', 'employes', 'banks'));
    }

    public function create()
    {
        $pageTitle = 'Create Employee';
        return view('employee.store', compact('pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Employee';
        $employee = Employee::find($id);
        return view('employee.store', compact('pageTitle', 'employee'));
    }

    public function store(Request $request, $id = 0)
    {
        // return $request;
        // dd($request->all());
        $request->validate([
            'name'        => 'required|string|max:40',
            'designation' => 'required|string|max:40',
            'nid'         => 'required',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($id),
            ],
            'mobile' => [
                'required',
                Rule::unique('employees', 'mobile')->ignore($id),
            ],
            'address'     => 'required|string|max:255'
        ]);

        if ($id > 0) {
            $employee               = Employee::whereId($id)->first();
            $message                = 'Employee has been updated successfully';
            $givenStatus            = isset($request->editbrandstatus) ? 1 : 0;
            $employee->update_by    = auth()->user()->id;

            if ($request->hasFile('image')) {
                $uploadedEmployeeImage = uploadImage($request->file('image'), 'employees', 450, $employee->image);
                $employee->image       = $uploadedEmployeeImage;
            }
        } else {
            $employee               = new Employee();
            $message                = 'Employee has been created successfully';
            $givenStatus            = isset($request->status) ? 1 : 0;
            $employee->code         = randomCode('EMP');
            $employee->entry_by     = auth()->user()->id;
            $employee->entry_date   = now();

            if ($request->hasFile('image')) {
                $uploadedEmployeeImage = uploadImage($request->file('image'), 'employees', 450);
                $employee->image       = $uploadedEmployeeImage;
            }
        }

        $employee->name               = $request->name;
        $employee->designation        = $request->designation;
        $employee->nid                = $request->nid;
        $employee->mobile             = $request->mobile;
        $employee->email              = $request->email;
        $employee->address            = $request->address;
        $employee->joining_date       = $request->joining_date;
        $employee->salary             = $request->salary;
        $employee->conveyance         = $request->conveyance ?? 0;
        $employee->status             = $givenStatus;
        $employee->save();

        // // == ===== == Receivable/Payable Account Start ==>
        if ($id == 0) {
            $payableData = new Payable();
            $payableData->employee_id = $employee->id;
            $payableData->payables_head_id = 3;
            $payableData->save();

            // $receivableData = new Receivable();
            // $receivableData->employee_id = $employee->id;
            // $receivableData->receivable_head_id = 2;
            // $receivableData->save();
        }
        // // == ===== == Receivable/Payable Account End ==>

        // ===================== for insert employee as a user start =====================
        if ($id > 0) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|same:confirm-password',
            ]);

            $user = User::find($id);
            if (!$user) {
                return redirect()->route('users.index')->with('error', 'User not found.');
            }

            $input = $request->all();

            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            $user->update($input);
            // $user->syncRoles($request->input('roles'));
            $user->assignRole('Employee');

        } else {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
            ]);

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            // $user->assignRole($request->input('roles'));
            $user->assignRole('Employee');

        }
        // ===================== for insert employee as a user End =====================

        $notify[] = ['success', $message];
        return to_route('employee.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $file = Employee::find($id);
        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Employee has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
