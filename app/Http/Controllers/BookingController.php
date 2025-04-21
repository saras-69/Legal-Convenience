<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function create(Service $service)
    {
        return view('bookings.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $booking = Booking::create([
            'citizen_id' => $user->id,
            'lsp_id' => $service->lsp_id,
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'status' => 'pending',
            'payment_status' => 'pending',
            'amount' => $service->price,
            'notes' => $request->notes,
        ]);

        // For demo purposes, we'll mark the payment as paid
        // In a real application, you would integrate with a payment gateway
        $booking->payment_status = 'paid';
        $booking->transaction_id = 'DEMO_' . uniqid();
        $booking->save();

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load('service', 'lsp', 'citizen');
        
        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'citizen') {
            $bookings = Booking::where('citizen_id', $user->id)
                ->with('service', 'lsp')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('bookings.citizen-index', compact('bookings'));
        } elseif ($user->role === 'lsp') {
            $bookings = Booking::where('lsp_id', $user->id)
                ->with('service', 'citizen')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('bookings.lsp-index', compact('bookings'));
        }
        
        return redirect()->route('home')->with('error', 'Unauthorized access');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:confirmed,completed,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $booking->status = $request->status;
        
        if ($request->status === 'cancelled') {
            $booking->cancellation_reason = $request->cancellation_reason;
        }
        
        $booking->save();

        // Award points if the booking is completed
        if ($request->status === 'completed') {
            $this->awardPoints($booking);
        }

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    private function awardPoints(Booking $booking)
    {
        // Award points to LSP
        $lspPoints = floor($booking->amount * 0.1); // 10% of booking amount as points
        $booking->lsp->reward_points += $lspPoints;
        $booking->lsp->save();
        
        Reward::create([
            'user_id' => $booking->lsp_id,
            'points' => $lspPoints,
            'type' => 'earned',
            'source' => 'booking_completed',
            'reference_id' => $booking->id,
            'description' => 'Points earned for completing booking #' . $booking->id,
        ]);

        // Award points to Citizen
        $citizenPoints = floor($booking->amount * 0.05); // 5% of booking amount as points
        $booking->citizen->reward_points += $citizenPoints;
        $booking->citizen->save();
        
        Reward::create([
            'user_id' => $booking->citizen_id,
            'points' => $citizenPoints,
            'type' => 'earned',
            'source' => 'booking_completed',
            'reference_id' => $booking->id,
            'description' => 'Points earned for booking #' . $booking->id,
        ]);
    }
}