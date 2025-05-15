@php
    // Configuración centralizada de breadcrumbs
    $breadcrumbsMap = [
        'dashboard' => [
            ['title' => 'Dashboard']
        ],
        'articles.index' => [
            ['title' => 'Artículos'],
            ['title' => 'Listar', 'active' => true]
        ],
        'articles.create' => [
            ['title' => 'Artículos'],
            ['title' => 'Crear', 'active' => true]
        ],
        'articles.show' => [
            ['title' => 'Artículos'],
            ['title' => 'Editar', 'active' => true]
        ],
        'categories.index' => [
            ['title' => 'Categorías'],
            ['title' => 'Listar', 'active' => true]
        ],
        'categories.create' => [
            ['title' => 'Categorías'],
            ['title' => 'Crear', 'active' => true]
        ],
        'categories.show' => [
            ['title' => 'Categorías'],
            ['title' => 'Editar', 'active' => true]
        ],
        'purchases.index' => [
            ['title' => 'Compras'],
            ['title' => 'Listar', 'active' => true]
        ],
        'purchases.create' => [
            ['title' => 'Compras'],
            ['title' => 'Crear', 'active' => true]
        ],
        'purchases.show' => [
            ['title' => 'Compras'],
            ['title' => 'Editar', 'active' => true]
        ],
        'providers.index' => [
            ['title' => 'Proveedores'],
            ['title' => 'Listar', 'active' => true]
        ],
        'providers.create' => [
            ['title' => 'Proveedores'],
            ['title' => 'Crear', 'active' => true]
        ],
        'providers.show' => [
            ['title' => 'Proveedores'],
            ['title' => 'Editar', 'active' => true]
        ],
        'canceled_purchases' => [
            ['title' => 'Compras'],
            ['title' => 'Anuladas', 'active' => true]
        ],
        'sales.index' => [
            ['title' => 'Ventas'],
            ['title' => 'Listar', 'active' => true]
        ],
        'sales.create' => [
            ['title' => 'Ventas'],
            ['title' => 'Crear', 'active' => true]
        ],
        'sales.show' => [
            ['title' => 'Ventas'],
            ['title' => 'Editar', 'active' => true]
        ],
        'canceled' => [
            ['title' => 'Ventas'],
            ['title' => 'Anuladas', 'active' => true]
        ],
        'clients.index' => [
            ['title' => 'Clientes'],
            ['title' => 'Listar', 'active' => true]
        ],
        'clients.create' => [
            ['title' => 'Clientes'],
            ['title' => 'Crear', 'active' => true]
        ],
        'clients.show' => [
            ['title' => 'Clientes'],
            ['title' => 'Editar', 'active' => true]
        ],
        'users.index' => [
            ['title' => 'Usuarios'],
            ['title' => 'Listar', 'active' => true]
        ],
        'users.create' => [
            ['title' => 'Usuarios'],
            ['title' => 'Crear', 'active' => true]
        ],
        'users.show' => [
            ['title' => 'Usuarios'],
            ['title' => 'Editar', 'active' => true]
        ],
        'settings' => [
            ['title' => 'General']
        ],
        'vouchers.index' => [
            ['title' => 'Comprobantes'],
            ['title' => 'Listar', 'active' => true]
        ],
        'vouchers.create' => [
            ['title' => 'Comprobantes'],
            ['title' => 'Crear', 'active' => true]
        ],
        'vouchers.show' => [
            ['title' => 'Comprobantes'],
            ['title' => 'Editar', 'active' => true]
        ],
        'contacts.index' => [
            ['title' => 'Contactos'],
            ['title' => 'Listar', 'active' => true]
        ],
        'contacts.create' => [
            ['title' => 'Contactos'],
            ['title' => 'Crear', 'active' => true]
        ],
        'contacts.show' => [
            ['title' => 'Contactos'],
            ['title' => 'Editar', 'active' => true]
        ],
        'payment-methods.index' => [
            ['title' => 'Métodos de pago'],
            ['title' => 'Listar', 'active' => true]
        ],
        'payment-methods.create' => [
            ['title' => 'Métodos de pago'],
            ['title' => 'Crear', 'active' => true]
        ],
        'payment-methods.show' => [
            ['title' => 'Métodos de pago'],
            ['title' => 'Editar', 'active' => true]
        ],
        'documents.index' => [
            ['title' => 'Documentos'],
            ['title' => 'Listar', 'active' => true]
        ],
        'documents.show' => [
            ['title' => 'Documentos'],
            ['title' => 'Emitir', 'active' => true]
        ],
        'kardex' => [
            ['title' => 'Kardex']
        ],
        'reports' => [
            ['title' => 'Reportes']
        ]
    ];

    $currentRoute = Route::currentRouteName();

    $breadcrumbs = $breadcrumbsMap[$currentRoute] ?? [];
@endphp

@foreach($breadcrumbs as $index => $breadcrumb)
    <x-base.breadcrumb.link
        :index="$index + 1"
        :active="$breadcrumb['active'] ?? false">
        {{ $breadcrumb['title'] }}
    </x-base.breadcrumb.link>
@endforeach
