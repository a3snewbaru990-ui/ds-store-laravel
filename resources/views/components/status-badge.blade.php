@props(['tone' => 'neutral', 'label'])

<span {{ $attributes->merge(['class' => 'badge badge--' . $tone]) }}>
    <span class="badge__dot" aria-hidden="true"></span>
    {{ $label }}
</span>
