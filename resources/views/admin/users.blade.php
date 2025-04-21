@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Users</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(count($users) > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'lsp' ? 'bg-primary' : 'bg-success') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->is_verified ? 'bg-success' : 'bg-warning' }}">
                                        {{ $user->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
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
    
    <!-- User Detail Modals -->
    @foreach($users as $user)
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel{{ $user->id }}">User Details: {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                            <p><strong>Verified:</strong> {{ $user->is_verified ? 'Yes' : 'No' }}</p>
                            <p><strong>Created:</strong> {{ $user->created_at->format('F d, Y H:i:s') }}</p>
                            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('F d, Y H:i:s') }}</p>
                            
                            @if($user->role === 'citizen')
                                <p><strong>Reward Points:</strong> {{ $user->reward_points ?? 0 }}</p>
                            @endif
                            
                            @if($user->role === 'lsp')
                                <div class="mt-4">
                                    <h6>LSP Profile:</h6>
                                    @if(isset($user->lspProfile))
                                        <p><strong>Service Type:</strong> {{ ucfirst($user->lspProfile->service_type) }}</p>
                                        <p><strong>Specialization:</strong> {{ $user->lspProfile->specialization }}</p>
                                        <p><strong>Verification Status:</strong> 
                                            <span class="badge {{ $user->lspProfile->verification_status === 'verified' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($user->lspProfile->verification_status) }}
                                            </span>
                                        </p>
                                        <a href="{{ route('admin.verifications') }}" class="btn btn-sm btn-outline-primary">View Full Profile</a>
                                    @else
                                        <p class="text-muted">No LSP profile created yet.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> There are no users in the system.
    </div>
@endif
@endsection
