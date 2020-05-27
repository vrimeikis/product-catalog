@if(canAccessAny(['supplier.index', 'categories.index', 'products.index']))
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false" v-pre>
            {{ __('Catalog') }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

            @if (canAccess('categories.index'))
                <a class="dropdown-item" href="{{ route('categories.index') }}">{{ __('Categories') }}</a>
            @endif

            @if (canAccess('products.index'))
                <a class="dropdown-item" href="{{ route('products.index') }}">{{ __('Products') }}</a>
            @endif

            @if (canAccess('supplier.index'))
                <a class="dropdown-item" href="{{ route('supplier.index') }}">{{ __('Supplier') }}</a>
            @endif

        </div>
    </li>
@endif