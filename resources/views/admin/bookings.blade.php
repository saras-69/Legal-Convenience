@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Bookings</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(count($bookings) > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Citizen</th>
                            <th>LSP</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->service->title }}</td>
                                <td>{{ $booking->citizen->name }}</td>
                                <td>{{ $booking->lsp->name }}</td>
                                <td>{{ $booking->booking_date->format('M d, Y') }} @ {{ date('h:i A', strtotime($booking->booking_time)) }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $booking->status === 'completed' ? 'bg-success' : 
                                          ($booking->status === 'confirmed' ? 'bg-primary' : 
                                          ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-warning')) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $booking->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Booking Detail Modals -->
    @foreach($bookings as $booking)
        <div class="modal fade" id="bookingModal{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel{{ $booking->id }}">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Booking Information</h6>
                                <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
                                <p><strong>Date:</strong> {{ $booking->booking_date->format('F d, Y') }}</p>
                                <p><strong>Time:</strong> {{ date('h:i A', strtotime($booking->booking_time)) }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge 
                                        {{ $booking->status === 'completed' ? 'bg-success' : 
                                          ($booking->status === 'confirmed' ? 'bg-primary' : 
                                          ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-warning')) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                                @if($booking->status === 'cancelled')
                                    <p><strong>Cancellation Reason:</strong> {{ $booking->cancellation_reason }}</p>
                                @endif
                                <p><strong>Amount:</strong> ₹{{ number_format($booking->amount, 2) }}</p>
                                <p><strong>Payment Status:</strong> 
                                    <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </p>
                                @if($booking->transaction_id)
                                    <p><strong>Transaction ID:</strong> {{ $booking->transaction_id }}</p>
                                @endif
                                <p><strong>Created:</strong> {{ $booking->created_at->format('F d, Y H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Service Information</h6>
                                <p><strong>Service:</strong> {{ $booking->service->title }}</p>
                                <p><strong>Category:</strong> {{ $booking->service->category }}</p>
                                <p><strong>Duration:</strong> {{ $booking->service->duration_minutes }} minutes</p>
                                
                                <h6 class="mt-4">Client Information</h6>
                                <p><strong>Name:</strong> {{ $booking->citizen->name }}</p>
                                <p><strong>Email:</strong> {{ $booking->citizen->email }}</p>
                                <p><strong>Phone:</strong> {{ $booking->citizen->phone }}</p>
                                
                                <h6 class="mt-4">Provider Information</h6>
                                <p><strong>Name:</strong> {{ $booking->lsp->name }}</p>
                                <p><strong>Email:</strong> {{ $booking->lsp->email }}</p>
                                <p><strong>Phone:</strong> {{ $booking->lsp->phone }}</p>
                            </div>
                        </div>
                        
                        @if($booking->notes)
                            <div class="mt-4">
                                <h6>Additional Notes</h6>
                                <p>{{ $booking->notes }}</p>
                            </div>
                        @endif
                        
                        @if($booking->review)
                            <div class="mt-4">
                                <h6>Client Review</h6>
                                <div class="d-flex mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2">{{ $booking->review->rating }}/5</span>
                                </div>
                                <p>{{ $booking->review->comment }}</p>
                                
                                @if($booking->review->lsp_response)
                                    <h6 class="mt-3">Provider Response</h6>
                                    <p>{{ $booking->review->lsp_response }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View Booking Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> There are no bookings in the system.
    </div>
@endif
@endsection
