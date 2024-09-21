<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // Show all cities (index view)
    public function index()
    {
        $cities = City::all();
        return view('admin.city.index', compact('cities'));
    }


    // Store a new city
    public function store(Request $request)
    {
        // Validate the form input
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255',

        ]);

        // Create and save the new city
        City::create($request->all());

        // Redirect to cities index with success message
        return redirect()->route('cities.index')->with('success', 'City created successfully.');
    }

    // Update the city in the database
    public function update(Request $request, $id)
    {
        // Find the city to update
        $city = City::findOrFail($id);

        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        // Update the city's data
        $city->update($request->all());

        // Redirect to cities index with success message
        return redirect()->route('cities.index')->with('success', 'City updated successfully.');
    }

    // Delete a city
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        // Redirect back to cities index with success message
        return redirect()->route('cities.index')->with('success', 'City deleted successfully.');
    }
}


