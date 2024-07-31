<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div
        class="absolute w-3/4 transform -translate-x-1/2 -translate-y-1/2 rounded-md shadow-inner sm:w-3/4 md:w-2/4 top-1/2 left-1/2 bg-secondary-bg">
        <div class="flex flex-col justify-center p-6">

            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-4xl font-bold text-secondary-txt">Task #{{ $task->id }}</h2>
                    <div class="flex items-center">
                        <a href="{{ route('tasks.edit', ['task' => $task]) }}"
                            class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
                            </svg>
                        </a>
                        <form action="{{ route('tasks.destroy', ['task' => $task]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="block rounded-full cursor-pointer hover:shadow-inner text-tertiary-txt hover:text-secondary-txt">
                                <svg class="w-8 h-8 p-1 rounded-full hover:text-secondary-txt" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="overflow-auto break-all max-h-32">
                    <p>{{ $task->description }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 [&>div>span]:text-tertiary-txt">

                <div class="flex gap-2">
                    <span>Title:</span>
                    <div>{{ $task->title }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Category:</span>
                    <div>{{ $task->taskCategory?->name ?? 'none' }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Status</span>
                    <div>{{ $task->status }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Created at:</span>
                    <div>{{ $task->created_at->format('m-d-Y') }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Due date:</span>
                    <div>{{ $task->due_date->format('m-d-Y') }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
