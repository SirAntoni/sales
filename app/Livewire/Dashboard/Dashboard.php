<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Department;
use App\Models\District;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Provider;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Illuminate\Support\Facades\Cache;
use Carbon\CarbonPeriod;

class Dashboard extends Component
{

    public $month;
    public $year;
    public $provider;
    public $providers;
    public $category;
    public $categories;
    public $department;
    public $district;
    public $districts;
    public $departments;
    public $top10Products = [];
    public $margenGananciasProveedor = [];
    public $margenGananciasCategory = [];

    public function mount()
    {
        $providers = DB::table('providers')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->get();
        $departments = DB::table('departments')->select('id', 'name')->get();
        $this->providers = $providers;
        $this->categories = $categories;
        $this->departments = $departments;
        $this->month = Carbon::now()->format('m');
        $this->year = Carbon::now()->format('Y');

    }


    public function getCantidadVentas(): int{
        return Cache::remember('ventas_hoy_count', 60, function () {
            return Sale::whereDate('created_at', Carbon::today())->count();
        });

    }

    public function getTotalVentasHoy(): float
    {
        return Cache::remember('total_ventas_hoy', 60, function () {
            return (float) Sale::whereDate('created_at', Carbon::today())
                ->sum('total');
        });
    }

    public function updatingDepartment($department)
    {
        $departments = Department::with('districts')->find($department);
        $this->districts = $departments->districts;
        $this->department = $department;
        $top10Products = $this->top10Products();
        $this->dispatch('dashboard-report', $top10Products);
    }

    public function updatedCategory($category)
    {
        $this->category = $category;
        $top10Products = $this->top10Products();
        $this->dispatch('dashboard-report', $top10Products);
    }

    public function updatedMonth($month)
    {
        $this->month = $month;
        $top10Products = $this->top10Products();
        $this->dispatch('dashboard-report', $top10Products);
    }

    public function updatedYear($year)
    {
        $this->year = $year;
        $top10Products = $this->top10Products();
        $this->dispatch('dashboard-report', $top10Products);
    }

    private function getSalesChartData(): array
    {
        $end   = Carbon::now();
        $start = $end->copy()->subDays(6)->startOfDay();

        // 1) Obtenemos por día: suma de total y cantidad de ventas
        $raw = Sale::whereBetween('date', [$start, $end])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy(DB::raw('DATE(date)'), 'ASC')
            ->get([
                DB::raw('DATE(date)       AS sale_date'),
                DB::raw('SUM(total)        AS total_sum'),
                DB::raw('COUNT(*)          AS total_count'),
            ]);

        // Mapear por fecha
        $totals = $raw->pluck('total_sum', 'sale_date')->toArray();
        $counts = $raw->pluck('total_count', 'sale_date')->toArray();

        // 2) Generar período de 7 días
        $period = CarbonPeriod::create($start, $end);
        $labels = [];
        $dataTotals = [];
        $dataCounts = [];

        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $labels[]       = $day;
            $dataTotals[]   = isset($totals[$day]) ? (float) $totals[$day] : 0.00;
            $dataCounts[]   = isset($counts[$day]) ? (int) $counts[$day] : 0;
        }

