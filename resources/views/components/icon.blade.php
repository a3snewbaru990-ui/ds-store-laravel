@props(['name', 'size' => null])

@php
    $class = 'icon' . ($size ? ' icon--' . $size : '');
@endphp

<svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }} @if (in_array($name, ['whatsapp', 'star', 'spark'])) fill="currentColor" @else fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" @endif>
    @switch($name)
        @case('whatsapp')
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            @break
        @case('instagram')
            <rect x="3" y="3" width="18" height="18" rx="5"/>
            <circle cx="12" cy="12" r="4"/>
            <path d="M17.5 6.5h.01"/>
            @break
        @case('arrow-right')
            <path d="M5 12h14M13 6l6 6-6 6"/>
            @break
        @case('arrow-left')
            <path d="M19 12H5M11 18l-6-6 6-6"/>
            @break
        @case('arrow-up-right')
            <path d="M7 17 17 7M8 7h9v9"/>
            @break
        @case('chevron-down')
            <path d="m6 9 6 6 6-6"/>
            @break
        @case('shield-check')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
            <path d="m9 12 2 2 4-4"/>
            @break
        @case('shield')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
            @break
        @case('bolt')
            <path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/>
            @break
        @case('clock')
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 7v5l3 2"/>
            @break
        @case('lock')
            <rect x="4" y="11" width="16" height="10" rx="2"/>
            <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
            @break
        @case('check')
            <path d="M20 6 9 17l-5-5"/>
            @break
        @case('star')
            <path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.8 1-6.1L3.2 9.4l6.1-.9L12 3Z"/>
            @break
        @case('trophy')
            <path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0V4Z"/>
            <path d="M7 6H4v2a4 4 0 0 0 4 4M17 6h3v2a4 4 0 0 1-4 4"/>
            @break
        @case('user')
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 21a8 8 0 0 1 16 0"/>
            @break
        @case('users')
            <circle cx="9" cy="8" r="3"/>
            <path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a6 6 0 0 0-4-5.65"/>
            @break
        @case('dashboard')
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            @break
        @case('receipt')
            <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/>
            <path d="M9 8h6M9 12h6M9 16h3"/>
            @break
        @case('logout')
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break
        @case('close')
            <path d="m6 6 12 12M18 6 6 18"/>
            @break
        @case('search')
            <circle cx="11" cy="11" r="7"/>
            <path d="m20 20-3.5-3.5"/>
            @break
        @case('plus')
            <path d="M12 5v14M5 12h14"/>
            @break
        @case('minus')
            <path d="M5 12h14"/>
            @break
        @case('external')
            <path d="M14 3h7v7M21 3l-9 9M10 5H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
            @break
        @case('phone')
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/>
            @break
        @case('chat')
            <path d="M20 15a3 3 0 0 1-3 3H8l-4 3V6a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v9Z"/>
            <path d="M8 10h8M8 13h5"/>
            @break
        @case('gamepad')
            <path d="M7 8h10a5 5 0 0 1 4.8 6.4l-.6 2a2.5 2.5 0 0 1-4.4 1l-1.2-1.4h-6.8l-1.2 1.4a2.5 2.5 0 0 1-4.4-1l-.6-2A5 5 0 0 1 7 8Z"/>
            <path d="M8 11v4M6 13h4M16 12h.01M18 14h.01"/>
            @break
        @case('target')
            <circle cx="12" cy="12" r="9"/>
            <circle cx="12" cy="12" r="5"/>
            <circle cx="12" cy="12" r="1"/>
            @break
        @case('eye')
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
            <circle cx="12" cy="12" r="2.5"/>
            @break
        @case('wallet')
            <path d="M4 6h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6.5A2.5 2.5 0 0 1 5.5 4H18"/>
            <path d="M16 12h5v4h-5a2 2 0 0 1 0-4Z"/>
            @break
        @case('edit')
            <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5Z"/>
            @break
        @case('trash')
            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"/>
            @break
        @case('pause')
            <rect x="7" y="5" width="3" height="14" rx="1" fill="currentColor" stroke="none"/>
            <rect x="14" y="5" width="3" height="14" rx="1" fill="currentColor" stroke="none"/>
            @break
        @case('play')
            <path d="m8 5 11 7-11 7V5Z" fill="currentColor" stroke="none"/>
            @break
        @case('spark')
            <path d="M12 2 14 9l7 3-7 3-2 7-2-7-7-3 7-3 2-7Z"/>
            @break
        @case('sparkles')
            <path d="m12 3 1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8L12 3ZM19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15Z"/>
            @break
        @case('message')
            <path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.2 9.2 0 0 1-4-.9L3 21l1.9-5A8.5 8.5 0 1 1 21 11.5Z"/>
            @break
        @case('send')
            <path d="m22 2-7 20-4-9-9-4 20-7Z"/>
            <path d="M22 2 11 13"/>
            @break
        @case('list')
            <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
            @break
        @case('settings')
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1.1-1.55 1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1.1 1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1.1 1.55 1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.16.37.38.7.66.98.3.27.67.42 1.06.42H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.51.6Z"/>
            @break
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"/>
            <path d="m3 7 9 6 9-6"/>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"/>
            <path d="M16 3v4M8 3v4M3 11h18"/>
            @break
        @case('info')
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 11v5M12 8h.01"/>
            @break
        @case('alert')
            <path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            <path d="M12 9v4M12 17h.01"/>
            @break
        @default
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 8v5M12 16h.01"/>
    @endswitch
</svg>
