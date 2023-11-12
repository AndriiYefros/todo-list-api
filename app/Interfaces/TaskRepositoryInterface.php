<?php

namespace App\Interfaces;

use App\Http\Requests\TaskRequest;

interface TaskRepositoryInterface
{
    /**
     * Get All Tasks
     *
     * @param ?string $status
     * @param ?int $priority
     * @param ?string $search
     * @param ?string $sort
     */
    public function getAllTasks(?string $status, ?int $priority, ?string $search, ?string $sort);

    /**
     * Create Task
     *
     * @param array $requestData
     */
    public function createTask(array $requestData);

    /**
     * Update Task
     *
     * @param array $requestData
     * @param int $id
     */
    public function updateTask(array $requestData, int $id);

    /**
     * Complete Task
     *
     * @param int $id
     */
    public function completeTask(int $id);

    /**
     * Delete Task
     *
     * @param int $id
     */
    public function deleteTask(int $id);
}
