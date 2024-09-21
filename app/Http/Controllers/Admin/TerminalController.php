<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
     // Display a listing of the terminals
     public function index()
     {
         // Retrieve all terminals and load related city data
         $cities = City::all();
         $terminals = Terminal::with('city')->get();
         return view('admin.terminals.index', compact('terminals', 'cities'));
     }
 
     // Store a newly created terminal in the database
     public function store(Request $request)
     {
         // Validate the request data
         $request->validate([
             'name' => 'required|string|max:255',
             'city_id' => 'required|exists:cities,id',
         ]);
 
         // Create a new terminal record
         Terminal::create([
             'name' => $request->name,
             'city_id' => $request->city_id,
         ]);
 
         // Redirect to the index page with a success message
         return redirect()->route('terminals.index')->with('success', 'Terminal created successfully.');
     }
 
     // Update the specified terminal in the database
     public function update(Request $request, Terminal $terminal)
     {
         // Validate the request data
         $request->validate([
             'name' => 'required|string|max:255',
             'city_id' => 'required|exists:cities,id',
         ]);
 
         // Update the terminal's data
         $terminal->update([
             'name' => $request->name,
             'city_id' => $request->city_id,
         ]);
 
         // Redirect to the index page with a success message
         return redirect()->route('terminals.index')->with('success', 'Terminal updated successfully.');
     }
 
     // Delete the specified terminal from the database
     public function destroy(Terminal $terminal)
     {
         // Delete the terminal record
         $terminal->delete();
 
         // Redirect to the index page with a success message
         return redirect()->route('terminals.index')->with('success', 'Terminal deleted successfully.');
     }
}
