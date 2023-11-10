<?php

namespace App\Repositories;

use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskRepository implements TaskInterface
{
    public function getAllTasks(Request $request)
    {
        $query = Task::query();

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
            $query->search($request->search);
        }

        // Sort Parameter
        if ($request->has('sort')) {
            $query->sorting($request->sort);
        }

        return $query->get()->all();
    }

    public function createTask(TaskRequest $request)
    {
        return Task::create($request->all());
    }

    public function updateTask(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->fill($request->except(['id', 'user_id']));
        $task->save();

        return $task;
    }

    public function completeTask($id)
    {
        $task = Task::findOrFail($id);

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
            ['status', '=', Task::TODO],
        ])->firstOrFail();

        return $task->delete();
    }
}
