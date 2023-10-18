<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::orderBy('name', 'ASC')->paginate(10);

        return view('status.index', ['statuses' => $statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        $data = array_map('strtoupper', $request->validated());
        $status = Status::create($data);

        return redirect('status');
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return view('status.edit', ['status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {
        $status->fill($request->validated());

        if ($status->isDirty()) {
            $status->save();
        }

        return redirect('status');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return redirect()->back();
    }
}
