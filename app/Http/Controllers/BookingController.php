<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestItem;
use Illuminate\Support\Facades\DB;
use App\Models\AirconType;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        $validSorts = ['name', 'brand', 'base_price', 'capacity', 'category'];
        if (!in_array($sortBy, $validSorts)) $sortBy = 'name';

        $aircons = AirconType::where('status', 'active')->orderBy($sortBy, $direction)->get();

        return view('Booking.index', compact('aircons', 'sortBy', 'direction'));
    }

    /**
     * Store a new quote/service request
     */
    public function storeRequest(Request $request)
{
    DB::transaction(function() use ($request) {

        // 1️⃣ Save Customer Informationn
        $customer = Customer::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'business_name' => $request->input('business_name'),
            'contact_info' => $request->input('contact_info'),
        ]);

        // 2️⃣ Save Addresses (loop through multiple addresses)
        $labels = $request->input('add_address_label', []);
        foreach ($labels as $index => $label) {
            $defaults = $request->input('add_address_default', []);
            CustomerAddress::create([
                'customer_id' => $customer->customer_id,
                'label' => $label,
                'street_address' => $request->input('add_address_street')[$index] ?? null,
                'barangay' => $request->input('add_address_barangay')[$index] ?? null,
                'city' => $request->input('add_address_city')[$index] ?? null,
                'province' => $request->input('add_address_province')[$index] ?? null,
                'zip_code' => $request->input('add_address_zip')[$index] ?? null,
                'is_default' => in_array($index, $defaults ?? []),
            ]);
        }

        // 3️⃣ Create a Service Request Header
       $serviceRequest = ServiceRequest::create([
    'customer_id' => $customer->customer_id,

    'order_status' => 'Requested',
    
]);

        // 4️⃣ Save Service Request Item (Service Information)
        $unitPrice = $request->input('service_product_price', 0);
        $quantity = $request->input('unit_quantity', 1);

        ServiceRequestItem::create([
            'service_request_id' => $serviceRequest->service_request_id,
            'service_type' => $request->input('service_type'),
            'aircon_type_id' => $request->input('product_id'),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $unitPrice * $quantity,
            'status' => 'Requested',
        ]); 
    });

    return redirect()->back()->with('success', 'Quote request submitted successfully!');
}

}
