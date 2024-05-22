@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'YourGuardian')
                <img src="{{ asset('assets/logos/your-guardian-logo.png') }}" class="logo" alt="YourGuardian Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
