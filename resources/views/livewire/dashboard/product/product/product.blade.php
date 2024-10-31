<div>
    <div wire:loading class="spinner-border text-primary custom-loading" >
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-s fa-bandcamp"></i> Product s</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Product</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('product') }}"
                        style="color: #3C50E0">s</a>product</li>
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
        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td  style="width: 5%">#</td>
                        <td >Image</td>
                        <td >Name</td>
                        <td >Category</td>
                        <td >Brand</td>
                        <td >Unit</td>
                        <td >Status</td>
                        <td class="text-center" >Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultProduct) > 0)
                    @foreach ($this->resultProduct as $key => $item)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultProduct->firstItem() + $key }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category_name}}</td>
                        <td>{{ $item->brand_name}}</td>
                        <td>{{ $item->unit_name}}</td>
                        <td>{{ $item->status}}</td>
                        <td style="display: flex; justify-content:center">
                            <div class="">


                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultProduct->links() }}</span>
    </div>
</div>

<script>

</script>


