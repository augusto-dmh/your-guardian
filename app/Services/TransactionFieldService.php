<?php

namespace App\Services;

use App\Helpers\EnumHelper;
use App\Models\TransactionCategory;

class TransactionFieldService
{
    public function getTextFields($transaction)
    {
        return [
            ['name' => 'amount', 'exhibitionName' => 'Amount', 'value' => $transaction->amount],
        ];
    }

    public function getSelectFields($transaction)
    {
        return [
            [
                'name' => 'type',
                'exhibitionName' => 'Type',
                'value' => $transaction->type,
                'options' => array_map(function ($type) {
                    return ['value' => $type, 'label' => $type];
                }, EnumHelper::getEnumValues('transactions', 'type'))
            ],
            [
                'name' => 'transaction_category_id',
                'exhibitionName' => 'Category',
                'value' => $transaction->transactionCategory?->id,
                'options' => TransactionCategory::all()->map(function ($category) {
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
        $transactionTypes = EnumHelper::getEnumValues('transactions', 'type');

        return [
            ['name' => 'Type', 'values' => $transactionTypes],
        ];
    }
}
