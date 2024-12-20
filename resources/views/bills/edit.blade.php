<x-app-layout>
    <x-slot name="header"></x-slot>

    <x-edit-form :formAction="route('bills.update', $bill)"
        :model="$bill">
        <!-- Bill Specific Fields -->

        @foreach($textFields as $textField)
            <x-edit-form-text-field :field="$textField" />
        @endforeach

        @foreach($calendarFields as $calendarField)
            <x-edit-form-calendar-field :field="$calendarField" />
        @endforeach

        @foreach($selectFields as $selectField)
            <x-edit-form-select-field :field="$selectField" :options="$selectField['options']" />
        @endforeach
    </x-edit-form>
</x-app-layout>
