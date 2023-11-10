<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskInterface;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(TaskInterface $taskInterface)
    {
        $this->taskInterface = $taskInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->taskInterface->getAllTasks($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        return $this->taskInterface->createTask($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $id)
    {
        return $this->taskInterface->updateTask($request, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function complete(int $id)
    {
        return $this->taskInterface->completeTask($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->taskInterface->deleteTask($id);
    }
}
