<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PlanningController extends Controller
{
    /**
     * Show the weekly planning view.
     */
    public function index()
    {
        // Simulated mock planning data
        $planningData = [
            'Uteco' => [
                1 => 'cmd 3313/3314 VIBAC',
                3 => 'cmd 3252 Oze delice',
                4 => 'cmd VIBAC S37',
            ],
            'Conversion - Machine 1' => [
                0 => 'cmd 3213 Protek',
                1 => 'cmd 3312 Promoflex',
                2 => 'oze delice 1 roll',
            ],
            'Conversion - Machine 2' => [
                0 => 'cnesst',
                1 => 'cnesst',
                2 => 'cnesst',
                3 => 'cnesst',
            ],
            'Slitter' => [],
            'SIAT' => [],
        ];

        $machines = array_keys($planningData);
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday

        return view('production.planning', [
            'machines' => $machines,
            'planningData' => $planningData,
            'startOfWeek' => $startOfWeek
        ]);
    }
}
?>
