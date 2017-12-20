<?php

namespace App\Http\Controllers;

use App\Point;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index()
    {
        $points = Point::ofDay(Carbon::today())->get();

        return view('simulation.index', compact('points'));
    }
}
