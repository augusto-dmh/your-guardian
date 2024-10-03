<x-show-view :model="$task">
    <x-slot:form-fields>
        <x-show-form-field
            :label="__('Category:')"
            :value="__($task->taskCategory?->name ?? 'N/A')"
        />

        <x-show-form-field
            :label="__('Status:')"
            :value="__($task->status)"
        />

        <x-show-form-field
            :label="__('Created at:')"
            :value="formatDate($task->created_at)"
        />

        <x-show-form-field
            :label="__('Due date:')"
            :value="formatDate($task->due_date)"
        />
    </x-slot:form-fields>
</x-show-view>
