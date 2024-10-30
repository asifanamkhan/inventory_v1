<div>
    <div wire:loading class="spinner-border text-primary custom-loading" >
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-brands fa-bandcamp"></i> Product brands</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Product</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('brand') }}"
                        style="color: #3C50E0">brands</a></li>
            </ol>
        </nav>
    </div>

    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-md-3">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            <div class="col-md-1">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="col-md-8" style="text-align: right">
                <button @click="$dispatch('create-product-brand-modal')" type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#{{ $event }}">
                    Create new product brand
                </button>
            </div>

            <div wire:ignore.self class="modal fade" id="{{ $event }}" tabindex="-1" role="dialog"
                aria-labelledby="{{ $event }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="justify-content: space-between">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $modal_title }}</h5>
                            <b type="button" class="btn btn-sm btn-danger" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </b>
                        </div>
                        <div class="modal-body">
                            <livewire:dashboard.product.brand.brand-form />
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td  style="width: 5%">#</td>
                        <td >Brand name</td>
                        <td class="text-center" >Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultBrand) > 0)
                    @foreach ($this->resultBrand as $key => $productBrand)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultBrand->firstItem() + $key }}</td>
                        <td>{{ $productBrand->name }}</td>
                        <td style="display: flex; justify-content:center">
                            <div class="">
                                <button
                                    @click="$dispatch('product-brand-edit-modal', {id: {{ $productBrand->id }}})"
                                    data-toggle="modal" data-target="#{{ $event }}" class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px"
                                        viewBox="0 0 50 50">
                                        <path fill="white"
                                            d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z">
                                        </path>
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultBrand->links() }}</span>
    </div>
</div>

<script>

</script>

