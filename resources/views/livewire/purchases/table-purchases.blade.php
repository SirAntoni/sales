<div>
    <div class="grid grid-cols-12 gap-x-6 gap-y-10">
        <div class="col-span-12">
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                    Compras
                </div>
                <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                    <x-base.button
                        class="group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200"
                        variant="primary"
                        onclick="window.location.href='{{ route('purchases.create') }}'"
                    >
                        <div class="px-2"><i class="fa-solid fa-plus"></i></div>
                        Nueva compra
                    </x-base.button>
                </div>
            </div>
            <div class="mt-3.5">
                <div class="box box--stacked flex flex-col">
                    <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center justify-end">
                        <div>
                            <div class="relative">

                                <i class="absolute inset-y-0 left-0 z-10 my-auto ml-3.5 h-4 w-4 stroke-[1.3] text-slate-500 fa-solid fa-magnifying-glass"></i>
                                <x-base.form-input
                                    class="rounded-[0.5rem] pl-9 sm:w-64"
                                    type="text"
                                    placeholder="Buscar por proveedor..."
                                    wire:model.live="search"
                                />
                            </div>
                        </div>


                    </div>
                    <div class="overflow-auto xl:overflow-visible">
                        <x-base.table class="border-b border-slate-200/60">
                            <x-base.table.thead>
                                <x-base.table.tr>
                                    <x-base.table.td
                                        class="border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500"
                                    >
                                        Proveedor
                                    </x-base.table.td>
                                    <x-base.table.td
                                        class="border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500"
                                    >
                                        Fecha
                                    </x-base.table.td>
                                    <x-base.table.td
                                        class="border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500"
                                    >
                                        Factura
                                    </x-base.table.td>
                                    <x-base.table.td
                                        class="border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500"
                                    >
                                        Pasajero
                                    </x-base.table.td>
                                    <x-base.table.td
                                        class="border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500"
                                    >
                                        Total
                                    </x-base.table.td>
                                    <x-base.table.td
                                        class="w-36 border-t border-slate-200/60 bg-slate-50 py-4 text-center font-medium text-slate-500"
                                    >
                                        Action
                                    </x-base.table.td>
                                </x-base.table.tr>
                            </x-base.table.thead>
                            <x-base.table.tbody>
                                @if($purchases->count() > 0 )
                                    @foreach ($purchases as $purchase)
                                        <x-base.table.tr class="[&_td]:last:border-b-0">
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600">

                                                {{ $purchase->provider->name }}

                                            </x-base.table.td>
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600">
                                                {{ $purchase->created_at->format("d-m-Y")}}
                                            </x-base.table.td>
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600 text-white">
                                                <x-base.tippy
                                                    as="x-base.button-sm"
                                                    :variant="$purchase->btnColor"
                                                    :size="$purchase->btnSize"
                                                    wire:click="changeStatus({{$purchase->id}})">
                                                    {{$purchase->document}}
                                                </x-base.tippy>
                                            </x-base.table.td>
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600">
                                                {{ $purchase->passenger }}
                                            </x-base.table.td>
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600">
                                                $ {{ number_format($purchase->total,2) }}
                                            </x-base.table.td>
                                            <x-base.table.td class="border-dashed py-4 dark:bg-darkmode-600">
                                                <div class="flex items-center justify-center">
                                                    <x-base.button-custom
                                                        class="mr-2"
                                                        variant="success"
                                                        wire:click="edit({{$purchase->id}})"
                                                    >
                                                        <i class="text-white fa-solid fa-eye"></i>
                                                    </x-base.button-custom>
                                                    @can('delete')
                                                        <x-base.button-custom
                                                            variant="danger"
                                                            wire:click="delete({{$purchase->id}})"
                                                        >
                                                            <i class="text-white fa-solid fa-xmark"></i>
                                                        </x-base.button-custom>
                                                    @endcan
                                                </div>
                                            </x-base.table.td>
                                        </x-base.table.tr>
                                    @endforeach
                                @else
                                    <x-base.table.tr>
                                        <x-base.table.td colspan="9"
                                                         class=" text-center border-dashed py-4 dark:bg-darkmode-600">
                                            No se encontrarón resultados.
                                        </x-base.table.td>
                                    </x-base.table.tr>
                                @endif
                            </x-base.table.tbody>
                        </x-base.table>
                    </div>
                    <div class="m-4">
                        {{$purchases->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <x-base.notification
            class="flex"
            id="success-notification-content"
        >
            <x-base.lucide
                class="text-success"
                icon="CheckCircle"
            />
            <div class="ml-4 mr-4">
                <div class="font-medium">Compra actualizada</div>
                <div class="mt-1 text-slate-500">
                    El registro fue actualizado con éxito.
                </div>
            </div>
        </x-base.notification>
    </div>
</div>
