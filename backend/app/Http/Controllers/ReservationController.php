<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Create a new reservation (authenticated user).
     * POST /api/reservations
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:20',
            'date'             => 'required|date|after_or_equal:today',
            'time'             => 'required',
            'guests'           => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $reservation = Reservation::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'status'  => 'pending',
        ]);

        return response()->json([
            'message'     => 'Reservation created successfully.',
            'reservation' => $reservation,
        ], 201);
    }
}
