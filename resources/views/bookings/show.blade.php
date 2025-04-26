@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Booking Details</h4>
                    <span class="badge {{ $booking->status === 'completed' ? 'bg-success' : 
                                        ($booking->status === 'confirmed' ? 'bg-primary' : 
                                        ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-warning')) }} fs-6">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>{{ $booking->service->title }}</h5>
                        <p class="text-muted">{{ $booking->service->category }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5 class="text-primary">₹{{ number_format($booking->amount, 2) }}</h5>
                        <p>
                            <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                            @if($booking->transaction_id)
                                <small class="text-muted d-block">Txn: {{ $booking->transaction_id }}</small>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">Booking Information</h6>
                        <p><strong>Date:</strong> {{ date('F d, Y', strtotime($booking->booking_date)) }}</p>
                        <p><strong>Time:</strong> {{ date('h:i A', strtotime($booking->booking_time)) }}</p>
                        <p><strong>Duration:</strong> {{ $booking->service->duration_minutes }} minutes</p>
                        <p><strong>Booked on:</strong> {{ $booking->created_at->format('M d, Y H:i') }}</p>
                        
                        @if($booking->notes)
                            <p><strong>Additional Notes:</strong><br>
                            {{ $booking->notes }}</p>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="mb-3">Provider Information</h6>
                        <p><strong>Name:</strong> {{ $booking->lsp->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->lsp->email }}</p>
                        <p><strong>Phone:</strong> {{ $booking->lsp->phone }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    @if(Auth::user()->role === 'citizen')
                        <a href="{{ route('citizen.bookings') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to My Bookings
                        </a>
                    @elseif(Auth::user()->role === 'lsp')
                        <a href="{{ route('lsp.bookings') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to My Appointments
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to All Bookings
                        </a>
                    @endif
                    
                    @if(Auth::id() === $booking->citizen_id && $booking->status === 'pending')
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times-circle"></i> Cancel Booking
                            </button>
                        </form>
                    @endif
                    
                    @if(Auth::id() === $booking->lsp_id && $booking->status === 'pending')
                        <div>
                            <form action="{{ route('bookings.update', $booking) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Confirm Booking
                                </button>
                            </form>
                            
                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this booking?')">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(Auth::id() === $booking->lsp_id && $booking->status === 'confirmed')
                        <form action="{{ route('bookings.update', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Mark as Completed
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection