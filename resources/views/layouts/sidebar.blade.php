<nav id="sidebar">

    <div class="sidebar-header">
        <div>

            @php
            $company = DB::table('company')->first();
            @endphp
            @if (@$company->short_name)
            <span style="text-transform: uppercase">{{ $company->short_name }}</span>
            @else
            INVENTORY
            @endif

        </div>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a wire:navigate href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li>
            <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-screwdriver-wrench"></i> Administrator
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('company') ? 'show' : ' ' }}
        {{ request()->routeIs('user') ? 'show' : ' ' }}
        {{ request()->routeIs('user-create') ? 'show' : ' ' }}

        " id="adminSubmenu">

                <li class="{{ request()->routeIs('company') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('company') }}"> - Company</a>
                </li>
                <li class="{{ request()->routeIs('branch') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('branch') }}"> - Branch</a>
                </li>

                <li class="
                {{ request()->routeIs('user') ? 'active' : ' ' }}
                {{ request()->routeIs('user-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('user') }}"> - Users</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#peopleSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-users"></i> People
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('supplier') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-create') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-edit') ? 'show' : ' ' }}
        {{ request()->routeIs('customer') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-create') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-edit') ? 'show' : ' ' }}

        " id="peopleSubmenu">

                <li class="
                {{ request()->routeIs('supplier') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-create') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-edit') ? 'active' : ' ' }}

                 ">
                    <a class="list" wire:navigate href="{{ route('supplier') }}"> - Supplier</a>
                </li>


                <li class="
                {{ request()->routeIs('customer') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-create') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('customer') }}"> - Customer</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#productSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-users"></i> Product
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('brand') ? 'show' : ' ' }}


        " id="productSubmenu">

                <li class="{{ request()->routeIs('brand') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('brand') }}"> - Brand</a>
                </li>

                <li class="{{ request()->routeIs('category') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('category') }}"> - Category</a>
                </li>
                <li class="{{ request()->routeIs('unit') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('unit') }}"> - Unit</a>
                </li>
            </ul>
        </li>

    </ul>
</nav>
