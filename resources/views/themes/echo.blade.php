@extends('../themes/base')

@section('head')
    @yield('subhead')
@endsection

@section('content')
    <div @class([
        'echo group bg-gradient-to-b from-slate-200/70 to-slate-50 background relative min-h-screen',
        "before:content-[''] before:h-[370px] before:w-screen before:bg-gradient-to-t before:from-theme-1/80 before:to-theme-2 [&.background--hidden]:before:opacity-0 before:transition-[opacity,height] before:ease-in-out before:duration-300 before:top-0 before:fixed",
        "after:content-[''] after:h-[370px] after:w-screen [&.background--hidden]:after:opacity-0 after:transition-[opacity,height] after:ease-in-out after:duration-300 after:top-0 after:fixed after:bg-texture-white after:bg-contain after:bg-fixed after:bg-[center_-13rem] after:bg-no-repeat",
    ])>
        <div @class([
            '[&.loading-page--before-hide]:h-screen [&.loading-page--before-hide]:relative loading-page loading-page--before-hide',
            "[&.loading-page--before-hide]:before:block [&.loading-page--hide]:before:opacity-0 before:content-[''] before:transition-opacity before:duration-300 before:hidden before:inset-0 before:h-screen before:w-screen before:fixed before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:z-[60]",
            "[&.loading-page--before-hide]:after:block [&.loading-page--hide]:after:opacity-0 after:content-[''] after:transition-opacity after:duration-300 after:hidden after:h-16 after:w-16 after:animate-pulse after:fixed after:opacity-50 after:inset-0 after:m-auto after:bg-loading-puff after:bg-cover after:z-[61]",
        ])>
            <div @class([
                'side-menu xl:ml-0 shadow-xl transition-[margin,padding] duration-300 xl:shadow-none fixed top-0 left-0 z-50 side-menu group inset-y-0 xl:py-3.5 xl:pl-3.5',
                "after:content-[''] after:fixed after:inset-0 after:bg-black/80 after:xl:hidden",
                'side-menu--collapsed',
                '[&.side-menu--mobile-menu-open]:ml-0 [&.side-menu--mobile-menu-open]:after:block',
                '-ml-[275px] after:hidden',
            ])>
                <div @class([
                    'close-mobile-menu fixed ml-[275px] w-10 h-10 items-center justify-center xl:hidden z-50',
                    '[&.close-mobile-menu--mobile-menu-open]:flex',
                    'hidden',
                ])>
                    <a
                        class="ml-5 mt-5"
                        href=""
                    >
                        <x-base.lucide
                            class="h-8 w-8 text-white"
                            icon="X"
                        />
                    </a>
                </div>
                <div @class([
                    'side-menu__content',
                    'h-full box bg-white/[0.95] rounded-none xl:rounded-xl z-20 relative w-[275px] duration-300 transition-[width] group-[.side-menu--collapsed]:xl:w-[91px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:shadow-[6px_0_12px_-4px_#0000000f] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] overflow-hidden flex flex-col',
                ])>
                    <div @class([
                        'flex-none hidden xl:flex items-center z-10 px-5 h-[65px] w-[275px] overflow-hidden relative duration-300 group-[.side-menu--collapsed]:xl:w-[91px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px]',
                    ])>
                        <a
                            class="flex items-center transition-[margin] duration-300 group-[.side-menu--collapsed.side-menu--on-hover]:xl:ml-0 group-[.side-menu--collapsed]:xl:ml-2"
                            href=""
                        >
                            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="WariFact" width="34">

                            <div
                                class="ml-3.5 font-medium transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0">
                                WariFact
                            </div>
                        </a>
                        <a
                            class="toggle-compact-menu ml-auto hidden h-[20px] w-[20px] items-center justify-center rounded-full border border-slate-600/40 transition-[opacity,transform] hover:bg-slate-600/5 group-[.side-menu--collapsed]:xl:rotate-180 group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0 3xl:flex"
                            href=""
                        >
                            <x-base.lucide
                                class="h-3.5 w-3.5 stroke-[1.3]"
                                icon="ArrowLeft"
                            />
                        </a>
                    </div>
                    <div @class([
                        'scrollable-ref w-full h-full z-20 px-5 overflow-y-auto overflow-x-hidden pb-3 [-webkit-mask-image:-webkit-linear-gradient(top,rgba(0,0,0,0),black_30px)] [&:-webkit-scrollbar]:w-0 [&:-webkit-scrollbar]:bg-transparent',
                        '[&_.simplebar-content]:p-0 [&_.simplebar-track.simplebar-vertical]:w-[10px] [&_.simplebar-track.simplebar-vertical]:mr-0.5 [&_.simplebar-track.simplebar-vertical_.simplebar-scrollbar]:before:bg-slate-400/30',
                    ])>
                        <ul class="scrollable">
                            <!-- BEGIN: First Child -->
                            @foreach ($sideMenu as $menuKey => $menu)
                                @if (is_string($menu))
                                    <li class="side-menu__divider">
                                        {{ $menu }}
                                    </li>
                                @else
                                    @can($menu['permission'])
                                        <li>
                                            <a
                                                href="{{ isset($menu['route_name']) && Route::has($menu['route_name']) ? route($menu['route_name'], $menu['params']) : 'javascript:;' }}"
                                                @class([
                                                    'side-menu__link',
                                                    $firstLevelActiveIndex == $menuKey ? 'side-menu__link--active' : '',
                                                    $firstLevelActiveIndex == $menuKey && isset($menu['sub_menu'])
                                                        ? 'side-menu__link--active-dropdown'
                                                        : '',
                                                ])
                                            >
                                                <x-base.lucide
                                                    class="side-menu__link__icon"
                                                    :icon="$menu['icon']"
                                                />
                                                <div class="side-menu__link__title">{{ $menu['title'] }}</div>
                                                @if (isset($menu['badge']))
                                                    <div class="side-menu__link__badge">
                                                        {{ $menu['badge'] }}
                                                    </div>
                                                @endif
                                                @if (isset($menu['sub_menu']))
                                                    <x-base.lucide
                                                        class="side-menu__link__chevron"
                                                        icon="ChevronDown"
                                                    />
                                                @endif
                                            </a>
                                            <!-- BEGIN: Second Child -->
                                            @if (isset($menu['sub_menu']))
                                                <ul class="{{ $firstLevelActiveIndex == $menuKey ? 'block' : 'hidden' }}">
                                                    @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                                        @can($subMenu['permission'])
                                                        <li>
                                                            <a
                                                                href="{{ isset($subMenu['route_name']) && Route::has($subMenu['route_name']) ? route($subMenu['route_name'], $subMenu['params']) : 'javascript:;' }}"
                                                                @class([
                                                                    'side-menu__link',
                                                                    $firstLevelActiveIndex == $menuKey && $secondLevelActiveIndex == $subMenuKey
                                                                        ? 'side-menu__link--active'
                                                                        : '',
                                                                    $secondLevelActiveIndex == $subMenuKey && isset($subMenu['sub_menu'])
                                                                        ? 'side-menu__link--active-dropdown'
                                                                        : '',
                                                                ])
                                                            >
                                                                <x-base.lucide
                                                                    class="side-menu__link__icon"
                                                                    :icon="$subMenu['icon']"
                                                                />
                                                                <div class="side-menu__link__title">
                                                                    {{ $subMenu['title'] }}
                                                                </div>
                                                                @if (isset($subMenu['badge']))
                                                                    <div class="side-menu__link__badge">
                                                                        {{ $subMenu['badge'] }}
                                                                    </div>
                                                                @endif
                                                                @if (isset($subMenu['sub_menu']))
                                                                    <x-base.lucide
                                                                        class="side-menu__link__chevron"
                                                                        icon="ChevronDown"
                                                                    />
                                                                @endif
                                                            </a>
                                                            <!-- BEGIN: Third Child -->
                                                            @if (isset($subMenu['sub_menu']))
                                                                <ul
                                                                    class="{{ $secondLevelActiveIndex == $subMenuKey ? 'block' : 'hidden' }}">
                                                                    >
                                                                    @foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu)
                                                                        <li>
                                                                            <a
                                                                                href="{{ isset($lastSubMenu['route_name']) && Route::has($lastSubMenu['route_name']) ? route($lastSubMenu['route_name'], $lastSubMenu['params']) : 'javascript:;' }}"
                                                                                @class([
                                                                                    'side-menu__link',
                                                                                    $firstLevelActiveIndex == $menuKey &&
                                                                                    $secondLevelActiveIndex == $subMenuKey &&
                                                                                    $thirdLevelActiveIndex == $lastSubMenuKey
                                                                                        ? 'side-menu__link--active'
                                                                                        : '',
                                                                                    $thirdLevelActiveIndex == $lastSubMenuKey && isset($lastSubMenu['sub_menu'])
                                                                                        ? 'side-menu__link--active-dropdown'
                                                                                        : '',
                                                                                ])
                                                                            >
                                                                                <x-base.lucide
                                                                                    class="side-menu__link__icon"
                                                                                    :icon="$lastSubMenu['icon']"
                                                                                />
                                                                                <div class="side-menu__link__title">
                                                                                    {{ $lastSubMenu['title'] }}
                                                                                </div>
                                                                                @if (isset($lastSubMenu['badge']))
                                                                                    <div class="side-menu__link__badge">
                                                                                        {{ $lastSubMenu['title'] }}
                                                                                    </div>
                                                                                @endif
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                            <!-- END: Third Child -->
                                                        </li>
                                                        @endcan
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <!-- END: Second Child -->
                                        </li>
                                    @endcan
                                @endif
                            @endforeach
                            <!-- END: First Child -->
                        </ul>
                    </div>
                </div>
                <div
                    class="fixed inset-x-0 top-0 mt-3.5 h-[65px] transition-[margin] duration-100 xl:ml-[275px] group-[.side-menu--collapsed]:xl:ml-[90px]">
                    <div @class([
                        'top-bar absolute left-0 xl:left-3.5 right-0 h-full mx-5 group',
                        "before:content-[''] before:absolute before:top-0 before:inset-x-0 before:-mt-[15px] before:h-[20px] before:backdrop-blur",
                    ])>
                        <div
                            class="box group-[.top-bar--active]:box container flex h-full w-full items-center border-transparent bg-transparent shadow-none transition-[padding,background-color,border-color] duration-300 ease-in-out group-[.top-bar--active]:border-transparent group-[.top-bar--active]:bg-transparent group-[.top-bar--active]:bg-gradient-to-r group-[.top-bar--active]:from-theme-1 group-[.top-bar--active]:to-theme-2 group-[.top-bar--active]:px-5">
                            <div class="flex items-center gap-1 xl:hidden">
                                <a
                                    class="open-mobile-menu rounded-full p-2 text-white hover:bg-white/5"
                                    href=""
                                >
                                    <x-base.lucide
                                        class="h-[18px] w-[18px]"
                                        icon="AlignJustify"
                                    />
                                </a>

                            </div>
                            <!-- BEGIN: Breadcrumb -->
                            <x-base.breadcrumb
                                class="hidden flex-1 xl:block"
                                light
                            >
                                <x-base.breadcrumb.link :index="0">WariFact</x-base.breadcrumb.link>
                                <x-custom.breadcrumbs/>

                            </x-base.breadcrumb>
                            <!-- END: Breadcrumb -->
                            <!-- BEGIN: Notification & User Menu -->
                            <div class="flex flex-1 items-center">
                                <div class="ml-auto flex items-center gap-1">

                                    <a
                                        class="request-full-screen rounded-full p-2 text-white hover:bg-white/5"
                                        href=""
                                    >
                                        <x-base.lucide
                                            class="h-[18px] w-[18px]"
                                            icon="Expand"
                                        />
                                    </a>
                                </div>
                                <x-base.menu class="ml-5">
                                    <x-base.menu.button
                                        class="image-fit h-[36px] w-[36px] overflow-hidden rounded-full border-[3px] border-white/[0.15]"
                                    >

                                        <img
                                            src="{{auth()->user()->avatar_url}}"
                                            alt="{{auth()->user()->name}}"
                                        >
                                    </x-base.menu.button>
                                    <x-base.menu.items class="mt-1 w-56">
                                        <livewire:auth.logout/>
                                    </x-base.menu.items>
                                </x-base.menu>
                            </div>
                            <!-- END: Notification & User Menu -->
                        </div>
                    </div>
                </div>
            </div>
            <div @class([
                'content transition-[margin,width] duration-100 xl:pl-3.5 pt-[54px] pb-16 relative z-10 group mode',
                'content--compact',
                'xl:ml-[275px]',
                'mode--light',
                '[&.content--compact]:xl:ml-[91px]',
            ])>
                <div class="mt-16 px-5">
                    <div class="container">
                        @yield('subcontent')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/simplebar.css')
    @vite('resources/css/themes/echo.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/simplebar.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/themes/echo.js')
@endPushOnce

