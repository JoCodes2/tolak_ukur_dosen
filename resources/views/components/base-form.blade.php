@props([
    'id',
    'action' => '#'
])

<form id="{{ $id }}" action="{{ $action }}" method="POST" {{ $attributes }}>
    @csrf
    <div class="row">
        {{ $slot }}
    </div>
</form>
