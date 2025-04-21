<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LspProfile;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalLsps = User::where('role', 'lsp')->count();
        $totalCitizens = User::where('role', 'citizen')->count();
        $pendingVerifications = LspProfile::where('verification_status', 'pending')->count();
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalServices = Service::count();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalLsps', 
            'totalCitizens', 
            'pendingVerifications', 
            'totalBookings', 
            'completedBookings', 
            'totalServices'
        ));
    }

    public function pendingVerifications()
    {
        $profiles = LspProfile::where('verification_status', 'pending')
            ->with('user')
            ->get();
            
        return view('admin.verifications', compact('profiles'));
    }

    public function verifyLsp(Request $request, LspProfile $profile)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->action === 'approve') {
            $profile->verification_status = 'verified';
            $profile->save();
            
            // Send approval notification to LSP
            
            return redirect()->back()->with('success', 'LSP profile approved successfully!');
        } else {
            $profile->verification_status = 'rejected';
            $profile->rejection_reason = $request->rejection_reason;
            $profile->save();
            
            // Send rejection notification to LSP
            
            return redirect()->back()->with('success', 'LSP profile rejected successfully!');
        }
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function services()
    {
        $services = Service::with('lsp')->get();
        return view('admin.services', compact('services'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['citizen', 'lsp', 'service'])->get();
        return view('admin.bookings', compact('bookings'));
    }
}