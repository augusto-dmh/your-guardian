<div class="flex justify-center popup-container">
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
        <button type="button" class="absolute appearance-none cursor-pointer popup-close-btn right-2 top-14">
            <x-heroicon-o-x class="w-6 h-6" />
        </button>
    </div>
</div>

<style>
    .popup-container {
        transition: opacity 0.25s ease-in-out;
    }

    .hide {
        opacity: 0;
    }
</style>

<script>
    const popupContainer = document.querySelector('.popup-container');
    const popupCloseBtn = document.querySelector('.popup-close-btn');

    popupCloseBtn.addEventListener('click', () => {
        popupContainer.classList.add('hide');
        setTimeout(() => popupContainer.remove(), 250);
    })
</script>
