<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify($volunteerId)
    {

        $volunteer = Volunteer::where('volunteer_id', $volunteerId)->first();
        
        if(!$volunteer) {
            return response()->json([
                'message' => 'Certificate not found.'
            ], 404);
        }

        return response()->json([
            'name' => $volunteer->name,
            'department' => $volunteer->department,
            'batch' => $volunteer->batch,
            'volunteer_id' => $volunteer->volunteer_id,
            'rating' => $volunteer->rating,
            'issued_date' => $volunteer->created_at->format('d-m-Y'),
            'image' => $volunteer->image,
        ]);
    }
}
