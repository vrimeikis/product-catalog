@if (canAccess('api_keys.index'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('api_keys.index') }}">{{ __('Api keys') }}</a>
    </li>
@endif