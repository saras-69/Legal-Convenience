@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Booking Details</h4>
                <span class="badge 
                    @if($booking->status === 'pending') bg-warning
                    @elseif($booking->status === 'confirmed') bg-info
                    @elseif($booking->status === 'completed') bg-success
                    @elseif($booking->status === 'cancelled') bg-danger
                    @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Booking Information</h5>
                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                        <p><strong>Date:</strong> {{ date('F j, Y', strtotime($booking->booking_date)) }}</p>
                        <p><strong>Time:</strong> {{ date('g:i A', strtotime($booking->booking_time)) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge 
                                @if($booking->status === 'pending') bg-warning
                                @elseif($booking->status === 'confirmed') bg-info
                                @elseif($booking->status === 'completed') bg-success
                                @elseif($booking->status === 'cancelled') bg-danger
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </p>
                        <p><strong>Payment Status:</strong> 
                            <span class="badge 
                                @if($booking->payment_status === 'pending') bg-warning
                                @elseif($booking->payment_status === 'paid') bg-success
                                @elseif($booking->payment_status === 'refunded') bg-info
                                @endif">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </p>
                        @if($booking->transaction_id)
                            <p><strong>Transaction ID:</strong> {{ $booking->transaction_id }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5>Service Details</h5>
                        <p><strong>Service:</strong> {{ $booking->service->title }}</p>
                        <p><strong>Category:</strong> {{ $booking->service->category }}</p>
                        <p><strong>Duration:</strong> {{ $booking->service->duration_minutes }} minutes</p>
                        <p><strong>Amount:</strong> ₹{{ number_format($booking->amount, 2) }}</p>
                        @if(Auth::user()->role === 'citizen')
                            <p><strong>Provider:</strong> {{ $booking->lsp->name }}</p>
                        @elseif(Auth::user()->role === 'lsp')
                            <p><strong>Client:</strong> {{ $booking->citizen->name }}</p>
                        @endif
                    </div>
                </div>
                
                @if($booking->notes)
                    <div class="mb-4">
                        <h5>Additional Notes</h5>
                        <p>{{ $booking->notes }}</p>
                    </div>
                @endif
                
                @if($booking->cancellation_reason)
                    <div class="alert alert-danger mb-4">
                        <h5>Cancellation Reason</h5>
                        <p>{{ $booking->cancellation_reason }}</p>
                    </div>
                @endif
                
                <div class="d-flex justify-content-between">
                    @if(Auth::user()->role === 'lsp' && $booking->status === 'pending')
                        <form action="{{ route('lsp.bookings.status', $booking) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success">Accept Booking</button>
                        </form>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            Decline Booking
                        </button>
                    @elseif(Auth::user()->role === 'lsp' && $booking->status === 'confirmed')
                        <form action="{{ route('lsp.bookings.status', $booking) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success">Mark as Completed</button>
                        </form>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            Cancel Booking
                        </button>
                    @elseif(Auth::user()->role === 'citizen' && ($booking->status === 'pending' || $booking->status === 'confirmed'))
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            Cancel Booking
                        </button>
                    @endif
                    
                    @if(Auth::user()->role === 'citizen' && $booking->status === 'completed' && !$booking->review)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            Leave a Review
                        </button>
                    @endif
                    
                    <a href="{{ Auth::user()->role === 'citizen' ? route('citizen.bookings') : route('lsp.bookings') }}" class="btn btn-outline-secondary">
                        Back to Bookings
                    </a>
                </div>
            </div>
        </div>
        
        @if($booking->review)
            <div class="card shadow mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Your Review</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $booking->review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">{{ $booking->review->created_at->format('M d, Y') }}</small>
                        </div>
                        <p>{{ $booking->review->comment }}</p>
                    </div>
                    
                    @if($booking->review->lsp_response)
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted">Response from {{ $booking->lsp->name }}:</small>
                                <p class="mb-0">{{ $booking->review->lsp_response }}</p>
                            </div>
                        </div>
                    @elseif(Auth::user()->role === 'lsp')
                        <form action="{{ route('lsp.reviews.respond', $booking->review) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="lsp_response" class="form-label">Respond to this review</label>
                                <textarea class="form-control" id="lsp_response" name="lsp_response" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Response</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lsp.bookings.status', $booking) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Review Modal -->
@if(Auth::user()->role === 'citizen' && $booking->status === 'completed' && !$booking->review)
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Leave a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reviews.store', $booking) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                    <label class="form-check-label" for="rating1">1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                    <label class="form-check-label" for="rating2">2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                    <label class="form-check-label" for="rating3">3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                    <label class="form-check-label" for="rating4">4</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                    <label class="form-check-label" for="rating5">5</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Review</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection