{{-- Employees dropdown --}}
@if (canAccessAny(['admins.index', 'roles.index', '']))
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false" v-pre>
            {{ __('Employees') }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @if (canAccess('admins.index'))
                <a class="dropdown-item" href="{{ route('admins.index') }}">{{ __('Admins') }}</a>
            @endif
            @if (canAccess('roles.index'))
                <a class="dropdown-item" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
            @endif
        </div>
    </li>
@endif