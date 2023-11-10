<?php

namespace App\Interfaces;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

interface TaskInterface
{
    /**
     * Get all Tasks
     */
    public function getAllTasks(Request $request);

    /**
     * Create Task
     *
     * @param \App\Http\Requests\TaskRequest $request
     */
    public function createTask(TaskRequest $request);

    /**
     * Update Task
     *
     * @param \App\Http\Requests\TaskRequest $request
     * @param integer $id
     */
    public function updateTask(TaskRequest $request, int $id);

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
