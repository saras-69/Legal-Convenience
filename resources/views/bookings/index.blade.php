@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            @if(Auth::user()->role === 'citizen')
                My Bookings
            @elseif(Auth::user()->role === 'lsp')
                My Appointments
            @else
                All Bookings
            @endif
        </h2>
        
        @if(Auth::user()->role === 'citizen')
            <!-- Changed this line to use a route that exists in your application -->
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Book New Service
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(count($bookings) > 0)
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($bookings as $booking)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header 
                            {{ $booking->status === 'completed' ? 'bg-success text-white' : 
                              ($booking->status === 'confirmed' ? 'bg-primary text-white' : 
                              ($booking->status === 'cancelled' ? 'bg-danger text-white' : 'bg-warning')) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $booking->service->title }}</h5>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <p class="mb-0"><strong>Date:</strong> {{ date('F d, Y', strtotime($booking->booking_date)) }}</p>
                                    <p class="mb-0"><strong>Time:</strong> {{ date('h:i A', strtotime($booking->booking_time)) }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="mb-0"><strong>Amount:</strong> ₹{{ number_format($booking->amount, 2) }}</p>
                                    <p class="mb-0">
                                        <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            @if(Auth::user()->role === 'citizen')
                                <p><strong>Provider:</strong> {{ $booking->lsp->name }}</p>
                            @elseif(Auth::user()->role === 'lsp')
                                <p><strong>Client:</strong> {{ $booking->citizen->name }}</p>
                            @endif
                            
                            @if($booking->transaction_id)
                                <p class="text-muted small mb-0">Transaction ID: {{ $booking->transaction_id }}</p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            @if(Auth::user()->role === 'citizen')
                You don't have any bookings yet. Book your first legal service now!
            @elseif(Auth::user()->role === 'lsp')
                You don't have any client appointments yet.
            @else
                There are no bookings in the system.
            @endif
        </div>
    @endif
</div>
@endsection