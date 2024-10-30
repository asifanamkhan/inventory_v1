@props(['eventName','modalTitle'])

<div wire:ignore.self class="modal fade" id="{{ $eventName }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $eventName }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="justify-content: space-between">
                <h5 class="modal-title" id="exampleModalLabel">{{ $modalTitle }}</h5>
                <b type="button" class="btn btn-sm btn-danger" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </b>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
