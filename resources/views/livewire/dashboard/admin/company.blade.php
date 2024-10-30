<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Company</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">administrator</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('company') }}" style="">company</a></li>
            </ol>
        </nav>
    </div>

    <form wire:submit='save' action="">
        <div class="card p-4">
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @elseif (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <x-input required_mark='true'  wire:model='state.name' name='name' type='text' label='Company name' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='true' wire:model='state.short_name' name='short_name' type='text' label='Short name' />
                </div>

                <div class="col-md-6">
                    <x-input required_mark='true' wire:model='state.phone' name='phone' type='text' label='Company phone' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='true' wire:model='state.email' name='email' type='text' label='Company email' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='' wire:model='state.website' name='website' type='text' label='Company websitesite' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='' wire:model='state.license_no' name='license_no' type='text' label='Company license no' />
                </div>
                <div class="col-md-12">
                    <x-input required_mark='true' wire:model='state.address' name='address' type='text' label='Company address' />
                </div>
                <div class="col-md-6">
                    <div>
                        @if (count($this->editPhotos) > 0 )
                        <div class="row ">

                         @foreach ($editPhotos as $k => $p)
                             <div wire:key='{{ $k }}' class="col-3 d-flex align-items-center justify-content-center">

                                 <a target="_blank" href="{{ asset('storage/app/upload/company/'.$p)}}">
                                     <img style="max-width:100px; height: auto" class="img-thumbnail m-2" src="{{ asset('storage/app/upload/company/'.$p)}}" alt="">
                                 </a>

                             </div>
                         @endforeach
                        </div>
                     @endif
                </div>
                    <div class="form-group">
                        <label for="">Logo </label>
                        <livewire:dropzone wire:model="photos" :rules="['mimes:jpg,svg,png,jpeg']"
                            :key="'dropzone-one'" />
                    </div>
                </div>
            </div>
            {{-- @if ($update) --}}
                <div class="mt-2 d-flex justify-content-center">
                    <button class="btn btn-primary">Save</button>
                </div>
            {{-- @endif --}}

        </div>
    </form>

</div>

