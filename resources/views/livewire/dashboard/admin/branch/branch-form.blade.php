<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>

    @endif
    <form wire:submit="@if($editForm) update @else store @endif" action="">
        <div>
            <x-input required_mark='true' wire:model='state.name' name='name' type='text'
                label='Name' />
        </div>
        <div>
            <x-input required_mark='' wire:model='state.email' name='email' type='text'
                label='Email' />
        </div>
        <div>
            <x-input required_mark='true' wire:model='state.phone' name='phone' type='text'
                label='Phone' />
        </div>
        <div>
            <x-input required_mark='true' wire:model='state.address' name='address' type='text'
                label='Address' />
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>


