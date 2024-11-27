<x-app-layout :contentRowDirection="true">
    <x-slot name="header">
        <h2 class="text-4xl font-bold text-center text-secondary-txt">{{ __('Manage Notifications') }}</h2>
    </x-slot>
    <form action="{{ route('user-available-notifications.savePreferences') }}" method="POST" class="flex flex-col gap-2">
        @csrf
        <div class="flex flex-col gap-4 lg:flex-row">
            <div class="flex flex-col gap-2">
                @foreach ($availableNotifications as $notification)
                    <div class="rounded-lg bg-secondary-bg p-2 {{ in_array($notification->id, $userEnabledNotifications->pluck('id')->toArray()) }}">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="notifications[]" value="{{ $notification->id }}" {{ in_array($notification->id, $userEnabledNotifications->pluck('id')->toArray()) ? 'checked' : '' }} class="cursor-pointer border-1 focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                            <div class="flex flex-col select-none">
                                <span>{{ $notification->name }}</span>
                                <p class="italic text-gray-500"> {{ $notification->description }} </p>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-col gap-2">
                <span>{{ __("Enable notifications to be sent via:") }}</span>
                @foreach ($notificationChannels as $notificationChannel)
                    <div class="p-2 rounded-lg bg-secondary-bg">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="notification_channels[]" value="{{ $notificationChannel->id }}" {{ in_array($notificationChannel->id, $userEnabledNotificationChannels->pluck('id')->toArray()) ? 'checked' : '' }} class="cursor-pointer border-1 focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                            <div class="flex select-none">
                                <p class=""> {{ $notificationChannel->name }} </p>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-4 text-right">
            <button type="submit" class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">Save Preferences</button>
        </div>
    </form>
</x-app-layout>
