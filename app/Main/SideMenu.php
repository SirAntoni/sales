<?php

namespace App\Main;

class SideMenu
{
    /**
     * List of side menu items.
     */
    public static function menu(): array
    {
        return [
            "NAVEGACIÓN",
            [
                'icon' => "Home",
                'route_name' => "dashboard",
                'params' => [],
                'title' => "Dashboard",
                'permission' => 'dashboard',
            ],
            [
                'icon' => "Package",
                'route_name' => "",
                'params' => [],
                'title' => "Almacen",
                'permission' => 'store',
                'sub_menu' => [
                    [
                        'icon' => "ShoppingBag",
                        'route_name' => "articles.index",
                        'params' => [],
                        'title' => "Articulos",
                        'permission' => 'articles',
                    ],
                    [
                        'icon' => "Grid",
                        'route_name' => "categories.index",
                        'params' => [],
                        'title' => "Categorias",
                        'permission' => 'categories',
                    ],
                    [
                        'icon' => "Tag",
                        'route_name' => "brands.index",
                        'params' => [],
                        'title' => "Marcas",
                        'permission' => 'brands',
                    ],
                ],
            ],
            [
                'icon' => "ShoppingCart",
                'route_name' => "",
                'params' => [],
                'title' => "Compras",
                'permission' => 'purchases',
                'sub_menu' => [
                    [
                        'icon' => "Activity",
                        'route_name' => "purchases.index",
                        'params' => [],
                        'title' => "Compras",
                        'permission' => 'purchases.index',
                    ],
                    [
                        'icon' => "Users",
                        'route_name' => "providers.index",
                        'params' => [],
                        'title' => "Proveedores",
                        'permission' => 'providers',
                    ],
                    [
                        'icon' => "FileMinus",
                        'route_name' => "canceled_purchases",
                        'params' => [],
                        'title' => "Anulados",
                        'permission' => 'canceled_purchases',
                    ],
                ],
            ],
            [
                'icon' => "DollarSign",
                'route_name' => "",
                'params' => [],
                'title' => "Ventas",
                'permission' => 'sales',
                'sub_menu' => [
                    [
                        'icon' => "Activity",
                        'route_name' => "sales.index",
                        'params' => [],
                        'title' => "Ventas",
                        'permission' => 'sales.index',
                    ],
                    [
                        'icon' => "Users",
                        'route_name' => "clients.index",
                        'params' => [],
                        'title' => "Clientes",
                        'permission' => 'clients',
                    ],
                    [
                        'icon' => "FileMinus",
                        'route_name' => "canceled",
                        'params' => [],
                        'title' => "Anulados",
                        'permission' => 'canceled',
                    ],
                ],
            ],
            [
                'icon' => "Folder",
                'route_name' => "documents.index",
                'params' => [],
                'title' => "Documentos",
                'permission' => 'documents',
            ],
            [
                'icon' => "AlignJustify",
                'route_name' => "kardex",
                'params' => [],
                'title' => "Kardex",
                'permission' => 'kardex',
            ],
            [
                'icon' => "PieChart",
                'route_name' => "reports",
                'params' => [],
                'title' => "Reportes",
                'permission' => 'reports',
            ],
            [
                'icon' => "Users",
                'route_name' => "users.index",
                'params' => [],
                'title' => "Usuarios",
                'permission' => 'users',
            ],
            [
                'icon' => "Settings",
                'route_name' => "",
                'params' => [],
                'title' => "Configuración",
                'permission' => 'settings',
                'sub_menu' => [
                    [
                        'icon' => "Layout",
                        'route_name' => "settings",
                        'params' => [],
                        'title' => "General",
                        'permission' => 'settings',
                    ],
                    [
                        'icon' => "FilePlus",
                        'route_name' => "vouchers.index",
                        'params' => [],
                        'title' => "Comprobantes",
                        'permission' => 'settings',
                    ],
                    [
                        'icon' => "Book",
                        'route_name' => "contacts.index",
                        'params' => [],
                        'title' => "Contactos",
                        'permission' => 'settings',
                    ],
                    [
                        'icon' => "CreditCard",
                        'route_name' => "payment-methods.index",
                        'params' => [],
                        'title' => "Métodos de pago",
                        'permission' => 'settings',
                    ],
                ],
            ],
        ];
    }
}
