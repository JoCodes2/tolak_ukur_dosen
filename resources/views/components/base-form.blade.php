@props([
    'id',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    <input
        type="{{ $type }}"
        class="form-control"
        id="{{ $id }}"
        name="{{ $id }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
</div>
