<?php

namespace App\Http\Controllers;

use App\Models\AirconType;
use Illuminate\Http\Request;

class AirconTypeController extends Controller
{
    /**
     * Show all Aircon Types (with sorting options)
     */
    public function index(Request $request)
    {
        // Default sort: by name ascending
        $sortBy = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        // Validate sortable columns
        $validSorts = ['name', 'brand', 'base_price', 'capacity', 'category'];
        if (!in_array($sortBy, $validSorts)) {
            $sortBy = 'name';
        }

        $aircons = AirconType::where('status', 'active')
            ->orderBy($sortBy, $direction)
            ->get();

        return view('Booking.index', compact('aircons', 'sortBy', 'direction'));
    }
}
