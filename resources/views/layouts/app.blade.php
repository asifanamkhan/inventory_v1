<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('public/img/favicon.png') }}">
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'Page Title' }}</title>


    <link rel="stylesheet" href="{{ asset('public/build/assets/app-6EXYc7Fs.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/build/assets/app-Bg1aHGgo.js') }}" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap -->

    <!-- Font Awesome JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>

    {{-- select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- select2 --}}


    {{-- Text editor --}}

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    {{-- Text editor --}}

    {{-- date range --}}

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- date range --}}
    {{-- @vite(['resources/css/app.css']) --}}


    <script type="text/javascript">
        $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

    });

    function redirectToRoute(select) {
            const selectedValue = select.value;
            if (selectedValue) {
                window.open(selectedValue, '_blank'); // Redirect to the selected route
            }
        }

    </script>

    <link rel="stylesheet" href="{{ asset('public/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/livewire-error.css') }}">
    @stack('css')
    @livewireStyles
</head>

<body style="overflow-x: hidden">
    <div class="wrapper">

        <!-- Sidebar  -->
        @include('layouts.sidebar')

        <!-- Page Content  -->
        <div id="content">

            @persist('nav-bar')

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-align-left"></i>

                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item d-flex align-items-center justify-content-center">
                                {{-- <select onchange="redirectToRoute(this)" name="" id="" class="form-select">
                                    <option value="">Quick access</option>

                                    <option wire:navigate value="{{ route('sale-create') }}">
                                        Add Sale
                                    </option>
                                    <option wire:navigate value="{{ route('sale-return-create') }}">
                                        Add Sale Return
                                    </option>
                                    <option wire:navigate value="{{ route('purchase-create') }}">
                                        Add Purchase
                                    </option>
                                    <option wire:navigate value="{{ route('purchase-return-create') }}">
                                        Add Purchase Return
                                    </option>
                                </select> --}}
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle profile-img"
                                        alt="Profile Photo"> <!-- Replace with your logo URL -->
                                </a>
                                <div class="dropdown-menu drop-nav-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        href="{{ route('logout') }}">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
            @endpersist
            <div class="main-contant">
                {{ $slot }}
            </div>
            <div class="footer">
                Ultimate POS - V6.4 | Copyright © 2024 All rights reserved.
            </div>
        </div>
    </div>

    @livewireScripts




</body>

</html>
