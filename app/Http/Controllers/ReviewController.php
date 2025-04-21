<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $this->authorize('create', [Review::class, $booking]);
        
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'citizen_id' => Auth::id(),
            'lsp_id' => $booking->lsp_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Review submitted successfully!');
    }

    public function respond(Request $request, Review $review)
    {
        $this->authorize('respond', $review);
        
        $validator = Validator::make($request->all(), [
            'lsp_response' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $review->update([
            'lsp_response' => $request->lsp_response,
        ]);

        return redirect()->route('bookings.show', $review->booking)->with('success', 'Response submitted successfully!');
    }
}