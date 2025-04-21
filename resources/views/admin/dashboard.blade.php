@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h2 class="mb-4">Admin Dashboard</h2>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.users') }}" class="text-white-50 small">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Legal Service Providers</h6>
                        <h2 class="mb-0">{{ $totalLsps }}</h2>
                    </div>
                    <i class="fas fa-user-tie fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.users') }}?role=lsp" class="text-white-50 small">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Citizens</h6>
                        <h2 class="mb-0">{{ $totalCitizens }}</h2>
                    </div>
                    <i class="fas fa-user fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.users') }}?role=citizen" class="text-white-50 small">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Pending Verifications</h6>
                        <h2 class="mb-0">{{ $pendingVerifications }}</h2>
                    </div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.verifications') }}" class="text-white-50 small">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Platform Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Services</h6>
                                <h3>{{ $totalServices }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Bookings</h6>
                                <h3>{{ $totalBookings }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Completed Bookings</h6>
                                <h3>{{ $completedBookings }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Completion Rate</h6>
                                <h3>{{ $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100) : 0 }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.verifications') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-check me-2"></i> Verify LSP Profiles
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $pendingVerifications }}</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.services') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-briefcase me-2"></i> Manage Services
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-check me-2"></i> View Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection