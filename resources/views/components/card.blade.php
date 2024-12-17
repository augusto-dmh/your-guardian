<div class="relative rounded-md shadow-inner bg-secondary-bg">
    <div class="flex flex-col justify-center px-6 py-4">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <a class="mr-2" href="{{ route($modelName . 's.show', $instance) }}">
                            <h3
                                class="max-w-full text-2xl font-bold break-words text-tertiary-txt hover:underline hover:text-secondary-txt title">
                                {{ Str::limit($instance->title, 20, '...') }}
                            </h3>
                        </a>
                        <div class="absolute w-6 h-6 bottom-4 right-6">
                            <x-card-icon :instance="$instance" :modelName="$modelName" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <form action="{{ route($modelName . 's.destroy', $instance) }}" method="POST">
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
                    <a href="{{ route($modelName . 's.edit', $instance) }}"
                        class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="overflow-auto font-thin break-all text-primary-txt max-h-32">
                <p>{{ Str::limit($instance->description, 45, '...') ?? 'No description available' }}</p>
            </div>
        </div>
    </div>
</div>
