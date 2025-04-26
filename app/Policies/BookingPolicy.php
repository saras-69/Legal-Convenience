<?php
// filepath: c:\Users\saket\Documents\int221\legal-convenience\app\Policies\BookingPolicy.php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine if the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Allow if user is the citizen who created the booking
        if ($user->id === $booking->citizen_id) {
            return true;
        }
        
        // Allow if user is the LSP providing the service
        if ($user->id === $booking->lsp_id) {
            return true;
        }
        
        // Allow if user is an admin
        if ($user->role === 'admin') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if the user can update the booking.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Only LSP or admin can update bookings
        return $user->id === $booking->lsp_id || $user->role === 'admin';
    }
    
    /**
     * Determine if the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Both citizen and LSP can cancel bookings in pending status
        if ($booking->status !== 'pending') {
            return false;
        }
        
        return $user->id === $booking->citizen_id || 
               $user->id === $booking->lsp_id || 
               $user->role === 'admin';
    }
}