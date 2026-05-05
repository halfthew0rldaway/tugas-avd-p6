<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::all();
        
        // Prepare data for the charts
        
        // a. Sales Trend (Line Chart): sales by date
        $trendDates = $sales->pluck('tanggal')->toArray();
        $trendSales = $sales->pluck('penjualan')->toArray();
        
        // b. Event Impact (Bar Chart): avg sales with event vs without
        $salesWithEvent = $sales->where('kegiatan', 1)->avg('penjualan') ?? 0;
        $salesWithoutEvent = $sales->where('kegiatan', 0)->avg('penjualan') ?? 0;
        
        // c. Rain Impact (Bar Chart): avg sales high rain vs low rain
        // Let's define high rain as > median or average, or > a specific threshold (e.g., > 10mm). Let's use > 5 for simplicity.
        $avgRain = $sales->avg('curah_hujan');
        $salesHighRain = $sales->where('curah_hujan', '>=', $avgRain)->avg('penjualan') ?? 0;
        $salesLowRain = $sales->where('curah_hujan', '<', $avgRain)->avg('penjualan') ?? 0;
        
        return view('sales.index', compact(
            'sales',
            'trendDates',
            'trendSales',
            'salesWithEvent',
            'salesWithoutEvent',
            'salesHighRain',
            'salesLowRain'
        ));
    }

    public function export()
    {
        $sales = Sale::all();
        $fileName = 'sales_export_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Day', 'Date', 'Event Activity', 'Rainfall (mm)', 'Sales (pcs)'];

        $callback = function() use($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->hari,
                    $sale->tanggal,
                    $sale->kegiatan ? 'Active' : 'Inactive',
                    $sale->curah_hujan,
                    $sale->penjualan,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
