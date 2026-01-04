<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $pageTitle = "Role List";
        $roles = Role::orderBy('id', 'DESC')->paginate(gs()->pagination);
        return view('roles.index', compact('roles', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(): View
    {
        $pageTitle = "Create Role";
        
        $permissions = Permission::all()
            ->groupBy('section_name') 
            ->sortBy(function ($permissions, $sectionName) {
                return $permissions->first()->order; 
            });
    
        return view('roles.create', compact('permissions', 'pageTitle'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = 0): RedirectResponse
    {
        $validationRules = [
            'name' => 'required',
            'permission' => 'required|array',
        ];

        if ($id > 0) {
            $this->validate($request, $validationRules);

            $role = Role::find($id);

            if (!$role) {
                return redirect()->route('roles.index')
                    ->with('error', 'Role not found');
            }

            $role->name = $request->input('name');
            $role->save();

            $permissionsID = array_map('intval', $request->input('permission'));

            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully');
        }

        $this->validate($request, array_merge($validationRules, [
            'name' => 'required|unique:roles,name',
        ]));

        $permissionsID = array_map('intval', $request->input('permission'));

        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $pageTitle = "Edit Role";

        $role = Role::find($id);
        $permissions = Permission::orderBy('section_name')->orderBy('order')->get()->groupBy('section_name');
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
            return view('roles.create', compact('role', 'permissions', 'rolePermissions', 'pageTitle'));
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
    //         'permission' => 'required',
    //     ]);

    //     $role = Role::find($id);
    //     $role->name = $request->input('name');
    //     $role->save();

    //     $permissionsID = array_map(
    //         function ($value) {
    //             return (int)$value;
    //         },
    //         $request->input('permission')
    //     );

    //     $role->syncPermissions($permissionsID);

    //     return redirect()->route('roles.index')
    //         ->with('success', 'Role updated successfully');
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
