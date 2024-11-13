<div>
    <style>
        .ql-editor {
            /* height: 70px; */
            min-height: 70px;
            overflow: auto;
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <form action="" wire:submit='save'>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Product Name <span style="color: red"> * </span></label>
                    <input wire:model='state.name' type='text' label='Name'
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div style="width: 90%">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Product Category <span style="color: red"> * </span></label>
                            <select class="form-select select2" id='product_category'>
                                <option value="">Select category</option>
                                @forelse ($product_categories as $cat)
                                <option @if ($cat->id == @$edit_select['edit_category_id'])
                                    selected
                                    @endif
                                    value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse

                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a class="btn btn-primary">+</a>
                    </div>
                </div>
                @error('category_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div style="width: 90%">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Product Brand </label>
                            <select class="form-select select2" id='product_brand'>
                                <option value="">Select type</option>
                                @forelse ($product_brands as $brand)
                                <option @if ($brand->id == @$edit_select['edit_brand_id'])
                                    selected
                                    @endif
                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse

                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a class="btn btn-primary">+</a>
                    </div>
                </div>
                @error('brand_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-2">
                <div class="d-flex align-items-center">
                    <div style="width: 90%">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Product Unit </label>
                            <select class="form-select select2" id='product_unit'>
                                <option value="">Select type</option>
                                @forelse ($product_units as $unit)
                                <option @if ($unit->id == @$edit_select['edit_unit_id'])
                                    selected
                                    @elseif ($unit->id == 1)
                                    selected
                                    @endif
                                    value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a class="btn btn-primary">+</a>
                    </div>
                </div>
                @error('unit_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>



            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Product description </label>
                    <livewire:quill-text-editor wire:model="state.description" theme="snow" />
                </div>
            </div>
            <div class="col-md-6 ">
                <div>
                    @if (count($this->editPhotos) > 0 )
                    <div class="row ">

                        @foreach ($editPhotos as $k => $p)
                        <div wire:key='{{ $k }}' class="col-3 d-flex align-items-center justify-content-center">

                            <a target="_blank" href="{{ asset('storage/app/upload/product/'.$p)}}">
                                <img style="max-width:100px; height: auto" class="img-thumbnail m-2"
                                    src="{{ asset('storage/app/upload/product/'.$p)}}" alt="">
                            </a>
                            {{-- <a href='' style="cursor: pointer" wire:click.prevent='editImgRemove({{ $k }})'>
                                <div class="dz-flex dz-items-center dz-mr-3">
                                    <button type="button" wire:click.prevent='editImgRemove({{ $k }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red"
                                            class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                            <path fill-rule="evenodd"
                                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </a> --}}
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Product images </label>
                    <livewire:dropzone wire:model="photos" :rules="['mimes:jpg,svg,png,jpeg,gif']" :multiple='false'
                        :key="'dropzone-two'" />
                </div>
            </div>
            <div class="col-md-4">
                <label for="">Product variant </label>
                <select wire:model.live='state.variant_type' name="" id="" class="form-select">
                    <option value="1">Single variant</option>
                    <option value="2">Multiple variant</option>
                    {{-- <option value="3">Combo </option> --}}
                </select>
            </div>


            <div class="col-md-12 mt-4">
                <div class="responsive-table">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-sidebar">
                            <tr>
                                <td>Description</td>
                                <td>Barcode</td>
                                <td>Purchase price</td>
                                <td>Sale price</td>
                                <td>Open stock</td>
                                <td>Alert qty</td>
                                @if ($state['variant_type'] == 2)
                                <td>
                                    <a wire:click.prevent='addVarient' class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </td>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variant_cart as $key => $variant)
                            <tr wire:key="{{ $key }}">
                                <td>
                                    <input type="text" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.description">
                                </td>
                                <td>
                                    <input type="text" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.barcode">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.purchase_price">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.sale_price">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.open_stock">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control"
                                        wire:model="variant_cart.{{ $key }}.alert_qty">
                                </td>
                                @if ($state['variant_type'] == 2)
                                <td>
                                    @if ($key != 0 && $variant['id'] == 0)
                                    <a class="btn btn-sm btn-danger" wire:click.prevent='removeVarient({{ $key }})'>
                                        <i class="fa fa-times"></i>
                                    </a>
                                    @endif
                                </td>
                                @endif

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="mt-3 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });



    $('#product_category').on('change', function(e){
        @this.set('state.category_id', e.target.value, false);
    });

    $('#product_brand').on('change', function(e){
        @this.set('state.brand_id', e.target.value, false);
    });

    $('#product_unit').on('change', function(e){
        @this.set('state.unit_id', e.target.value, false);
    });

</script>
@endscript
