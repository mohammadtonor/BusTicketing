<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Terminal;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::with(['originTerminal', 'destinationTerminal'])->get();
        $terminals = Terminal::all(); // To populate the terminal dropdown
        return view('admin.routes.index', compact('routes', 'terminals'));
    }

    // Store a newly created route
    public function store(Request $request)
    {
        $validator = $request->validate([
            'origin_terminal_id' => 'required|exists:terminals,id',
            'destination_terminal_id' => 'required|exists:terminals,id|different:origin_terminal_id',
            'price' => 'required|numeric',
            'duration' => 'required',
        ]);

        Route::create($request->all());
        return redirect()->route('routes.index')->with('success', 'Route created successfully.');
    }

    // Update an existing route
    public function update(Request $request, $id)
    {
        $request->validate([
            'origin_terminal_id' => 'required|exists:terminals,id',
            'destination_terminal_id' => 'required|exists:terminals,id|different:origin_terminal_id',
            'price' => 'required|numeric',
            'duration' => 'required',
        ]);

        $route = Route::findOrFail($id);
        $route->update($request->all());
        return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
    }

    // Delete a route
    public function destroy($id)
    {
        $route = Route::findOrFail($id);
        $route->delete();
        return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    }
}
