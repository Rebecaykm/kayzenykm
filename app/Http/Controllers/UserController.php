<?php

namespace App\Http\Controllers;

use App\Jobs\AddWorkCenterPartNumberJob;
use App\Jobs\ItemClassMigrationJob;
use App\Jobs\PartHierarchyMigrationJob;
use App\Jobs\PartNumberMigrationJob;
use App\Jobs\PlannerMigrationJob;
use App\Jobs\ProductionPlanMigrationJob;
use App\Jobs\StandardPackageMigrationJob;
use App\Jobs\TransactionTypeMigrationJob;
use App\Jobs\WorkcenterMigrationJob;
use App\Models\Departament;
use App\Models\Line;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     *
     */
    function data()
    {
        TransactionTypeMigrationJob::dispatch();
        // Log::info(now());
        ItemClassMigrationJob::dispatch();
        // Log::info(now());
        StandardPackageMigrationJob::dispatch();
        // Log::info(now());
        PlannerMigrationJob::dispatch();
        // Log::info(now());
        WorkcenterMigrationJob::dispatch();
        // Log::info(now());
        PartNumberMigrationJob::dispatch();
        // Log::info(now());
        AddWorkCenterPartNumberJob::dispatch();
        // Log::info(now());
        // PartHierarchyMigrationJob::dispatch();
        // Log::info(now());
        ProductionPlanMigrationJob::dispatch();
        // Log::info(now());

        return redirect('production-plan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);

        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departaments = Departament::all();
        $roles = Role::all();
        $lines = Line::all();

        return view('users.create', ['roles' => $roles, 'departaments' => $departaments, 'lines' => $lines]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $data = $request->only(['name', 'email', 'password', 'infor']);
        $data['password'] = bcrypt($data['password']);
        $data['email_verified_at'] = now();

        $user = User::create($data);

        if ($request->has('role_id')) {
            $user->roles()->sync([$request->role_id]);
        }

        if ($request->has('departaments')) {
            $user->departaments()->sync($request->departaments);
        }

        if ($request->has('lines')) {
            $user->lines()->sync($request->lines);
        }

        return redirect('users')->with('success', 'Usuario creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $departaments = Departament::all();
        $lines = Line::all();

        return view('users.edit', ['user' => $user, 'roles' => $roles, 'departaments' => $departaments, 'lines' => $lines]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->except('password', 'role_id');

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->fill($data);

        if ($user->isDirty()) {
            $user->save();
        }

        if (!empty($request->role_id)) {
            $user->roles()->sync([$request->role_id]);
        }

        $user->departaments()->sync($request->departament);

        $user->lines()->sync($request->lines);

        return redirect()->back()->with('status', 'Usuario actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