        return [
            'labels'      => $labels,
            'totals'      => $dataTotals,
            'counts'      => $dataCounts,
        ];
    }

    public function top10Products()
    {

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $articles = DB::table('articles')
            ->join('sale_details', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                'articles.id',
                'articles.title',
                // Suma la cantidad vendida
                DB::raw('SUM(sale_details.quantity) as total_qty'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro por provider (suponiendo que es en articles, columna provider_id)
            ->when($provider, function ($query) use ($provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro por category en sale_details
            ->when($category, function ($query) use ($category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro por department en clients
            ->when($department, function ($query) use ($department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro por district en clients
            ->when($district, function ($query) use ($district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('articles.id', 'articles.title', 'articles.purchase_price')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $this->top10Products = $articles;
    }

    public function margenGananciaProveedor()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $ganancias = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('providers', 'articles.provider_id', '=', 'providers.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                'articles.provider_id as provider_id',
                'providers.name as provider_name',
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw("SUM(sale_details.price - (articles.purchase_price * $exchange)) as total_ganancia"),
                // Calcula el margen promedio en porcentaje
                DB::raw("AVG((sale_details.price - (articles.purchase_price * $exchange)) / sale_details.price * 100) as margen_promedio")
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('articles.provider_id')
            ->get();

        return $ganancias;
    }

    public function margenGananciaContacto()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $contacts = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('contacts', 'sales.contact_id', '=', 'contacts.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                'contacts.name as contact_name',
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw('SUM(sale_details.quantity) as total_qty'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('sales.contact_id')
            ->get();

        return $contacts;
    }

    public function margenGananciasCategory()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $contacts = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                'categories.name as category_name',
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw('SUM(sale_details.quantity) as total_qty'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('sale_details.category_id','categories.name')
            ->get();

        return $contacts;
    }

    public function margenGananciasDepartment()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $departments = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->join('departments', 'clients.department_id', '=', 'departments.id')
            ->select(
                'departments.name as department_name',
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw('SUM(sale_details.quantity) as total_qty'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('clients.department_id')
            ->having('total_qty', '>', 0)
            ->get();

        return $departments;
    }

    public function margenGananciasDistrict()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $districts = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->join('districts', 'clients.district_id', '=', 'districts.id')
            ->select(
                'districts.name as district_name',
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw('SUM(sale_details.quantity) as total_qty'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->groupBy('clients.district_id')
            ->having('total_qty', '>', 0)
            ->get();

        return $districts;
    }


    public function gananciaVentasTotal()
    {
        $exchange = 3.90;

        $month = $this->month;
        $year = $this->year;
        $provider = $this->provider;
        $category = $this->category;
        $department = $this->department;
        $district = $this->district;


        $total = DB::table('sale_details')
            // Unir la tabla articles para obtener el provider_id y, si fuese necesario, otros datos
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                // Calcula la ganancia total aplicando la fórmula y el tipo de cambio
                DB::raw('SUM(sale_details.price - (articles.purchase_price * 3.90)) as total_ganancias'),
                // Realiza la operación por cada registro de sale_details
                DB::raw('SUM(sale_details.price) as total_ventas')
            )
            ->where('sales.status',"!=",0)
            ->whereYear('sale_details.created_at', $year)
            ->whereMonth('sale_details.created_at', $month)
            // Filtro opcional por proveedor (provider_id de la tabla articles)
            ->when($provider, function ($query, $provider) {
                return $query->where('articles.provider_id', $provider);
            })
            // Filtro opcional por categoría en sale_details
            ->when($category, function ($query, $category) {
                return $query->where('sale_details.category_id', $category);
            })
            // Filtro opcional por departamento en clients
            ->when($department, function ($query, $department) {
                return $query->where('clients.department_id', $department);
            })
            // Filtro opcional por distrito en clients
            ->when($district, function ($query, $district) {
                return $query->where('clients.district_id', $district);
            })
            ->get();

        return $total;
    }

    public function render()
    {

        $this->top10Products();
        $gananciasProveedores = $this->margenGananciaProveedor();
        $gananciasContacto = $this->margenGananciaContacto();
        $gananciasCategory = $this->margenGananciasCategory();
        $gananciasDepartment = $this->margenGananciasDepartment();
        $gananciasDistrict = $this->margenGananciasDistrict();
        $gananciasVentasTotal = $this->gananciaVentasTotal();
        $getSalesChartData = $this->getSalesChartData();
        $this->dispatch('dashboard-report', [$gananciasProveedores,$gananciasContacto,$gananciasCategory,$gananciasDepartment,$gananciasDistrict,$gananciasVentasTotal,$getSalesChartData]);



        return view('livewire.dashboard.dashboard');
    }
}
