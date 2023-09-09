<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::query()->orderBy('model', 'DESC')->paginate(10);

        return view('project.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::query()->orderBy('name', 'DESC')->get();

        return view('project.create', ['clients' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project =  Project::create(['type' => $request->type, 'model' => $request->model, 'prefixe' => $request->prefixe]);

        $project->clients()->sync($request->clients);

        return redirect()->route('project.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::query()->orderBy('name', 'DESC')->get();

        return view('project.edit', ['project' => $project, 'clients' => $clients]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update(['type' => $request->type, 'model' => $request->model, 'prefixe' => $request->prefixe]);

        $project->clients()->sync($request->clients);

        return redirect()->route('project.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->clients()->detach();

        $project->delete();

        return redirect()->back();
    }
}
