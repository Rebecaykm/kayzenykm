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
        $departaments = Departament::orderBy('name', 'ASC')->get();
        $roles = Role::all();
        $lines = Line::with('departament')
            ->orderBy(function ($query) {
                $query->select('name')
                    ->from('departaments')
                    ->whereColumn('lines.departament_id', 'departaments.id');
            })
            ->orderBy('name')
            ->get();
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
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:table_name'],
            'password' => ['required', 'string', 'min:8'],
            'infor' => ['nullable', 'string'],
            'role_id' => ['required', 'integer'],
            'departaments' => ['required', 'array'],
            'lines' => ['required', 'array']
        ]);

        $data = $request->only(['name', 'username', 'email', 'password', 'infor']);
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

        return redirect()->back()->with('success', 'Usuario creado con éxito');
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
        $departaments = Departament::orderBy('name', 'ASC')->get();
        $lines = Line::with('departament')
            ->orderBy(function ($query) {
                $query->select('name')
                    ->from('departaments')
                    ->whereColumn('lines.departament_id', 'departaments.id');
            })
            ->orderBy('name')
            ->get();

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

    /**
     * Función para iniciar sesión.
     */
    public function signIn(Request $request)
    {
        // Buscar al usuario por su nombre de usuario o correo electrónico
        $user = User::query()
            ->where('username', $request->email)
            ->orWhere('email', $request->email)
            ->first();

        // Verificar si el usuario no existe
        if ($user == null) {
            return redirect()->back()->withErrors('Usuario no encontrado. Verifique sus credenciales e intente de nuevo.');
        }

        // Verificar la contraseña
        $password = $request->password;

        if (!\Hash::check($password, $user->password)) {
            return redirect()->back()->withErrors('Contraseña incorrecta. Verifique sus credenciales e intente de nuevo.');
        }

        // Iniciar sesión del usuario
        \Auth::login($user);

        // Redireccionar al panel de control después del inicio de sesión exitoso
        return redirect()->route('dashboard');
    }
}
