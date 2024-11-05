<div>
    <div class="p-4">
        <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
            <span class="sr-only">Loading...</span>
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
        @if ($mst)
        <div class="row" style="padding: 0px 8px 2px">
            <p class="col-auto">
                Total sale:
                <span class="badge bg-primary">
                    {{ number_format($mst['total'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Return:
                <span class="badge bg-warning">
                    {{ number_format($mst['pr_return'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Total paid:
                <span class='badge bg-success'>
                    {{ number_format($mst['paid'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Total due:
                <span class='badge bg-danger'>
                    {{ number_format($mst['due'], 2, '.', ',') }}
                </span>
            </p>
        </div>
        @endif
        <form action="" wire:submit='save'>
            <div style="padding: 5px 15px">
                <div style="">
                    <div class="form-group mb-3">
                        <label for="">Payment method<span style="color: red"> *
                            </span></label>
                        <select wire:model.live.debounce.500ms='paymentState.pay_mode' class="form-select"
                            id='pay_mode'>
                            @forelse ($payment_methods as $key => $method)
                            <option {{-- @if ($supplier->st_group_id ==
                                @$edit_select['edit_group_id'])
                                selected
                                @endif --}}
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
                                type='text' label='Payment Description (Ex. mobile/bank/cheque no)' />
                        </div>
                        <div class="col-md-12">
                            <x-input required_mark='' wire:model='paymentState.tran_no' name='tran_no'
                                type='text' label='Transaction number' />
                        </div>
                    </div>
                    @endif
                </div>
                <x-input required_mark='' wire:model='paymentState.amount' name='tot_paid_amt' type='number'
                    steps='0.01' label='Payment amount' />
            </div>
            <div class="mt-1 d-flex justify-content-center">
                <button class="btn btn-primary">Pay</button>
            </div>
        </form>
        {{-- <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            <div class="col-auto">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div> --}}
        <div class="responsive-table mt-4" style="font-size: 0.9em !important;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">#</td>
                        <td style="">Date</td>
                        <td style="">Memo</td>
                        <td style="">Methods</td>
                        <td style="text-align: center">Details</td>
                        <td style="text-align: center">Amount</td>
                        <td class="text-center">Action</td>

                    </tr>

                </thead>
                <tbody>
                    @if (count($this->resultPayments) > 0)
                    @foreach ($this->resultPayments as $key => $payment)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPayments->firstItem() + $key }}</td>
                        <td>{{ date('d-M-y', strtotime($payment->date)) }}</td>
                        <td>{{ $payment->voucher_no }}</td>
                        <td>
                            {{ App\Service\PaymentMethod::tranTypeCheck($payment->pay_mode) }}
                        </td>
                        <td>
                            @if ($payment->pay_mode == 1)
                            Payment by cash
                            @else
                            {{ $payment->description }}
                            @endif
                        </td>
                        <td style="text-align: right">{{ number_format($payment->amount, 2, '.','') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px"
                                    viewBox="0 0 50 50">
                                    <path fill="white"
                                        d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z">
                                    </path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



