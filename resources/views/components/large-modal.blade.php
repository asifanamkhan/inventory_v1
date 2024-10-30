@props(['class'])
<div wire:ignore.self class="modal fade {{ $class }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
