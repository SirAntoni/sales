<!DOCTYPE html>
<!--
Template Name: Tailwise - Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html
    class="opacity-0"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="description"
        content="Tailwise admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities."
    >
    <meta
        name="keywords"
        content="admin template, Tailwise Admin Template, dashboard template, flat admin template, responsive admin template, web app"
    >
    <meta
        name="author"
        content="LEFT4CODE"
    >

    @yield('head')

    <!-- BEGIN: CSS Assets-->
    @stack('styles')
    <!-- END: CSS Assets-->

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<!-- END: Head -->



<body>

<x-theme-switcher/>

@yield('content')

<!-- BEGIN: Vendor JS Assets-->
@vite('resources/js/vendors/dom.js')
@vite('resources/js/vendors/tailwind-merge.js')
@stack('vendors')
<!-- END: Vendor JS Assets-->

<!-- BEGIN: Pages, layouts, components JS Assets-->
@vite('resources/js/components/base/theme-color.js')
@stack('scripts')

<!-- END: Pages, layouts, components JS Assets-->
<script src="https://kit.fontawesome.com/808596313d.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@livewireScripts
<script>
    window.addEventListener('success', event => {
        Swal.fire({
            title: 'Realizado',
            text: event.detail[0]['label'],
            icon: 'success',
            confirmButtonText: event.detail[0]['btn']
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = event.detail[0]['route'];
            }
        });
    });
    window.addEventListener('error', event => {
        Swal.fire({
            title: 'Error',
            text: event.detail[0]['label'],
            icon: 'error',
            confirmButtonText: "Cancelar",
        })
    });

    window.addEventListener('notification',event => {
        Toastify({
            node: $("#success-notification-content")
                .clone()
                .removeClass("hidden")[0],
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "bottom",
            position: "right",
            stopOnFocus: true,
        }).showToast();
    });

    window.addEventListener('delete', event => {
        Swal.fire({
            title: 'Alerta',
            text: event.detail[0]['label'],
            icon: 'warning',
            confirmButtonText: event.detail[0]['btn'],
            confirmButtonColor: "red",
            showCancelButton: true,
        }).then((result) => {
            if(result.isConfirmed){
                Swal.fire({
                    title: "Eliminado!",
                    text: "El registro se ha eliminado con exito!.",
                    icon: "success"
                });

                Livewire.dispatch('destroy',{id:event.detail[0]['id']})
            }
        });
    });

    window.addEventListener('abrir-nueva-pestania', event => {
        window.open(event.detail[0]['url'], '_blank');
    });

</script>

</body>

</html>
