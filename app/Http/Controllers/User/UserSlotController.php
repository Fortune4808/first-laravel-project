<?php

namespace App\Http\Controllers\User;

use App\Models\Admin\Slot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SlotResource;

class UserSlotController extends Controller
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

}
