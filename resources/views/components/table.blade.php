<div class="w-full overflow-x-auto rounded-lg">
    <table class="w-full bg-secondary-bg">
        <x-columns :modelName="$modelName" />
        @foreach ($instances as $instance)
            <tr
                class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                <x-row :instance="$instance" :modelName="$modelName" />
            </tr>
        @endforeach
    </table>
</div>
