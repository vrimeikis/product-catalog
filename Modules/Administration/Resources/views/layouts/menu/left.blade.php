<ul class="navbar-nav mr-auto">
    @auth('admin')
        @foreach(\Nwidart\Modules\Facades\Module::getOrdered() as $module)
            @includeIf($module->getLowerName() . '::menu.menu')
        @endforeach
    @endauth
</ul>