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

    <form action="" wire:submit='save' onkeydown="return event.key != 'Enter';">
        <div class="row" x-data="{edit : false}">
            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.date' name='date' type='date'
                    label='Purchase return date' />
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
            <div class="col-md-12 mt-2">
                <div class="form-group mb-3">
                    <label for=""> Purchase memo search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="please type or scan purchase memo no" @input="edit = true"
                                style="padding: 1rem" wire:model.live.debounce.500ms='productsearch'
                                wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                                wire:keydown.Arrow-Up="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                                wire:keydown.enter.prevent="selectAccount" type='text' class="form-control">

                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll">
                                @if (count($resultProducts) > 0)
                                <div x-show="edit === true" class="search__container">
                                    @forelse ($resultProducts as $pk => $resultProduct)
                                    <p class="productRow" wire:click='searchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                        @click="edit = false"
                                        style="@if($searchSelect === $pk) background: #1e418685; @endif">
                                        {{ $resultProduct->memo_no }}
                                            @if (@$resultProduct->supplier_name)
                                            - {{ $resultProduct->supplier_name }}
                                            @endif
                                        <span style="font-size: 12px; font-style: italic">
                                            @if (@$resultProduct->total)
                                            , Amount: {{ $resultProduct->total }}
                                            @endif
                                        </span>

                                    </p>
                                    @empty
                                    <p>No product</p>
                                    @endforelse

                                </div>
                                @endif
                            </div>
                        </div>
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-4 responsive-table">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:3%">SL</td>
                            <td class="" style="width:35%">Item</td>
                            <td class="text-center" style="width:15%">Purchase Qty</td>
                            <td class="text-center" style="width:15%">Return Qty</td>
                            <td class="text-center" style="width:15%">Price</td>
                            <td class="text-center" style="width:15%">Returned Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseCart as $purchase_key => $purchase)
                        <tr wire:key='{{ $purchase_key }}' style="
                            @if ($purchase['is_check'] == 1)
                                background: #A3D1A3
                            @endif
                            "
                        >
                            <td>{{ $purchase_key + 1 }}</td>
                            <td>
                                {{ $purchase['name'] }}

                                @if (@$purchase['variant_description'])
                                | {{ $purchase['variant_description'] }}
                                @endif

                            </td>

                            <td>
                                <input readonly wire:input.debounce.500ms='calculation({{ $purchase_key }})' type="number"
                                    wire:model='purchaseCart.{{ $purchase_key }}.qty' class="form-control text-center">
                            </td>
                            <td>
                                <input @if ($purchase['is_check']==0) readonly @endif wire:input.debounce.500ms='calculation({{ $purchase_key }})' type="number"
                                    wire:model='purchaseCart.{{ $purchase_key }}.return_qty' class="form-control text-center">
                            </td>
                            <td>
                                <input type="number" readonly wire:input.debounce.500ms='calculation({{ $purchase_key }})'
                                    wire:model='purchaseCart.{{ $purchase_key }}.purchase_price'
                                    class="form-control text-center">
                            </td>


                            <td>
                                <input loading="lazy" tabindex="-1" type="number" style="border: 1px solid green; text-align: right"
                                    readonly class="form-control"
                                    wire:model='purchaseCart.{{ $purchase_key }}.line_total'>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <input wire:click='purchaseActive({{ $purchase_key }})'
                                            wire:model='purchaseCart.{{ $purchase_key }}.is_check'
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
            <div class="col-md-5 mt-4">
                @if (count($edit_select) == 0)
                    @if (session('payment-error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('payment-error') }}
                    </div>
                    @endif
                    <div style="border: 1px solid #DEE2E6; padding: 0 !important">
                        <div>
                            <h4 class="h4 text-center pt-2 pb-2" style="background: #0080005c">
                                Make Payment
                            </h4>
                            <h4 class="h4 text-center pt-2 pb-2" style="color: darkred">
                                @if ($pay_amt)
                                Payment amount: {{ number_format($pay_amt, 2, '.', ',') }}
                                @endif
                            </h4>
                        </div>
                        <div style="padding: 5px 15px">
                            <div class="form-group mb-3">
                                <label for="">Payment method<span style="color: red"> *
                                    </span></label>
                                <select wire:model.live.debounce.500ms='paymentState.pay_mode' class="form-select"
                                    id='pay_mode'>
                                    @forelse ($payment_methods as $key => $method)
                                    <option wire:key='{{ $key }}'
                                        value="{{ $key }}">{{ $method }}
                                    </option>
                                    @empty
                                    <option value=""></option>
                                    @endforelse
                                </select>
                                @error('pay_mode')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @if ($paymentState['pay_mode'] != 1)
                            <div class="row">
                                <div class="col-md-12">
                                    <x-input required_mark='' wire:model='paymentState.description' name='description'
                                        type='text' label='Description' />
                                </div>
                                <div class="col-md-12">
                                    <x-input required_mark='' wire:model='paymentState.tran_no' name='tran_no'
                                        type='text' label='Transaction number' />
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
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
                        @if (count($edit_select) == 0)
                        <tr style="text-align: right">
                            <td> Payment amount</td>
                            <td>
                                <input loading="lazy" type="number" step="0.01" style="text-align: right" class="form-control"
                                    wire:model='pay_amt' wire:input.debounce.500ms='grandCalculation'>
                                @if (session('payment-error'))
                                <div class="" role="alert">
                                    <span style="color: red">{{ session('payment-error') }}</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td>Due amount</td>
                            <td style="text-align:right">
                                <input style="text-align: right;" readonly class="form-control" wire:model='due'>
                            </td>
                        </tr>
                        @endif


                    </tbody>
                </table>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="">Purchase remarks </label>
                    <livewire:quill-text-editor wire:model="state.remarks" theme="snow" />
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Purchase documents </label>
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


