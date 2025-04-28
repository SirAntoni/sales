<div>
    <div class="grid grid-cols-12 gap-x-6 gap-y-1">
        <div class="col-span-12">
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                   Kardex - Entradas y salidas
                </div>

            </div>

        </div>
        <div class="col-span-12 mb-8">
            <div class="box box--stacked mt-3.5 flex flex-col p-5 sm:p-6">
                <div
                    class="col-span-12 relative mb-4 mt-7 rounded-[0.6rem] border border-slate-200/80 dark:border-darkmode-400">
                    <div class="absolute left-0 -mt-2 ml-4 bg-white px-3 text-xs uppercase text-slate-500">
                        <div class="-mt-px">Buscar Producto</div>
                    </div>
                    <div class="grid grid-cols-12 pt-4">
                        <div class="col-span-12 sm:col-span-12 flex flex-col gap-3.5 px-5 py-2">
                            <x-base.preview>
                                <div>
                                    <div class="mt-2" wire:ignore>
                                        <x-base.tom-select
                                            class="w-full"
                                            data-placeholder="Buscar producto por nombre u sku"
                                            wire:model.live="article"
                                        >
                                            <option value=""></option>
                                            @foreach($articles as $article)
                                                <option value="{{$article->id}}">{{$article->title}} | SKU: {{$article->sku}}</option>
                                            @endforeach

                                        </x-base.tom-select>
                                    </div>
                                    @error('articlesSelected')
                                    <div class="p-1">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </x-base.preview>
                            <x-base.source>
                                <x-base.highlight>
                                    <div>
                                        <label>Basic</label>

                                        <div class="mt-2">
                                            <x-base.tom-select
                                                class="w-full"
                                                data-placeholder="Buscar producto por nombre u sku"
                                                wire:change="article"
                                            >
                                                <option value=""></option>
                                                @foreach($articles as $article)
                                                    <option value="{{$article->id}}">{{$article->title}} | SKU: {{$article->sku}}</option>
                                                @endforeach

                                            </x-base.tom-select>
                                        </div>
                                    </div>
                                </x-base.highlight>
                            </x-base.source>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-span-12">
        <div>

            <div class="box box--stacked mt-3.5">
                <div class="overflow-auto xl:overflow-visible">
                    <x-base.table>
                        <x-base.table.thead>
                            <x-base.table.tr>

                                <x-base.table.td
                                    class="w-56 border-slate-200/80 bg-slate-50 py-5 font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem]"
                                >
                                    Fecha
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-56 border-slate-200/80 bg-slate-50 py-5 font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem]"
                                >
                                    Número
                                </x-base.table.td>
                                <x-base.table.td
                                    class="truncate border-slate-200/80 bg-slate-50 py-5 font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Contacto
                                </x-base.table.td>
                                <x-base.table.td
                                    class="truncate border-slate-200/80 bg-slate-50 py-5 font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Cliente
                                </x-base.table.td>
                                <x-base.table.td
                                    class="truncate border-slate-200/80 bg-slate-50 py-5 font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Usuario
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-32 truncate border-slate-200/80 bg-slate-50 py-5 text-right font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Entradas
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-32 truncate border-slate-200/80 bg-slate-50 py-5 text-right font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Salidas
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-32 truncate border-slate-200/80 bg-slate-50 py-5 text-right font-medium text-slate-500 first:rounded-tl-[0.6rem] last:rounded-tr-[0.6rem] text-center"
                                >
                                    Saldo
                                </x-base.table.td>

                            </x-base.table.tr>
                        </x-base.table.thead>
                        <x-base.table.tbody>

                            @if(collect($kardex)->count() > 0)

                                @foreach($kardex as $article)

                                    <x-base.table.tr class="[&_td]:last:border-b-0">

                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600"
                                        >

                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{$article->fecha}}
                                            </div>

                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600"
                                        >

                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{$article->number}}
                                            </div>

                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >
                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{($article->tipo == "salida") ? $article->contact_name:"-"}}
                                            </div>
                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >
                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{($article->tipo == "salida") ? $article->client_name:"-"}}
                                            </div>
                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >
                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{$article->user_name}}
                                            </div>
                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 text-right first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >

                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{($article->tipo == "entrada") ? $article->cantidad:"-"}}
                                            </div>

                                        </x-base.table.td>

                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 text-right first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >

                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                {{($article->tipo == "salida") ? $article->cantidad:"-"}}
                                            </div>

                                        </x-base.table.td>
                                        <x-base.table.td
                                            class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 text-right first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                        >

                                            <div
                                                class="ml-1.5 whitespace-nowrap {{($article->tipo == "entrada") ? "text-success":"text-danger"}} font-semibold">
                                                <i class="fa-solid {{($article->tipo == "entrada") ? "fa-circle-plus":"fa-circle-minus"}}"></i> {{$article->saldo}}
                                            </div>

                                        </x-base.table.td>

                                    </x-base.table.tr>

                                @endforeach

                            @else
                                <x-base.table.tr class="[&_td]:last:border-b-0">

                                    <x-base.table.td
                                        colspan="8"
                                        class="rounded-l-none rounded-r-none border-x-0 border-t-0 border-dashed py-5 first:rounded-l-[0.6rem] last:rounded-r-[0.6rem] dark:bg-darkmode-600 text-center"
                                    >

                                       Seleccione un articulo

                                    </x-base.table.td>


                                </x-base.table.tr>

                            @endif


                        </x-base.table.tbody>
                    </x-base.table>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
