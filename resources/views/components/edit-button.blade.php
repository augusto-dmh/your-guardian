<a
    href="{{ route(Str::plural($modelName) . '.edit', [$modelName => $model]) }}"
    class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-8 h-8 p-1 hover:text-secondary-txt"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z"
        />
    </svg>
</a>
