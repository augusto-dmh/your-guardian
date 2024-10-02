@php
    $previousUrl = url()->previous();
    $isEditUrl = preg_match("/{$modelName}s\/\d+\/edit/", $previousUrl);
    $backRoute = !$isEditUrl ? $previousUrl : session('previous_url_not_edit');
@endphp

<div class="p-6 m-auto rounded-sm shadow-inner form-wrapper h-fit w-fit">
    <div class="w-20 h-20 m-auto">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="w-full h-full" />
        </a>
    </div>

    <form action="{{ $formAction }}"
        method="post">
        @csrf
        @method('PUT')

        <fieldset class="flex flex-col gap-4">
            <!-- Title Field -->
            <div class="flex flex-col gap-1">
                <label for="title"
                    class="cursor-pointer text-secondary-txt">{{ __('Title') }}:</label>
                <input type="text"
                    name="title"
                    placeholder="Title"
                    value="{{ old('title', $model->title) }}"
                    id="title"
                    class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                @error('title')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customizable Fields (slot for select, etc.) -->
            {{ $slot }}

            <!-- Description Field -->
            <div class="flex flex-col gap-1">
                <label for="description"
                    class="cursor-pointer text-secondary-txt">{{ __('Description') }}:</label>
                <textarea id="description"
                    name="description"
                    rows="4"
                    cols="50"
                    class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">{{ old('description', $model->description) }}</textarea>
                @error('description')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </fieldset>

        <div class="flex gap-3 mt-8">
            <button type="submit"
                class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Update') }}</button>
            <a href="{{ $backRoute }}"
                class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Back') }}</a>
        </div>
    </form>
</div>
