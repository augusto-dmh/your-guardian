<x-app-layout>
    <x-slot name="header"></x-slot>

    <x-edit-form :formAction="route('tasks.update', $task)"
    :model="$task">
        <!-- Task Specific Fields -->

        @foreach($textFields as $textField)
            <x-edit-form-text-field :field="$textField" />
        @endforeach

        @foreach($selectFields as $selectField)
            <x-edit-form-select-field :field="$selectField" />
        @endforeach
    </x-edit-form>
</x-app-layout>
