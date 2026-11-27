@props(['icon' => 'receipt', 'title', 'description', 'actionLabel' => null, 'actionUrl' => null])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <span class="empty-state__icon"><x-icon :name="$icon" /></span>
    <h3>{{ $title }}</h3>
    <p>{{ $description }}</p>
    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn--primary btn--sm">{{ $actionLabel }}</a>
    @endif
</div>
