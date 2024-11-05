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

    <form action="" wire:submit='save'>
        <div class="row" x-data="{edit : false}">
            <div class="col-md-2">
                <x-input required_mark='true' wire:model='state.date' name='date' type='date'
                    label='Sale date' />
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="">Sale type<span style="color: red"> * </span></label>
                    <select wire:change='saleTypeChange' wire:model='state.sale_type' class="form-select" id='sale_type'>
                        <option value="1">Product wise</option>
                        <option value="2">Lot wise</option>
                    </select>
                    @error('status')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="">Status<span style="color: red"> * </span></label>
                    <select wire:model='state.status' class="form-select" id='status'>
                        <option value="1">Received</option>
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
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div style="width: 90%">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Customer<span style="color: red"> * </span></label>
                            <select class="form-select select2" id='customer'>
                                <option value="">Select customer</option>
                                @forelse ($customers as $customer)
                                <option wire:key='{{ $customer->id }}' @if ($customer->id == @$edit_select['customer_id'])
                                    selected
                                    @endif
                                    value="{{ $customer->id }}">{{ $customer->name }}</option>
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
                @error('customer_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for=""> Purchase no search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10%; border: 1px solid #DFE2E6;padding: 5px;border-radius: 4px;">
                            <i style="font-size: 16px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="purchase memo no" @input="edit = true"
                                 wire:model.live.debounce.500ms='psearch'
                                type='text' class="form-control">

                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll; z-index: 1000">
                                @if (count($resultPurchase) > 0)
                                <div x-show="edit === true" class="search__container">
                                    @forelse ($resultPurchase as $pk => $result)
                                    <p class="productRow" wire:click='prSearchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                        @click="edit = false"
                                        style="@if($searchSelect === $pk) background: #1e418685; @endif">
                                        {{ $result->memo_no }}
                                            @if (@$result->supplier_name)
                                            - {{ $result->supplier_name }}
                                            @endif
                                    </p>
                                    @empty
                                    <p>No product</p>
                                    @endforelse

                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                    <label for=""> Product search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="please type product name or code or scan barcode" @input="edit = true"
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
                                        {{ $resultProduct->name }}
                                        @if (@$resultProduct->variant_description)
                                        | {{ $resultProduct->variant_description }}
                                        @endif
                                        <span style="font-size: 12px; font-style: italic">
                                            @if (@$resultProduct->sale_price)
                                            , MRP: {{ $resultProduct->sale_price }}
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
                            <td class="" style="width:40%">Item</td>
                            <td class="text-center" style="width:15%">Qty</td>
                            <td class="text-center" style="width:20%">Price</td>
                            <td class="text-center" style="width:20%">Total Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($saleCart as $sale_key => $sale)
                        <tr wire:key='{{ $sale_key }}'>
                            <td>{{ $sale_key + 1 }}</td>
                            <td>
                                {{ $sale['name'] }}

                                @if (@$sale['variant_description'])
                                | {{ $sale['variant_description'] }}
                                @endif

                            </td>

                            <td>
                                <input wire:input.debounce.500ms='calculation({{ $sale_key }})' type="number"
                                    wire:model='saleCart.{{ $sale_key }}.qty' class="form-control text-center">
                            </td>
                            <td>
                                <input wire:input.debounce.500ms='calculation({{ $sale_key }})' type="number"
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
                                    <a type="button" wire:click.prevent='removeItem(
                                    {{ $sale_key }} ,
                                     {{ $sale['product_id'] }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red"
                                            class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                            <path fill-rule="evenodd"
                                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: 500; background:aliceblue">
                            <td colspan="2" style="text-align: right">Total:</td>
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
                                    <option wire:key='{{ $key }}' value="{{ $key }}">{{ $method }}</option>
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
                        <tr style="text-align: right">
                            <td>Discount</td>
                            <td>
                                <input step="0.01" type="number" wire:model='state.discount'
                                    style="text-align: right" class="form-control"
                                    wire:input.debounce.500ms='grandCalculation'>
                            </td>
                        </tr>
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

@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });

    $('#customer').on('change', function(e){
        @this.set('state.customer_id', e.target.value, false);
    });

</script>
@endscript


