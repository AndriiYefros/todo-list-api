<?php

namespace App\Interfaces;

use App\Http\Requests\TaskRequest;

interface TaskRepositoryInterface
{
    /**
     * Get all Tasks
     */
    public function getAllTasks($status, $priority, $search, $sort);

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
     * @param integer $id
     */
    public function updateTask(array $requestData, int $id);

    /**
     * Complete Task
     *
     * @param integer $id
     */
    public function completeTask(int $id);

    /**
     * Delete Task
     *
     * @param integer $id
     */
    public function deleteTask(int $id);
}
