@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="card-text">Find and book legal services from verified professionals across India.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Find Legal Services</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('services.search') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search" placeholder="What legal service do you need?">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <option value="Legal Consultation">Legal Consultation</option>
                            <option value="Document Drafting">Document Drafting</option>
                            <option value="Court Representation">Court Representation</option>
                            <option value="Mediation">Mediation</option>
                            <option value="Arbitration">Arbitration</option>
                            <option value="Notarization">Notarization</option>
                            <option value="Legal Research">Legal Research</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recommended Services</h5>
                <a href="{{ route('services.search') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recommendedServices) && count($recommendedServices) > 0)
                    <div class="row">
                        @foreach($recommendedServices as $service)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $service->title }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $service->category }}</h6>
                                        <p class="card-text small">{{ Str::limit($service->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">₹{{ number_format($service->price, 2) }}</span>
                                            <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-primary">View Details</a>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> {{ $service->lsp->name }}
                                            @if($service->lsp->lspProfile)
                                                | <i class="fas fa-briefcase"></i> {{ ucfirst($service->lsp->lspProfile->service_type) }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No recommended services available at the moment.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bookings</h5>
                <a href="{{ route('citizen.bookings') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentBookings) && count($recentBookings) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentBookings as $booking)
                            <a href="{{ route('bookings.show', $booking) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $booking->service->title }}</h6>
                                    <small>{{ $booking->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Provider: {{ $booking->lsp->name }}</p>
                                <small class="text-muted">
                                    Status: 
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="badge bg-info">Confirmed</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">You haven't made any bookings yet.</p>
                @endif
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Your Rewards</h5>
            </div>
            <div class="card-body text-center">
                <div class="display-4 mb-3">{{ Auth::user()->reward_points }}</div>
                <p class="mb-3">Reward Points</p>
                <a href="{{ route('rewards.index') }}" class="btn btn-outline-primary">View Rewards</a>
            </div>
        </div>
    </div>
</div>
@endsection