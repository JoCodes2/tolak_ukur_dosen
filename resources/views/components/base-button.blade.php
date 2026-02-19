@props([
    'id' => null,
    'type' => 'button',
    'variant' => 'primary',
    'text' => 'Simpan',
    'icon' => null
])

<button type="{{ $type }}" @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => "btn btn-$variant"]) }}>
    @if($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif
    {{ $text }}
</button>
