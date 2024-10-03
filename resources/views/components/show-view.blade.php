<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div
        class="absolute w-3/4 transform -translate-x-1/2 -translate-y-1/2 rounded-md shadow-inner left-1/2 sm:w-3/4 md:w-2/4 top-1/2 bg-secondary-bg">
        <div class="flex flex-col justify-center p-6">
            <div class="mb-8">
                <div class="flex items-center justify-between gap-4 mb-2">
                    <h2 class="text-4xl font-bold break-all text-secondary-txt">
                        {{ $model->title }}</h2>
                    <div class="flex items-center">
                        <x-edit-button :model="$model" />

                        <x-delete-button :model="$model" />

                        @isset($exclusiveActionButton)
                            {{ $exclusiveActionButton }}
                        @endisset
                    </div>
                </div>
                <div class="overflow-auto break-all max-h-32">{{ $model->description }}</div>
            </div>

            <div class="flex flex-col gap-3 [&>div>span]:text-tertiary-txt">
                {{ $formFields }}
            </div>
        </div>
    </div>
</x-app-layout>
