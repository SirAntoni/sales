@extends('../themes/' . $activeTheme)

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-x-6 gap-y-10">
        <div class="col-span-12">
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                    General Report
                </div>
                <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                    <div class="relative">
                        <x-base.lucide
                            class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] group-[.mode--light]:!text-slate-200"
                            icon="CalendarCheck2"
                        />
                        <x-base.form-select
                            class="rounded-[0.5rem] pl-9 group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:bg-chevron-white group-[.mode--light]:!text-slate-200 sm:w-44"
                        >
                            <option value="custom-date">Custom Date</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </x-base.form-select>
                    </div>
                    <div class="relative">
                        <x-base.lucide
                            class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] group-[.mode--light]:!text-slate-200"
                            icon="Calendar"
                        />
                        <x-base.litepicker
                            class="rounded-[0.5rem] pl-9 group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200 sm:w-64"
                        />
                    </div>
                </div>
            </div>
            <div class="mt-3.5 grid grid-cols-12 gap-5">
                <div class="col-span-12 p-1 box box--stacked md:col-span-6 2xl:col-span-3">
                    <div
                        class="-mx-1 h-[244px] overflow-hidden [&_.tns-nav]:bottom-auto [&_.tns-nav]:ml-5 [&_.tns-nav]:mt-5 [&_.tns-nav]:w-auto [&_.tns-nav_button.tns-nav-active]:w-5 [&_.tns-nav_button.tns-nav-active]:bg-white/70 [&_.tns-nav_button]:mx-0.5 [&_.tns-nav_button]:h-2 [&_.tns-nav_button]:w-2 [&_.tns-nav_button]:bg-white/40">
                        <x-base.tiny-slider config="fade">
                            <div class="px-1">
                                <div
                                    class="relative flex h-full w-full flex-col overflow-hidden rounded-[0.5rem] bg-gradient-to-b from-theme-2/90 to-theme-1/[0.85] p-5">
                                    <x-base.lucide
                                        class="absolute right-0 top-0 -mr-5 -mt-5 h-36 w-36 rotate-[-10deg] transform fill-white/[0.03] stroke-[0.3] text-white/20"
                                        icon="Medal"
                                    />
                                    <div class="mt-12 mb-9">
                                        <div class="text-2xl font-medium leading-snug text-white">
                                            New feature
                                            <br>
                                            unlocked!
                                        </div>
                                        <div class="mt-1.5 text-lg text-white/70">
                                            Boost your performance!
                                        </div>
                                    </div>
                                    <a
                                        class="flex items-center font-medium text-white"
                                        href=""
                                    >
                                        Upgrade now
                                        <x-base.lucide
                                            class="ml-1.5 h-4 w-4"
                                            icon="MoveRight"
                                        />
                                    </a>
                                </div>
                            </div>
                            <div class="px-1">
                                <div
                                    class="relative flex h-full w-full flex-col overflow-hidden rounded-[0.5rem] bg-gradient-to-b from-theme-2/90 to-theme-1/90 p-5">
                                    <x-base.lucide
                                        class="absolute right-0 top-0 -mr-5 -mt-5 h-36 w-36 rotate-[-10deg] transform fill-white/[0.03] stroke-[0.3] text-white/20"
                                        icon="Database"
                                    />
                                    <div class="mt-12 mb-9">
                                        <div class="text-2xl font-medium leading-snug text-white">
                                            Stay ahead
                                            <br>
                                            with upgrades
                                        </div>
                                        <div class="mt-1.5 text-lg text-white/70">
                                            New features and updates!
                                        </div>
                                    </div>
                                    <a
                                        class="flex items-center font-medium text-white"
                                        href=""
                                    >
                                        Discover now
                                        <x-base.lucide
                                            class="ml-1.5 h-4 w-4"
                                            icon="ArrowRight"
                                        />
                                    </a>
                                </div>
                            </div>
                            <div class="px-1">
                                <div
                                    class="relative flex h-full w-full flex-col overflow-hidden rounded-[0.5rem] bg-gradient-to-b from-theme-2/90 to-theme-1/90 p-5">
                                    <x-base.lucide
                                        class="absolute right-0 top-0 -mr-5 -mt-5 h-36 w-36 rotate-[-10deg] transform fill-white/[0.03] stroke-[0.3] text-white/20"
                                        icon="Gauge"
                                    />
                                    <div class="mt-12 mb-9">
                                        <div class="text-2xl font-medium leading-snug text-white">
                                            Supercharge
                                            <br>
                                            your workflow
                                        </div>
                                        <div class="mt-1.5 text-lg text-white/70">
                                            Boost performance!
                                        </div>
                                    </div>
                                    <a
                                        class="flex items-center font-medium text-white"
                                        href=""
                                    >
                                        Get started
                                        <x-base.lucide
                                            class="ml-1.5 h-4 w-4"
                                            icon="ArrowRight"
                                        />
                                    </a>
                                </div>
                            </div>
                        </x-base.tiny-slider>
                    </div>
                </div>
                <div class="flex flex-col col-span-12 p-5 box box--stacked md:col-span-6 2xl:col-span-3">
                    <x-base.menu class="absolute top-0 right-0 mt-5 mr-5">
                        <x-base.menu.button class="w-5 h-5 text-slate-500">
                            <x-base.lucide
                                class="w-6 h-6 fill-slate-400/70 stroke-slate-400/70"
                                icon="MoreVertical"
                            />
                        </x-base.menu.button>
                        <x-base.menu.items class="w-40">
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Copy"
                                /> Copy Link
                            </x-base.menu.item>
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Trash"
                                />
                                Delete
                            </x-base.menu.item>
                        </x-base.menu.items>
                    </x-base.menu>
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 border rounded-full border-primary/10 bg-primary/10">
                            <x-base.lucide
                                class="w-6 h-6 fill-primary/10 text-primary"
                                icon="Database"
                            />
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-medium">41k Products Sold</div>
                            <div class="mt-0.5 text-slate-500">Across 21 stores</div>
                        </div>
                    </div>
                    <div class="relative mt-5 mb-6 overflow-hidden">
                        <div
                            class="absolute inset-0 my-auto h-px whitespace-nowrap text-xs leading-[0] tracking-widest text-slate-400/60">
                            .......................................................................
                        </div>
                        <x-report-line-chart
                            class="relative z-10 -ml-1.5"
                            data-index="2"
                            data-border-color="primary"
                            data-background-color="primary/0.3"
                            height="h-[100px]"
                        />
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-primary/70"></div>
                            <div class="ml-2.5">Products Sold</div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                            <div class="ml-2.5">Store Locations</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col col-span-12 p-5 box box--stacked md:col-span-6 2xl:col-span-3">
                    <x-base.menu class="absolute top-0 right-0 mt-5 mr-5">
                        <x-base.menu.button class="w-5 h-5 text-slate-500">
                            <x-base.lucide
                                class="w-6 h-6 fill-slate-400/70 stroke-slate-400/70"
                                icon="MoreVertical"
                            />
                        </x-base.menu.button>
                        <x-base.menu.items class="w-40">
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Copy"
                                /> Copy Link
                            </x-base.menu.item>
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Trash"
                                />
                                Delete
                            </x-base.menu.item>
                        </x-base.menu.items>
                    </x-base.menu>
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 border rounded-full border-success/10 bg-success/10">
                            <x-base.lucide
                                class="w-6 h-6 fill-success/10 text-success"
                                icon="Files"
                            />
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-medium">
                                36k Products Shipped
                            </div>
                            <div class="mt-0.5 text-slate-500">
                                Across 14 warehouses
                            </div>
                        </div>
                    </div>
                    <div class="relative mt-5 mb-6 overflow-hidden">
                        <div
                            class="absolute inset-0 my-auto h-px whitespace-nowrap text-xs leading-[0] tracking-widest text-slate-400/60">
                            .......................................................................
                        </div>
                        <x-report-line-chart
                            class="relative z-10 -ml-1.5"
                            data-index="0"
                            data-border-color="success"
                            data-background-color="success/0.3"
                            height="h-[100px]"
                        />
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-success/70"></div>
                            <div class="ml-2.5">Total Shipped</div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                            <div class="ml-2.5">Warehouses</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col col-span-12 p-5 box box--stacked md:col-span-6 2xl:col-span-3">
                    <x-base.menu class="absolute top-0 right-0 mt-5 mr-5">
                        <x-base.menu.button class="w-5 h-5 text-slate-500">
                            <x-base.lucide
                                class="w-6 h-6 fill-slate-400/70 stroke-slate-400/70"
                                icon="MoreVertical"
                            />
                        </x-base.menu.button>
                        <x-base.menu.items class="w-40">
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Copy"
                                /> Copy Link
                            </x-base.menu.item>
                            <x-base.menu.item>
                                <x-base.lucide
                                    class="w-4 h-4 mr-2"
                                    icon="Trash"
                                />
                                Delete
                            </x-base.menu.item>
                        </x-base.menu.items>
                    </x-base.menu>
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center w-12 h-12 border rounded-full border-primary/10 bg-primary/10">
                            <x-base.lucide
                                class="w-6 h-6 fill-primary/10 text-primary"
                                icon="Zap"
                            />
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-medium">
                                3.7k Orders Processed
                            </div>
                            <div class="mt-0.5 text-slate-500">Across 9 regions</div>
                        </div>
                    </div>
                    <div class="relative mt-5 mb-6">
                        <x-report-donut-chart-3
                            class="relative z-10"
                            height="h-[100px]"
                        />
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-primary/70"></div>
                            <div class="ml-2.5">Ventas</div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-danger/70"></div>
                            <div class="ml-2.5">Compras</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
