<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        $query->where('user_id', Auth::id());

        // Status Parameter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Priority Parameter
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Search Parameter
        if ($request->has('search')) {
            $query->ofSearch($request->search);
        }

        // Sort Parameter
        if ($request->has('sort')) {
            $query->ofSort($request->sort);
        }

        return $query->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $request->merge([
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return Task::create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::where([
            ['id', '=', $id],
            ['user_id', '=', Auth::id()],
        ])->firstOrFail();

        $task->fill($request->except('user_id'));
        $task->save();

        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function complete(string $id)
    {
        $task = Task::where([
            ['id', '=', $id],
            ['user_id', '=', Auth::id()],
        ])->firstOrFail();

        if ($task->status === Task::TODO || is_null($task->completed_at)) {
            $task->status = Task::DONE;
            $task->completed_at = now();
            $task->save();
        }

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::where([
            ['id', '=', $id],
            ['user_id', '=', Auth::id()],
            ['status', '=', Task::TODO],
        ])->firstOrFail();

        $task->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
