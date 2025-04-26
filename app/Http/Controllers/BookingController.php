<?php
// filepath: c:\Users\saket\Documents\int221\legal-convenience\app\Http\Controllers\BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Service $service)
    {
        return view('bookings.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Calculate the total amount
        $amount = $service->price * 1.05; // Adding 5% platform fee
        
        // Create a booking record with pending payment status
        $booking = new Booking();
        $booking->citizen_id = Auth::id();
        $booking->lsp_id = $service->lsp_id;
        $booking->service_id = $service->id;
        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->status = 'pending';
        $booking->payment_status = 'pending';
        $booking->amount = $amount;
        $booking->notes = $request->notes;
        $booking->save();
        
        // Initialize Razorpay API
        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
        
        // Create Razorpay Order
        $orderData = [
            'receipt' => 'booking_' . $booking->id,
            'amount' => (int)($amount * 100), // Amount in paisa as integer
            'currency' => config('razorpay.currency'),
            'payment_capture' => 1 // Auto capture
        ];
        
        try {
            $razorpayOrder = $api->order->create($orderData);
            
            // Return checkout view with Razorpay data
            return view('bookings.checkout', [
                'booking' => $booking,
                'razorpayOrder' => $razorpayOrder,
                'razorpayId' => config('razorpay.key_id'),
                'amount' => $amount,
                'service' => $service,
                'user' => Auth::user(),
            ]);
        } catch (\Exception $e) {
            $booking->delete(); // Remove the booking if order creation failed
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
    
    public function complete(Request $request, Booking $booking)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);
        
        // Verify the payment signature
        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
        
        try {
            $attributes = [
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Update the booking
            $booking->payment_status = 'paid';
            $booking->transaction_id = $request->razorpay_payment_id;
            $booking->save();
            
            return redirect()->route('bookings.show', $booking)->with('success', 'Payment successful and booking confirmed!');
        } catch (\Exception $e) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }
 
    public function show(Booking $booking)
    {
        if (Auth::user()->cannot('view', $booking)) {
            abort(403, 'Unauthorized action.');
        }
        
        $booking->load('service', 'lsp', 'citizen');
        
        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'citizen') {
            $bookings = Booking::where('citizen_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->with(['service', 'lsp'])
                ->get();
        } elseif ($user->role === 'lsp') {
            $bookings = Booking::where('lsp_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->with(['service', 'citizen'])
                ->get();
        } elseif ($user->role === 'admin') {
            $bookings = Booking::orderBy('created_at', 'desc')
                ->with(['service', 'lsp', 'citizen'])
                ->get();
        } else {
            abort(403, 'Unauthorized action.');
        }
        
        return view('bookings.index', compact('bookings'));
    }
    
    public function cancel(Request $request, Booking $booking)
    {
        if (Auth::user()->cannot('cancel', $booking)) {
            abort(403, 'Unauthorized action.');
        }
        
        $booking->status = 'cancelled';
        
        if ($request->has('cancellation_reason')) {
            $booking->cancellation_reason = $request->cancellation_reason;
        }
        
        if ($booking->payment_status === 'paid') {
            // In a real application, you would integrate with Razorpay's refund API here
            // $booking->refund_status = 'pending';
        }
        
        $booking->save();
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking has been cancelled.');
    }

    public function update(Request $request, Booking $booking)
    {
        if (Auth::user()->cannot('update', $booking)) {
            abort(403, 'Unauthorized action.');
        }
        
        $booking->status = $request->status;
        $booking->save();
        
        // Award points if the booking is completed
        if ($request->status === 'completed') {
            $this->awardPoints($booking);
        }
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking status has been updated.');
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