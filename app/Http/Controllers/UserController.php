<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
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

        return view('users.create', ['roles' => $roles, 'departaments' => $departaments]);
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
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
            'infor' => ['required']
        ]);

        $data = $request->only(['name', 'email', 'password', 'infor']);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->roles()->sync($request->role_id);
        $user->departaments()->sync($request->departament);
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
        $departaments = Departament::all();
        $roles = Role::all();

        return view('users.edit', ['user' => $user, 'roles' => $roles, 'departaments' => $departaments]);
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
            $user->roles()->sync($request->role_id);
        }
        $user->departaments()->sync($request->departament);

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
