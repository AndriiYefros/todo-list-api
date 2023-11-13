<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;

class TaskService
{
    private TaskRepositoryInterface $taskRepositoryInterface;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * Get List Tasks
     *
     * @param ?string $status
     * @param ?int $priority
     * @param ?string $search
     * @param ?string $sort
     * @return array
     */
    public function getListTasks(?string $status, ?int $priority, ?string $search, ?string $sort): array
    {
        return $this->taskRepositoryInterface->getAllTasks($status, $priority, $search, $sort);
    }

    /**
     * Create Task
     *
     * @param array $requestData
     */
    public function createTask(array $requestData)
    {
        return $this->taskRepositoryInterface->createTask($requestData);
    }

    /**
     * Update Task
     *
     * @param array $requestData
     * @param int $id
     */
    public function updateTask(array $requestData, int $id)
    {
        return $this->taskRepositoryInterface->updateTask($requestData, $id);
    }

    /**
     * Complete Task
     *
     * @param int $id
     */
    public function completeTask(int $id)
    {
        return $this->taskRepositoryInterface->completeTask($id);
    }

    /**
     * Delete Task
     *
     * @param int $id
     */
    public function deleteTask(int $id)
    {
        return $this->taskRepositoryInterface->deleteTask($id);
    }
}
