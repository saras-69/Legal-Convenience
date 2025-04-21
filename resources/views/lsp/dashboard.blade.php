@extends('layouts.app')

@section('title', 'LSP Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="card-text">Manage your legal services and bookings from this dashboard.</p>
                
                @if(!Auth::user()->lspProfile || Auth::user()->lspProfile->verification_status === 'pending')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        @if(!Auth::user()->lspProfile)
                            Your profile is incomplete. Please <a href="{{ route('lsp.profile.create') }}">complete your profile</a> to start offering services.
                        @else
                            Your profile is pending verification. You'll be notified once it's approved.
                        @endif
                    </div>
                @elseif(Auth::user()->lspProfile->verification_status === 'rejected')
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i> Your profile verification was rejected. Reason: {{ Auth::user()->lspProfile->rejection_reason }}
                        <div class="mt-2">
                            <a href="{{ route('lsp.profile.edit') }}" class="btn btn-sm btn-outline-danger">Update Profile</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-briefcase fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">My Services</h5>
                <p class="card-text">Manage your service offerings</p>
                <a href="{{ route('lsp.services.index') }}" class="btn btn-primary">View Services</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-alt fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Bookings</h5>
                <p class="card-text">View and manage your bookings</p>
                <a href="{{ route('lsp.bookings') }}" class="btn btn-primary">View Bookings</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-gift fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Rewards</h5>
                <p class="card-text">Check your reward points and redeem benefits</p>
                <a href="{{ route('rewards.index') }}" class="btn btn-primary">View Rewards</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @if(isset($recentBookings) && count($recentBookings) > 0)
                        @foreach($recentBookings as $booking)
                            <a href="{{ route('bookings.show', $booking) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $booking->service->title }}</h6>
                                    <small>{{ $booking->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Client: {{ $booking->citizen->name }}</p>
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
                    @else
                        <p class="text-muted">No recent bookings found.</p>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('lsp.bookings') }}" class="btn btn-sm btn-outline-primary">View All Bookings</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Reviews</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @if(isset($recentReviews) && count($recentReviews) > 0)
                        @foreach($recentReviews as $review)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $review->booking->service->title }}</h6>
                                    <small>{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                        <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="mb-1">{{ $review->comment }}</p>
                                <small class="text-muted">By: {{ $review->citizen->name }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                