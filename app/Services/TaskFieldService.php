<?php

namespace App\Services;

use App\Helpers\EnumHelper;
use App\Models\TaskCategory;

class TaskFieldService
{
    public function getTextFields($task)
    {
        return [
            ['name' => 'title', 'exhibitionName' => 'Title', 'value' => $task->title],
            ['name' => 'description', 'exhibitionName' => 'Description', 'value' => $task->description],
        ];
    }

    public function getSelectFields($task)
    {
        return [
            [
                'name' => 'status',
                'exhibitionName' => 'Status',
                'value' => $task->status,
                'options' => array_map(function ($status) {
                    return ['value' => $status, 'label' => ucfirst($status)];
                }, EnumHelper::getEnumValues('tasks', 'status'))
            ],
            [
                'name' => 'task_category_id',
                'exhibitionName' => 'Category',
                'value' => $task->taskCategory?->id,
                'options' => TaskCategory::all()->map(function ($category) {
                    return ['value' => $category->id, 'label' => $category->name];
                })->toArray()
            ]
        ];
    }

    public function getSortFields()
    {
        return ['Amount', 'Due Date'];
    }

    public function getFilterFields()
    {
        $taskStatuses = EnumHelper::getEnumValues('tasks', 'status');

        return [
            ['name' => 'Status', 'values' => $taskStatuses],
        ];
    }

    public function getCalendarFields($bill)
    {
        return [
            ['name' => 'due_date', 'exhibitionName' => 'Due Date', 'value' => $bill->due_date]
        ];
    }
}
