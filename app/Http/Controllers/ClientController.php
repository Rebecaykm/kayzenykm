<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Project;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::query()->orderBy('name', 'DESC')->paginate(10);

        return view('client.index', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::query()->orderBy('model', 'ASC')->get();

        return view('client.create', ['projects' => $projects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client =  Client::create(['code' => $request->code, 'name' => $request->name]);

        $client->projects()->sync($request->projects);

        return redirect()->route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $projects = Project::query()->orderBy('model', 'ASC')->get();

        return view('client.edit', ['client' => $client, 'projects' => $projects]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update(['code' => $request->code, 'name' => $request->name]);

        $client->projects()->sync($request->projects);

        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->projects()->detach();

        $client->delete();

        return redirect()->back();
    }
}
