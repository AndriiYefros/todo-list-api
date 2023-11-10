<?php

namespace App\Repositories;

use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{
    public function getAllTasks(Request $request)
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

        return $query->get()->all();
    }

    public function createTask(TaskRequest $request)
    {
        $request->merge([
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return Task::create($request->all());
    }

    public function updateTask(TaskRequest $request, $id)
    {
        $task = Task::where([
            ['id', '=', $id],
            ['user_id', '=', Auth::id()],
        ])->firstOrFail();

        $task->fill($request->except(['id', 'user_id']));
        $task->save();

        return $task;
    }

    public function completeTask($id)
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

    public function deleteTask($id)
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
