<div>
    <style>
         .ql-editor {
            height: 70px;
            max-height: 250px;
            overflow: auto;
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading" role="status">
        <span class="sr-only">Loading...</span>
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
                    <x-input required_mark='true' wire:model='state.name' name='name' type='text'
                        label='Name' />
                </div>

                <div class="col-md-6">
                    <x-input required_mark='' wire:model='state.phone' name='phone' type='text'
                        label='Phone' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='' wire:model='state.email' name='email' type='text'
                        label='Email' />
                </div>
                <div class="col-md-6">
                    <x-input required_mark='' wire:model='state.address' name='address' type='text'
                        label='Address' />
                </div>
                <div class="col-md-6">
                    <div>
                        @if (count($this->editPhotos) > 0 )
                        <div class="row ">

                            @foreach ($editPhotos as $k => $p)
                            <div wire:key='{{ $k }}' class="col-3 d-flex align-items-center justify-content-center">

                                <a target="_blank" href="{{ asset('storage/app/upload/customer/'.$p)}}">
                                    <img style="max-width:100px; height: auto" class="img-thumbnail m-2"
                                        src="{{ asset('storage/app/upload/customer/'.$p)}}" alt="">
                                </a>

                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Photo </label>
                        <livewire:dropzone wire:model="photos" :rules="['mimes:jpg,svg,png,jpeg']"
                            :key="'dropzone-one'" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Remarks </label>
                        <livewire:quill-text-editor wire:model="state.description" theme="snow" />
                    </div>
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-center">
                <button class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>

</div>

