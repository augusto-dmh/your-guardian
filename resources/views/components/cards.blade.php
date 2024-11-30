<div class="grid gap-4 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 2xl:grid-cols-4">
    @foreach ($instances as $instance)
        <x-card :instance="$instance" :modelName="$modelName" />
    @endforeach
</div>
