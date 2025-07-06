<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LocationResource;
use App\Http\Resources\Admin\SlotResource;
use App\Models\Admin\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        $location = Slot::with(['locations:id,locationName', 'status:id,statusName'])->paginate(50);
        if ($location->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No record found!'
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'Slot fetched successfully',
            'data' => SlotResource::collection($location->items()),
            'pagination' => [
                'total' => $location->total(),
                'perPage' => $location->perPage(),
                'currentPage' => $location->currentPage(),
                'lastPage' => $location->lastPage(),
                'nextPageUrl' => $location->nextPageUrl(),
                'prevPageUrl' => $location->previousPageUrl(),
                'from' => $location->firstItem(),
                'to' => $location->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $createSlot = $request->validate([
            'locationId' => 'required|integer|exists:location,id',
            'slotName' => 'required|string',
            'statusId' => 'required|integer|in:1,2'
        ]);

        $createdBy = auth('admin')->user();
        $staffDetails = $createdBy->staffId . ' / ' . $createdBy->firstName . ' ' . $createdBy->middleName . ' ' . $createdBy->lastName;
        
        $slot = Slot::create([
            'locationId' => $createSlot['locationId'],
            'slotName' => strtoupper($createSlot['slotName']),
            'statusId' => $createSlot['statusId'],
            'createdBy' => $staffDetails
        ]);

        if ($slot){
            return response()->json([
                'success' => true,
                'message' => 'Slot registration successful.',
            ]);
        }
    }

    public function show(string $id)
    {
        return new SlotResource(Slot::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $slotId = Slot::findOrFail($id);
        $incomingFields = $request->validate([
            'locationId' => 'required|integer|exists:location,id',
            'slotName' => 'required|string'
        ]);

        $incomingFields['slotName'] = strtoupper($incomingFields['slotName']);
        $slotId->update([
            'slotName' => $incomingFields['slotName'],
            'locationId' => $incomingFields['locationId'],
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Slot updated successfully'
        ], 200); 
    }

    public function destroy(string $id)
    {
        $slotId = Slot::findOrFail($id);
        $slotId->delete();
        return response()->json([
            'status' => true,
            'message' => 'Slot deleted successfully'
        ], 200); 
    }
}
