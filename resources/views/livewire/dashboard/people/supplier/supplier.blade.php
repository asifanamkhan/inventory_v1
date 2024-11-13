<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-user"></i> Supplier
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">People</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('supplier') }}" style="color: #3C50E0">supplier
                        list</a></li>
            </ol>
        </nav>
    </div>

    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
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
                <a wire:navigate href='{{route('supplier-create') }}' type="button" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> New supplier
                </a>
            </div>

        </div>
        <div class="responsive-table" style="font-size: 0.9em !important;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">#</td>
                        <td style="">Photo</td>
                        <td style="">Id</td>
                        <td style="">Name</td>
                        <td style="">Email</td>
                        <td style="">Phone</td>
                        <td style="">Address</td>
                        <td class="text-center" style="width: 9%">Action</td>
                    </tr>

                </thead>
                <tbody>
                    @if (count($this->resultUser) > 0)
                    @foreach ($this->resultUser as $key => $supplier)
                    <tr wire:key='{{ $key }}'>

                        <td>{{ $this->resultUser->firstItem() + $key }}</td>
                        <td>
                            @if ($supplier->photo)
                                @php
                                    $photo = json_decode($supplier->photo)[0];

                                @endphp
                                <a target='_blank' href="{{ asset('storage/app/upload/supplier/'.$photo) }}">
                                    <img class="" style="height: 30px; width:45px" src="{{ asset('storage/app/upload/supplier/'.$photo) }}" alt="">
                                </a>
                                @else
                                    <img class="" style="height: 30px; width:45px" src="{{ asset('public/img/no-img.png') }}" alt="">
                                @endif
                        </td>
                        <td>{{ $supplier->user_id }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td style="">
                            <div class="dropdown show">
                                <button style="font-size:12px; border: 1px solid #009DE4 !important" class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" wire:navigate href="{{ route('supplier-edit', $supplier->id) }}">
                                        <i class="fa fa-edit"></i> <span>Edit</span>
                                    </a>
                                    {{-- <a class="dropdown-item d-flex gap-1" wire:navigate
                                        href="{{ route('supplier-details', $supplier->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
                                            <path
                                                d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5z" />
                                        </svg>
                                        <span>Details</span>
                                    </a> --}}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>

            </table>
        </div>
        <span>{{ $this->resultUser->links() }}</span>
    </div>
</div>
@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {

    });
</script>
@endscript

