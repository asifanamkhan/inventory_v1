<div>
    @push('css')
    <style>
        .productRow {
            color: white;
            cursor: pointer;
            padding: 0rem 1rem !important;
            margin-bottom: 5px !important
        }

        .ql-editor {
            height: 70px;
            max-height: 250px;
            overflow: auto;
        }

        .productRow:hover {
            background: #8f9cff
        }

        .search__container {
            background: #227CE9 !important;
            padding: 0.2rem !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
    </style>
    @endpush
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-plus"></i> Sale return
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-return') }}">Sale return</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-return-form', $sale_id) }}"
                        style="color: #3C50E0">create</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4" wire:ignore.self>
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
        @elseif (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
        </div>
        @endif
        <form action="" wire:submit='save' onkeydown="return event.key != 'Enter';">
            <div class="row" x-data="{edit : false}">
                <div class="col-md-4">
                    <x-input required_mark='true' wire:model='state.date' name='date' type='date'
                        label='Sale return date' />
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="">Status<span style="color: red"> * </span></label>
                        <select wire:model='state.status' class="form-select" id='status'>
                            <option value="1">Returned</option>
                            <option value="2">Partial</option>
                            <option value="3">Pending</option>
                            <option value="4">Ordered</option>
                            <option value="5">Cancled</option>

                        </select>
                        @error('status')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mt-4 responsive-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-sidebar">
                                <td class="" style="width:3%">SL</td>
                                <td class="" style="width:35%">Item</td>
                                <td class="text-center" style="width:15%">Sale Qty</td>
                                <td class="text-center" style="width:15%">Return Qty</td>
                                <td class="text-center" style="width:15%">Price</td>
                                <td class="text-center" style="width:15%">Return Amt</td>
                                <td class="text-center" style="width:2%">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($saleCart as $sale_key => $sale)
                            <tr wire:key='{{ $sale_key }}' style="
                                @if ($sale['is_check'] == 1)
                                    background: #A3D1A3
                                @endif
                                "
                            >
                                <td>{{ $sale_key + 1 }}</td>
                                <td>
                                    {{ $sale['name'] }}

                                    @if (@$sale['variant_description'])
                                    | {{ $sale['variant_description'] }}
                                    @endif

                                </td>

                                <td>
                                    <input readonly wire:input.debounce.500ms='calculation({{ $sale_key }})' type="number"
                                        wire:model='saleCart.{{ $sale_key }}.qty' class="form-control text-center">
                                </td>
                                <td>
                                    <input @if ($sale['is_check']==0) readonly @endif wire:input.debounce.500ms='calculation({{ $sale_key }})' type="number"
                                        wire:model='saleCart.{{ $sale_key }}.return_qty' class="form-control text-center">
                                </td>
                                <td>
                                    <input type="number" readonly wire:input.debounce.500ms='calculation({{ $sale_key }})'
                                        wire:model='saleCart.{{ $sale_key }}.sale_price'
                                        class="form-control text-center">
                                </td>


                                <td>
                                    <input loading="lazy" tabindex="-1" type="number" style="border: 1px solid green; text-align: right"
                                        readonly class="form-control"
                                        wire:model='saleCart.{{ $sale_key }}.line_total'>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <input wire:click='saleActive({{ $sale_key }})'
                                                wire:model='saleCart.{{ $sale_key }}.is_check'
                                                class="form-check-input" type="checkbox">
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: 500; background:aliceblue">
                                <td colspan="3" style="text-align: right">Total:</td>
                                <td style="text-align: center">
                                    {{ $state['qty'] }} </td>
                                <td colspan="1" style="text-align: right"></td>
                                <td style="text-align: right">
                                    {{ $state['net_total'] }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-md-5 mt-4"></div>
                <div class="col-md-2"> </div>
                <div class="col-md-5 mt-4">
                    <table class="table table-borderless">
                        <tbody>
                            <tr style="text-align: right">
                                <td>Shipping</td>
                                <td>
                                    <input step="0.01" type="number" wire:model='state.shipping'
                                        style="text-align: right" class="form-control"
                                        wire:input.debounce.500ms='grandCalculation'>
                                </td>
                            </tr>
                            {{-- <tr style="text-align: right">
                                <td>Discount</td>
                                <td>
                                    <input step="0.01" type="number" wire:model='state.discount'
                                        style="text-align: right" class="form-control"
                                        wire:input.debounce.500ms='grandCalculation'>
                                </td>
                            </tr> --}}
                            <tr style="text-align: right">
                                <td>Net amount</td>
                                <td>
                                    <input loading="lazy" style="text-align: right" readonly class="form-control"
                                        wire:model='state.total'>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="">Sale remarks </label>
                        <livewire:quill-text-editor wire:model="state.remarks" theme="snow" />
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="">Sale documents </label>
                        <livewire:dropzone wire:model="document" :rules="['mimes:jpg,svg,png,jpeg,pdf,docx,xlsx,csv']"
                            :key="'dropzone-two'" />
                    </div>
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-center">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
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

    $('#supplier').on('change', function(e){
        @this.set('state.supplier_id', e.target.value, false);
    });

</script>
@endscript


