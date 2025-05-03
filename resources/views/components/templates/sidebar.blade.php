@php
    use App\Helpers\MenuHelper;
    $menus = MenuHelper::getSidebarMenus();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link d-flex align-items-center">
           Makam Purwosari
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        @foreach ($menus as $menu)
            @php
                $childCountForHeader = 0;
                if ($menu->type === 'HEADER') {
                    $childCountForHeader = $menus
                        ->filter(function ($child) use ($menu) {
                            return $child->header === $menu->title &&
                                $child->type !== 'HEADER' &&
                                MenuHelper::canViewMenu($child);
                        })
                        ->count();
                }
            @endphp

            @if ($menu->type === 'HEADER')
                @if ($menu->title && $childCountForHeader > 0)
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ $menu->title }}</span>
                    </li>
                @endif
            @elseif ($menu->type === 'PARENT')
                @if (MenuHelper::canViewMenu($menu))
                    @php
                        $children = $menu->children->filter(function ($child) {
                            return MenuHelper::canViewMenu($child);
                        });
                    @endphp

                    @if ($children->count() > 0)
                        <li class="menu-item {{ MenuHelper::isActiveMenu($menu) ? 'active' : '' }}">
                            <a href="javascript:void(0)" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                                <div>{{ $menu->title }}</div>
                            </a>
                            <ul class="menu-sub">
                                @foreach ($children as $child)
                                    <li class="menu-item {{ MenuHelper::isActiveMenu($child) ? 'active' : '' }}">
                                        <a href="{{ $child->route ? route($child->route) : 'javascript:void(0)' }}"
                                            class="menu-link">
                                            <div>{{ $child->title }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="menu-item {{ MenuHelper::isActiveMenu($menu) ? 'active' : '' }}">
                            <a href="{{ $menu->route ? route($menu->route) : 'javascript:void(0)' }}"
                                class="menu-link">
                                <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                                <div>{{ $menu->title }}</div>
                            </a>
                        </li>
                    @endif
                @endif
            @elseif ($menu->type === 'SUB PARENT')
                @if (MenuHelper::canViewMenu($menu))
                    <li class="menu-item {{ MenuHelper::isActiveMenu($menu) ? 'active' : '' }}">
                        <a href="{{ $menu->route ? route($menu->route) : 'javascript:void(0)' }}" class="menu-link">
                            <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                            <div>{{ $menu->title }}</div>
                        </a>
                    </li>
                @endif
            @endif

        @endforeach

    </ul>
</aside>
