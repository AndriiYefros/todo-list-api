<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    protected $taskRepositoryInterface;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->has('status') && $request->status ? $request->status : null;
        $priority = $request->has('priority') && $request->priority ? $request->priority : null;
        $search = $request->has('search') && $request->search ? $request->search : null;
        $sort = $request->has('sort') && $request->sort ? $request->sort : null;

        return $this->taskRepositoryInterface->getAllTasks($status, $priority, $search, $sort);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $requestData = $request->all();

        return $this->taskRepositoryInterface->createTask($requestData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $id)
    {
        $requestData = $request->except(['id', 'user_id']);

        return $this->taskRepositoryInterface->updateTask($requestData, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function complete(int $id)
    {
        return $this->taskRepositoryInterface->completeTask($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->taskRepositoryInterface->deleteTask($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
