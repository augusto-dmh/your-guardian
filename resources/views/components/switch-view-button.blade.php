<div class="flex items-end">
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" value="" class="sr-only peer" id="table-view-toggle"
            {{ Auth::user()->index_view_preference === 'table' ? 'checked' : '' }} />
        <div
            class="relative w-11 h-6 peer-focus:outline-none peer-focus:ring-4 ring-orange-300 peer-focus:bg-quinary-bg rounded-full peer bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all border-gray-600 peer-checked:bg-quinary-bg">
        </div>
        <span class="text-sm font-medium text-gray-300 ms-2">{{ __('Table view') }}</span>
    </label>
</div>

<script>
    const tableView = document.querySelector('#table-view-toggle');
    tableView.addEventListener('change', function() {
        const isChecked = tableView.checked;
        const viewPreference = isChecked ? 'table' : 'cards';
        fetch(`/index-view-preference-switch/${viewPreference}`, {
            method: 'GET',
        }).then(response =>
            location.reload());
    });
</script>
