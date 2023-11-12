<?php

namespace App\Repositories;

use App\Http\Requests\TaskRequest;
use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks($status, $priority, $search, $sort)
    {
        $query = Task::query();

        // Status Parameter
        if ($status) {
            $query->where('status', $status);
        }

        // Priority Parameter
        if ($priority) {
            $query->where('priority', $priority);
        }

        // Search Parameter
        if ($search) {
            $query->search($search);
        }

        // Sort Parameter
        if ($sort) {
            $query->sorting($sort);
        }

        return $query->get()->all();
    }

    public function createTask($requestData)
    {
        return Task::create($requestData);
    }

    public function updateTask($requestData, $id)
    {
        $task = Task::findOrFail($id);
        $task->fill($requestData);
        $task->save();

        return $task;
    }

    public function completeTask($id)
    {
        $task = Task::findOrFail($id);

        $ids = (new Task)->getSubTaskIds($id, false, 'todo');
        if ($ids) {
            return $task;
        }

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
