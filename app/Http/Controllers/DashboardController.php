<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cx;
use Carbon\CarbonPeriod;
use App\Charts\StatisticsChart;
use Balping\JsonRaw\Raw;
use App\Models\Lot;
use App\Models\Category;

class DashboardController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function __construct()
    {
        $this->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
    }


    public function getIndex()
    {
        $data = [];
        $data['page_title'] = '<strong>Dashboard</strong>';
        $data['categories'] = Category::where('status', 'Active')->get();
        
        if (request('debug')) {
            return response()->json($data['lots']);
        }
        
        return view('home', $data);
    }

    public function dailyStatisticChart()
    {
        $chart = new StatisticsChart;

        $chart->title('Pertumbuhan Pendapatan Hari Berjalan', 14, "#145984", 'bold', 'Roboto');

        $datas = [];
        $start = now()->format('Y-m-d 00:00:00');
        $end = now();
        $diff = $end->diffInHours($start);
        foreach (range(1, $diff) as $key => $time) {
            $start = sprintf("%02d", $key);
            $end = sprintf("%02d", $time);
            $hotel = mt_rand(10000000, 25000000);
            $restoran = mt_rand(5000000, 22000000);
            $parkir = mt_rand(5000000, 25000000);
            $hiburan = mt_rand(5000000, 25000000);
            $total = $hotel + $restoran + $parkir + $hiburan;
            $datas[] = [
                'whereStart' => now()->format("Y-m-d $start:00:00"),
                'whereEnd' => now()->format("Y-m-d $end:00:00"),
                'label' => now()->format("$end:00"),
                'hotel' => $hotel,
                'restoran' => $restoran,
                'parkir' => $parkir,
                'hiburan' => $hiburan,
                'total' => $total
            ];
        }

        $chart->labels(
            collect($datas)->pluck('label')
        );

        $chart
            ->dataset('Total', 'line', collect($datas)->pluck('total'))
            ->backgroundcolor("rgb(255, 99, 132)")
            ->fill(false)
            ->linetension(0)
            ->options([
                'borderWidth' => 5,
                'borderColor' => 'rgb(255, 99, 132)',
                'pointRadius' => 5,
                'pointHoverRadius' => 10,
                'pointStyle' => 'rect'
            ])
            ->dashed([5, 5]);

        $chart
            ->dataset('Hotel', 'bar', collect($datas)->pluck('hotel'))
            ->backgroundcolor("#0173b7")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ])
            ->color("#0173b7");

        $chart
            ->dataset('Restoran', 'bar', collect($datas)->pluck('restoran'))
            ->backgroundcolor("#dd4b39")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ])
            ->color("#dd4b39");

        $chart
            ->dataset('Parkir', 'bar', collect($datas)->pluck('parkir'))
            ->backgroundcolor("#01a65b")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ])
            ->color("#01a65b");

        $chart
           ->dataset('Hiburan', 'bar', collect($datas)->pluck('hiburan'))
           ->backgroundcolor("#f39c13")
           ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ])
            ->color("#f39c13");

        return $chart;
    }

    public function monthlyStatisticChart()
    {
        $chart = new StatisticsChart;

        $chart->title('Pertumbuhan Pendapatan Bulan Berjalan', 14, "#145984", 'bold', 'Roboto');

        $datas = [];
        $dates = CarbonPeriod::create(now()->startOfMonth(), now());
        foreach ($dates as $dt) {
            $hotel = mt_rand(100000000, 250000000);
            $restoran = mt_rand(50000000, 220000000);
            $parkir = mt_rand(50000000, 250000000);
            $hiburan = mt_rand(50000000, 250000000);
            $value = $hotel + $restoran + $parkir + $hiburan;
            $datas[] = [
                'date' => $dt->format('d/m/Y'),
                'value' => $value,
                'hotel' => $hotel,
                'restoran' => $restoran,
                'parkir' => $parkir,
                'hiburan' => $hiburan,
            ];
        }

        $chart->labels(
            collect($datas)->pluck('date')
        );

        $chart
            ->dataset('Total', 'line', collect($datas)->pluck('value'))
            ->backgroundcolor("rgb(255, 99, 132)")
            ->fill(false)
            ->linetension(0)
            ->options([
                'borderWidth' => 5,
                'borderColor' => 'rgb(255, 99, 132)',
                'pointRadius' => 5,
                'pointHoverRadius' => 10,
                'pointStyle' => 'rect'
            ])
            ->dashed([5, 5]);

        $chart
            ->dataset('Hotel', 'bar', collect($datas)->pluck('hotel'))
            ->backgroundcolor("#0173b7")
            ->color("#0173b7")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ]);

        $chart
            ->dataset('Restoran', 'bar', collect($datas)->pluck('restoran'))
            ->backgroundcolor("#dd4b39")
            ->color("#dd4b39")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ]);

        $chart
            ->dataset('Parkir', 'bar', collect($datas)->pluck('parkir'))
            ->backgroundcolor("#01a65b")
            ->color("#01a65b")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ]);

        $chart
           ->dataset('Hiburan', 'bar', collect($datas)->pluck('hiburan'))
           ->backgroundcolor("#f39c13")
            ->color("#f39c13")
            ->options([
                'barPercentage' => 0.50,
                'maxBarThickness' => 25,
            ]);

        return $chart;
    }

    public function yearlyStatisticChart()
    {
        $chart = new StatisticsChart;
        // $chart->options($this->chartOptions(), true);
        $chart->title('Monthly', 14, "#145984", 'bold', 'Roboto');
        $chart->barwidth(0.1);
        $chart->labels(['Jan', 'Feb', 'Mar']);
        $chart->dataset('Pertumbuhan Pendapatan Tahunan', 'bar', [30, 15, 44])
            ->color("#145984")
            ->backgroundcolor("#145984")
            ->fill(false)
            ->linetension(0);

        return $chart;
    }
}
