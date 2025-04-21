<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Service;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'lsp') {
            // Get recent bookings for LSP dashboard
            $recentBookings = Booking::where('lsp_id', $user->id)
                ->with(['service', 'citizen'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            // Get recent reviews for LSP dashboard
            $recentReviews = Review::where('lsp_id', $user->id)
                ->with(['booking.service', 'citizen'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            return view('lsp.dashboard', compact('recentBookings', 'recentReviews'));
        } else {
            // For citizens, show recommended services - using MongoDB's $sample aggregation instead of inRandomOrder()
            $recommendedServices = Service::raw(function($collection) {
                return $collection->aggregate([
                    ['$match' => ['is_active' => true]],
                    ['$sample' => ['size' => 6]]
                ]);
            });
            
            // Load relationships for the recommended services
            if ($recommendedServices->count() > 0) {
                $serviceIds = $recommendedServices->pluck('_id')->toArray();
                $recommendedServices = Service::whereIn('_id', $serviceIds)
                    ->with('lsp.lspProfile')
                    ->get();
            }
                
            // Get recent bookings for citizen dashboard
            $recentBookings = Booking::where('citizen_id', $user->id)
                ->with(['service', 'lsp'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            return view('citizen.dashboard', compact('recommendedServices', 'recentBookings'));
        }
    }
}