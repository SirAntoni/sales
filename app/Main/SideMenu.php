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
                    ],
                    [
                        'icon' => "Grid",
                        'route_name' => "categories.index",
                        'params' => [],
                        'title' => "Categorias",
                    ],
                    [
                        'icon' => "Tag",
                        'route_name' => "brands.index",
                        'params' => [],
                        'title' => "Marcas",
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
                    ],
                    [
                        'icon' => "Users",
                        'route_name' => "providers.index",
                        'params' => [],
                        'title' => "Proveedores",
                    ]
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
                    ],
                    [
                        'icon' => "Users",
                        'route_name' => "clients.index",
                        'params' => [],
                        'title' => "Clientes",
                    ],
                    [
                        'icon' => "FileMinus",
                        'route_name' => "canceled",
                        'params' => [],
                        'title' => "Anulados",
                    ],
                ],
            ],
            [
                'icon' => "AlignJustify",
                'route_name' => "documents.index",
                'params' => [],
                'title' => "Documentos",
                'permission' => 'kardex',
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
                    ],
                    [
                        'icon' => "FilePlus",
                        'route_name' => "vouchers.index",
                        'params' => [],
                        'title' => "Comprobantes",
                    ],
                    [
                        'icon' => "Book",
                        'route_name' => "contacts.index",
                        'params' => [],
                        'title' => "Contactos",
                    ],
                    [
                        'icon' => "CreditCard",
                        'route_name' => "payment-methods.index",
                        'params' => [],
                        'title' => "Métodos de pago",
                    ],
                ],
            ],
        ];
    }
}
