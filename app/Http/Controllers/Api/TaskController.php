<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskService $taskService;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->has('status') && $request->status ? $request->status : null;
        $priority = $request->has('priority') && $request->priority ? $request->priority : null;
        $search = $request->has('search') && $request->search ? $request->search : null;
        $sort = $request->has('sort') && $request->sort ? $request->sort : null;

        $allTasks =  $this->taskService->getListTasks($status, $priority, $search, $sort);

        return response()->json([
            'success' => true,
            'message' => 'Task List',
            'data' => $allTasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $requestData = $request->all();

        $task = $this->taskService->createTask($requestData);

        return response()->json([
            'success' => true,
            'message' => 'Task successfully created',
            'data' => $task,
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $requestData = $request->except(['user_id']);

        $task = $this->taskService->updateTask($requestData, $id);

        return response()->json([
            'success' => true,
            'message' => 'Task successfully updated',
            'data' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(int $id): JsonResponse
    {
        $task = $this->taskService->completeTask($id);

        return response()->json([
            'success' => true,
            'message' => 'Task successfully completed',
            'data' => $task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteTask($id);

        return response()->json([
            'success' => true,
            'message' => 'Task successfully deleted',
            'data' => [],
        ], Response::HTTP_NO_CONTENT);
    }
}
