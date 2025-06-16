<div>
    <div
        class="container grid grid-cols-12 px-5 py-10 sm:px-10 sm:py-14 md:px-36 lg:h-screen lg:max-w-[1550px] lg:py-0 lg:pl-14 lg:pr-12 xl:px-24 2xl:max-w-[1750px]">
        <div @class([
            'relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0',
            "before:content-[''] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5",
        ])>
            <div class="relative z-10 flex flex-col justify-center w-full h-full py-2 lg:py-32">
                <div
                    class="flex h-[55px] w-[55px] items-center justify-center rounded-[0.8rem] border border-primary/30">

                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="WariFact" width="34">

                </div>
                <div class="mt-10">
                    <div class="text-2xl font-medium">Iniciar sesión</div>

                    <div class="mt-6">
                        <x-base.form-label>Email*</x-base.form-label>
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="text"
                            placeholder="holamundo@wari.pe"
                            wire:model="email"
                        />
                        @error('email')
                        <div class="p-1">
                            {{ $message }}
                        </div>
                        @enderror
                        <x-base.form-label class="mt-4">Password*</x-base.form-label>
                        <x-base.form-input
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="password"
                            placeholder="************"
                            wire:model="password"
                        />
                        @error('password')
                        <div class="p-1">
                            {{ $message }}
                        </div>
                        @enderror
                        @if (session()->has('error'))
                            <x-base.alert
                                class="flex items-center mt-4"
                                variant="soft-danger"
                            >
                                <x-base.lucide
                                    class="mr-2 h-6 w-6"
                                    icon="AlertOctagon"
                                />
                                <div class="mr-2 h-6 w-6">
                                    <i class="fa-solid fa-circle-exclamation fa-xl"></i>
                                </div>
                                {{session('error')}}
                            </x-base.alert>
                        @endif
                        <div class="mt-4 text-center xl:mt-8 xl:text-left">
                            <x-base.button
                                class="w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3"
                                variant="primary"
                                rounded
                                wire:click="login"
                            >
                                Iniciar sesión
                            </x-base.button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="container fixed inset-0 grid h-screen w-screen grid-cols-12 pl-14 pr-12 lg:max-w-[1550px] xl:px-24 2xl:max-w-[1750px]">
        <div @class([
            'relative h-screen col-span-12 lg:col-span-5 2xl:col-span-4 z-20',
            "after:bg-white after:hidden after:lg:block after:content-[''] after:absolute after:right-0 after:inset-y-0 after:bg-gradient-to-b after:from-white after:to-slate-100/80 after:w-[800%] after:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0]",
            "before:content-[''] before:hidden before:lg:block before:absolute before:right-0 before:inset-y-0 before:my-6 before:bg-gradient-to-b before:from-white/10 before:to-slate-50/10 before:bg-white/50 before:w-[800%] before:-mr-4 before:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0]",
        ])></div>
        <div @class([
            'h-full col-span-7 2xl:col-span-8 lg:relative',
            "before:content-[''] before:absolute before:lg:-ml-10 before:left-0 before:inset-y-0 before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:w-screen before:lg:w-[800%]",
            "after:content-[''] after:absolute after:inset-y-0 after:left-0 after:w-screen after:lg:w-[800%] after:bg-texture-white after:bg-fixed after:bg-center after:lg:bg-[25rem_-25rem] after:bg-no-repeat",
        ])>
            <div class="sticky top-0 z-10 flex-col justify-center hidden h-screen ml-16 lg:flex xl:ml-28 2xl:ml-36">
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-5xl xl:leading-[1.2]">
                    WariFact <br> Sistema de ventas
                </div>
                <div class="mt-5 text-base leading-relaxed text-white/70 xl:text-lg">
                    Centraliza la gestión de inventario, pedidos y facturación, optimizando el
                    proceso comercial y facilitando la toma de decisiones.
                </div>
            </div>
        </div>
    </div>
</div>
