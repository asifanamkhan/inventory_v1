<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Customer</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">people</a></li>
                <li class="breadcrumb-item "><a wire:navigate href="{{ route('customer') }}" style="">customer</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('customer-create') }}" style="">create</a></li>
            </ol>
        </nav>
    </div>

    @livewire('dashboard.people.customer.form')

</div>




