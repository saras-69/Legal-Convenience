<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Booking $booking)
    {
        return $user->id === $booking->citizen_id && 
               $booking->status === 'completed' && 
               !$booking->review;
    }

    /**
     * Determine whether the user can respond to a review.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function respond(User $user, Review $review)
    {
        return $user->id === $review->lsp_id && !$review->lsp_response;
    }
}