@if (canAccess('customers.index'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customers.index') }}">{{ __('Customers') }}</a>
    </li>
@endif