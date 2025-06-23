<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Location;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LocationResource;

class LocationContoller extends Controller
{
    public function index()
    {
        return LocationResource::collection(Location::all());
    }

    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            'locationName' => 'required|string|unique:location'
        ]);

        $createdBy = auth('admin')->user();
        $staffDetails = $createdBy->staffId . ' / ' . $createdBy->firstName . ' ' . $createdBy->middleName . ' ' . $createdBy->lastName;

        Location::create([
            'locationName' => strtoupper($incomingFields['locationName']),
            'createdBy' => $staffDetails
        ]);

        if ($incomingFields){
           return response()->json([
                'success' => true,
                'message' => 'Location has been added successfully!'
           ], 200);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        return new LocationResource(Location::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $locationId = Location::findOrFail($id);
        $incomingFields = $request->validate([
            'locationName' => 'required|string|unique:location,locationName,'. $id . ',id'
        ]);

        $data = strtoupper($incomingFields['locationName']);
        $locationId->update(['locationName' => $data]);
        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully'
        ], 200); 
    }

    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return response()->json([
            'status' => true,
            'message' => 'Location deleted successfully'
        ], 200);
    }
}
