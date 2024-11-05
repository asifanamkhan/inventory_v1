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
            <div class="col-md-8" style="text-align: right">
                <a wire:navigate href='{{route('product-create') }}' type="button" class="btn btn-primary">Create new product</a>
            </div>
        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td  style="width: 5%">#</td>
                        <td style="width: 10%" >Image</td>
                        <td >Name</td>
                        <td >Category</td>
                        <td >Brand</td>
                        <td >Unit</td>
                        <td class="text-center" >Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultProduct) > 0)
                    @foreach ($this->resultProduct as $key => $item)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultProduct->firstItem() + $key }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                @if ($item->images)
                                @php
                                $photo = json_decode($item->images)[0];

                                @endphp
                                <a target='_blank' href="{{ asset('storage/app/upload/product/'.$photo) }}">
                                    <img class="" style="height: 30px; width:45px"
                                        src="{{ asset('storage/app/upload/product/'.$photo) }}" alt="">
                                </a>
                                @else
                                <img class="" style="height: 30px; width:45px"
                                    src="{{ asset('public/img/no-img.png') }}" alt="">
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category_name}}</td>
                        <td>{{ $item->brand_name}}</td>
                        <td>{{ $item->unit_name}}</td>
                        <td style="">
                            <div class="d-flex justify-content-center gap-2">
                                <a wire:navigate href="{{ route('product-edit',$item->u_code) }}"
                                    class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px"
                                        viewBox="0 0 50 50">
                                        <path fill="white"
                                            d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z">
                                        </path>
                                    </svg>
                                </a>
                                <button class="btn btn-sm btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path fill=white
                                            d="M 10 2 L 9 3 L 3 3 L 3 5 L 4.109375 5 L 5.8925781 20.255859 L 5.8925781 20.263672 C 6.023602 21.250335 6.8803207 22 7.875 22 L 16.123047 22 C 17.117726 22 17.974445 21.250322 18.105469 20.263672 L 18.107422 20.255859 L 19.890625 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z M 6.125 5 L 17.875 5 L 16.123047 20 L 7.875 20 L 6.125 5 z">
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
        <span>{{ $this->resultProduct->links() }}</span>
    </div>
</div>

<script>

</script>


