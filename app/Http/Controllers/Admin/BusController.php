<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    // Show all buses
    public function index()
    {
        $buses = Bus::all();
        return view('admin.buses.index', compact('buses'));
    }


    // Store new bus with validation
    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'bus_number' => 'required|string|unique:buses|max:255',
            'bus_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
        ]);

        // Create new bus
        Bus::create($validatedData);

        return redirect()->route('buses.index')->with('success', 'Bus created successfully!');
    }

    // Show a specific bus
    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    // Update a bus with validation
    public function update(Request $request, Bus $bus)
    {
        // Validate input data
        $validatedData = $request->validate([
            'bus_number' => 'required|string|max:255|unique:buses,bus_number,' . $bus->id,
            'bus_name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
        ]);

        // Update bus details
        $bus->update($validatedData);

        return redirect()->route('buses.index')->with('success', 'Bus updated successfully!');
    }

    // Delete a bus
    public function destroy(Bus $bus)
    {
        $bus->delete();

        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully!');
    }
}
