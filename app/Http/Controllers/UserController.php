<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create|user-edit', ['only' => ['store']]);
        $this->middleware('permission:user-create', ['only' => ['create']]);
        $this->middleware('permission:user-edit', ['only' => ['edit']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $pageTitle = "User List";
        $data = User::latest()->paginate(gs()->pagination);

        return view('users.index', compact('data', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Create User";

        $roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = 0): RedirectResponse
    {
        if ($id > 0) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'old_password' => 'required',
                'password' => 'nullable|same:confirm-password',
                'roles' => 'required'
            ]);

            $user = User::findOrFail($id); 
            $getPreviousPassword = $user->password;
            
            if (!Hash::check($request->old_password, $getPreviousPassword)) {
                $notify[] = ['error', 'Old password is not correct.'];
                return back()->withNotify($notify);
            }

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
            $user->syncRoles($request->input('roles'));

            // return redirect()->route('users.index')->with('success', 'User updated successfully');
            $message = 'User updated successfully';
            $notify[] = ['success', $message];
            return to_route('users.index')->withNotify($notify);
        } else {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'roles' => 'required'
            ]);

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($request->input('roles'));

            // return redirect()->route('users.index')->with('success', 'User created successfully');

        $message = 'User created successfully';
        $notify[] = ['success', $message];
        return to_route('users.index')->withNotify($notify);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $pageTitle = "Edit User";

        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.create', compact('user', 'roles', 'userRole', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email,' . $id,
    //         'password' => 'same:confirm-password',
    //         'roles' => 'required'
    //     ]);

    //     $input = $request->all();
    //     if (!empty($input['password'])) {
    //         $input['password'] = Hash::make($input['password']);
    //     } else {
    //         $input = Arr::except($input, array('password'));
    //     }

    //     $user = User::find($id);
    //     $user->update($input);
    //     DB::table('model_has_roles')->where('model_id', $id)->delete();

    //     $user->assignRole($request->input('roles'));

    //     return redirect()->route('users.index')
    //         ->with('success', 'User updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
