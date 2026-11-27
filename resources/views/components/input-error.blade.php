@props(['name'])

@error($name)
    <p {{ $attributes->merge(['class' => 'field-error', 'id' => $name . '-error']) }}>{{ $message }}</p>
@enderror
