<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get all Tasks
     *
     * @param ?string $status
     * @param ?int $priority
     * @param ?string $search
     * @param ?string $sort
     * @return array
     */
    public function getAllTasks(?string $status, ?int $priority, ?string $search, ?string $sort): array
    {
        $query = Task::query();

        if ($status) {
            $query->where('status', $status);
        }
        if ($priority) {
            $query->where('priority', $priority);
        }
        if ($search) {
            $query->search($search);
        }
        if ($sort) {
            $query->sorting($sort);
        }

        return $query->get()->all();
    }

    /**
     * Create Task
     *
     * @param array $requestData
     */
    public function createTask(array $requestData)
    {
        return Task::create($requestData);
    }

    /**
     * Update Task
     *
     * @param array $requestData
     * @param int $id
     */
    public function updateTask(array $requestData, int $id)
    {
        $task = Task::findOrFail($id);
        $task->fill($requestData);
        $task->save();

        return $task;
    }

    /**
     * Complete Task
     *
     * @param int $id
     */
    public function completeTask(int $id)
    {
        $task = Task::findOrFail($id);

        $ids = (new Task())->getSubTaskIds($id, false, TaskStatus::TODO->value);
        if ($ids) {
            return $task;
        }

        if ($task->status === TaskStatus::TODO || is_null($task->completed_at)) {
            $task->status = TaskStatus::DONE;
            $task->completed_at = now();
            $task->save();
        }

        return $task;
    }

    /**
     * Delete Task
     *
     * @param int $id
     */
    public function deleteTask(int $id)
    {
        $task = Task::where([
            ['id', '=', $id],
            ['status', '=', TaskStatus::TODO],
        ])->firstOrFail();

        return $task->delete();
    }
}
