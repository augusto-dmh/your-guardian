<?php

namespace App\Services;

use App\Helpers\EnumHelper;

class BillFieldService
{
    public function getTextFields($bill)
    {
        return [
            ['name' => 'title', 'exhibitionName' => 'Title', 'value' => $bill->title],
            ['name' => 'description', 'exhibitionName' => 'Description', 'value' => $bill->description],
            ['name' => 'amount', 'exhibitionName' => 'Amount', 'value' => $bill->amount],
        ];
    }

    public function getSelectFields($bill)
    {
        return [
            [
                'name' => 'status',
                'exhibitionName' => 'Status',
                'value' => $bill->status,
                'options' => array_map(function ($status) {
                    return ['value' => $status, 'label' => ucfirst($status)];
                }, EnumHelper::getEnumValues('bills', 'status'))
            ]
        ];
    }

    public function getSortFields()
    {
        return ['Amount', 'Due Date'];
    }

    public function getFilterFields()
    {
        $billStatuses = EnumHelper::getEnumValues('bills', 'status');

        return [
            ['name' => 'Status', 'values' => $billStatuses],
        ];
    }

    public function getCalendarFields($bill)
    {
        return [
            ['name' => 'due_date', 'exhibitionName' => 'Due Date', 'value' => $bill->due_date]
        ];
    }
}
