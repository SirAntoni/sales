<div>
    <div class="grid grid-cols-12 gap-x-6 gap-y-10">
        <div class="col-span-12">
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                    Ventas
                </div>
                <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                    <x-base.button
                        class="group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200"
                        variant="primary"
                        onclick="window.location.href='{{ route('sales.create') }}'"
                    >
                        <div class="px-2"><i class="fa-solid fa-plus"></i></div>
                        Nueva venta
                    </x-base.button>



                </div>
            </div>
            <div class="mt-3.5">
                <div class="box box--stacked flex flex-col">
                    <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center justify-end">
                        <div>
                            <div class="relative mr-2">
                                <i class="absolute inset-y-0 left-0 z-10 my-auto ml-3.5 h-4 w-4 stroke-[1.3] text-slate-500 fa-solid fa-arrow-up-1-9"></i>
                                <x-base.litepicker
                                    id="datepicker"
                                    class="rounded-[0.5rem] pl-9 sm:w-60"
                                    data-single-mode="true"
                                    wire:model.live="date"
                                    placeholder="Ingresa un rango de fechas"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="relative mr-2">

                                <i class="absolute inset-y-0 left-0 z-10 my-auto ml-3.5 h-4 w-4 stroke-[1.3] text-slate-500 fa-solid fa-magnifying-glass"></i>
                                <x-base.form-input
                                    class="rounded-[0.5rem] pl-9 sm:w-64"
                                    type="text"
                                    placeholder="Buscar..."
                                    wire:model.live="search"
                                />
                            </div>
                        </div>

                        <div>
                            <div class="relative mr-2">

                                <i class="absolute inset-y-0 left-0 z-10 my-auto ml-3.5 h-4 w-4 stroke-[1.3] text-slate-500 fa-solid fa-arrow-up-1-9"></i>
                                <x-base.form-select
                                    class="rounded-[0.5rem] pl-9 sm:w-35"
                                    type="text"
                                    placeholder="Buscar..."
                                    wire:model.live="limit"
                                >
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="100">100</option>

                                </x-base.form-select>
                            </div>
                        </div>



                    </div>
                    <div class="overflow-auto xl:overflow-visible text-sm">
                        <x-base.table class="border-b border-slate-200/60 ">
                            <x-base.table.thead>
                                <x-base.table.tr>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Creación
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Usuario
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Cliente
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Fecha
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Total
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Cant.
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        Contacto
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        M. pago
                                    </x-base.table.td>
                                    <x-base.table.td
                                                     class="border-t border-slate-200/60 bg-slate-50 font-medium text-slate-500"
                                    >
                                        N.Orden
                                    </x-base.table.td>

                                    <x-base.table.td
                                                     class="w-36 border-t border-slate-200/60 bg-slate-50 text-center font-medium text-slate-500"
                                    >
                                        Action
                                    </x-base.table.td>
                                </x-base.table.tr>
                            </x-base.table.thead>
                            <x-base.table.tbody>
                                @if($sales->count() > 0 )
                                    @foreach ($sales as $sale)
                                        <x-base.table.tr class="[&_td]:last:border-b-0">
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600 text" >

                                                {{ $sale->created_at->format("d-m-Y h:m:s")}}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >

                                                {{ $sale->user->name}}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600">

                                                <x-base.tippy
                                                    as="x-base.button-sm"
                                                    :variant="$sale->btnDetails"
                                                    size="sm"
                                                    :content="$sale->htmlDetails"
                                                    wire:click="changeStatus({{$sale->id}})">
                                                    {{\Illuminate\Support\Str::words($sale->client->name,2,'')}}

                                                </x-base.tippy>


                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >

                                                {{ $sale->date }}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >

                                                S/. {{ $sale->total }}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="text-center border-dashed dark:bg-darkmode-600"
                                                             >
                                                {{ $sale->saleDetails->sum('quantity') }}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >

                                                {{ $sale->contact->name }}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >

                                                {{ $sale->paymentMethod->name }}

                                            </x-base.table.td-sale>
                                            <x-base.table.td-sale class="border-dashed dark:bg-darkmode-600" >
                                                {{ $sale->number }}
                                            </x-base.table.td-sale>

                                            <x-base.table.td class="border-dashed dark:bg-darkmode-600">
                                                <div class="flex items-center justify-center">
                                                    @can('update')
                                                        <x-base.button-custom
                                                            class="mr-2"
                                                            variant="success"
                                                            wire:click="edit({{$sale->id}})"
                                                        >
                                                            <i class="text-white fa-solid fa-eye"></i>
                                                        </x-base.button-custom>
                                                    @endcan
                                                    <x-base.button-custom
                                                                   class="mr-2"
                                                                   variant="soft-primary"
                                                                   wire:click="newDocument({{$sale->id}})"
                                                    >
                                                        <i class="fa-solid fa-file-circle-plus"></i>
                                                    </x-base.button-custom>
                                                    <x-base.button-custom
                                                                   class="mr-2"
                                                                   variant="dark"
                                                                   wire:click="verPDF({{$sale->id}})"
                                                    >
                                                        <i class="text-white fa-solid fa-file-pdf"></i>
                                                    </x-base.button-custom>
                                                    @can('delete')
                                                    <x-base.button-custom

                                                                   variant="danger"
                                                                   wire:click="delete({{$sale->id}})"
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
                                        <x-base.table.td colspan="10"
                                                         class=" text-center border-dashed dark:bg-darkmode-600">
                                            No se encontrarón resultados.
                                        </x-base.table.td>
                                    </x-base.table.tr>
                                @endif
                            </x-base.table.tbody>
                        </x-base.table>
                    </div>
                    <div class="m-4">
                        {{$sales->links()}}
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
                <div class="font-medium">Venta actualizada</div>
                <div class="mt-1 text-slate-500">
                    El registro fue actualizado con éxito.
                </div>
            </div>
        </x-base.notification>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const pickerFilter = new Litepicker({
            element: document.getElementById('datepicker'),
            autoApply: false,
            singleMode: false,
            numberOfColumns: 2,
            numberOfMonths: 2,
            dropdowns: {
                minYear: 2020,
                maxYear: null,
                months: true,
                years: true,
            },
        });

        pickerFilter.on('selected', (startDate, endDate) => {
            @this.set('startDate', startDate.format('YYYY-MM-DD'));
            @this.set('endDate', endDate.format('YYYY-MM-DD'));
        });

    });
</script>


