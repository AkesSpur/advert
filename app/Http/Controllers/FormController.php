<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;
use App\Models\MetroStation;
use App\Models\Service;
use App\Models\PaidService;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        // Fetch data from the database
        $neighborhoods = Neighborhood::all();
        $metroStations = MetroStation::all();
        $services = Service::all();
        $paidServices = PaidService::all();

        // Pass data to the view
        return view('form.index', compact('neighborhoods', 'metroStations', 'services', 'paidServices'));
    }
}