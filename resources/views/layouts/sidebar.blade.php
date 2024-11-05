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
        {{ request()->routeIs('branch') ? 'show' : ' ' }}
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
        {{ request()->routeIs('category') ? 'show' : ' ' }}
        {{ request()->routeIs('unit') ? 'show' : ' ' }}
        {{ request()->routeIs('product') ? 'show' : ' ' }}
        {{ request()->routeIs('product-create') ? 'show' : ' ' }}
        {{ request()->routeIs('product-edit') ? 'show' : ' ' }}
        {{-- {{ request()->routeIs('product-details') ? 'show' : ' ' }} --}}


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
                <li class="
                {{ request()->routeIs('product') ? 'active' : ' ' }}
                {{ request()->routeIs('product-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('product') }}"> - Product</a>
                </li>
                <li class="{{ request()->routeIs('product-create') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-create') }}"> - Add new product</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#purchaseSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-cart-shopping"></i> Purchase
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('purchase') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-create') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-details') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-create') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-edit') ? 'show' : ' ' }}
            {{-- {{ request()->routeIs('purchase-return-details') ? 'show' : ' ' }}  --}}

        " id="purchaseSubmenu">
                <li class="
                {{ request()->routeIs('purchase') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase') }}"> - Purchase list</a>
                </li>
                <li class="
                {{ request()->routeIs('purchase-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase-create') }}"> - Purchase entry</a>
                </li>
                <li class="
                {{ request()->routeIs('purchase-return') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-create') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-edit') ? 'active' : ' ' }}
                {{-- {{ request()->routeIs('purchase-return-details') ? 'active' : ' ' }} --}}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase-return') }}"> - Purchase return</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#saleSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-scale-balanced"></i> Sale
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('sale') ? 'show' : ' ' }}
            {{ request()->routeIs('sale-create') ? 'show' : ' ' }}
            {{ request()->routeIs('sale-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('sale-details') ? 'show' : ' ' }}
            {{-- {{ request()->routeIs('purchase-return') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-create') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-details') ? 'show' : ' ' }} --}}

        " id="saleSubmenu">
                <li class="
                {{ request()->routeIs('sale') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('sale') }}"> - Sale list</a>
                </li>
                <li class="
                {{ request()->routeIs('sale-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('sale-create') }}"> - Sale entry</a>
                </li>
                {{-- <li class="
                {{ request()->routeIs('purchase-return') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-create') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase-return') }}"> - Purchase return</a>
                </li> --}}
            </ul>
        </li>

    </ul>
</nav>
