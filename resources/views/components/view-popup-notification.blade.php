<div class="flex justify-center">
    <div class="fixed z-10 px-4 py-2 text-2xl rounded-md shadow-inner top-6 text-tertiary-txt bg-primary-bg"
        role="alert">
        <div class="flex flex-col items-center">
            <p>
                {{ __($description) }}
            </p>
            <a class="mt-2 text-center shadow-inner text-secondary-txt hover:underline" href="{{ $ctaRoute }}">
                {{ __($ctaText) }}!
            </a>
        </div>
    </div>
</div>
