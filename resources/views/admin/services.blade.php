@extends('layouts.app')

@section('title', 'Manage Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Services</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(count($services) > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Provider</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->lsp->name }}</td>
                                <td>{{ $service->category }}</td>
                                <td>₹{{ number_format($service->price, 2) }}</td>
                                <td>{{ $service->duration_minutes }} mins</td>
                                <td>
                                    <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
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
    
    <!-- Service Detail Modals -->
    @foreach($services as $service)
        <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-labelledby="serviceModalLabel{{ $service->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="serviceModalLabel{{ $service->id }}">Service: {{ $service->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6>Service Details</h6>
                            <p><strong>Title:</strong> {{ $service->title }}</p>
                            <p><strong>Category:</strong> {{ $service->category }}</p>
                            <p><strong>Price:</strong> ₹{{ number_format($service->price, 2) }}</p>
                            <p><strong>Duration:</strong> {{ $service->duration_minutes }} minutes</p>
                            <p><strong>Status:</strong> 
                                <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $service->created_at->format('F d, Y') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Description</h6>
                            <p>{{ $service->description }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Provider Information</h6>
                            <p><strong>Name:</strong> {{ $service->lsp->name }}</p>
                            <p><strong>Email:</strong> {{ $service->lsp->email }}</p>
                            <p><strong>Phone:</strong> {{ $service->lsp->phone }}</p>
                            
                            @if(isset($service->lsp->lspProfile))
                                <p><strong>Service Type:</strong> {{ ucfirst($service->lsp->lspProfile->service_type) }}</p>
                                <p><strong>Specialization:</strong> {{ $service->lsp->lspProfile->specialization }}</p>
                                <p><strong>Experience:</strong> {{ $service->lsp->lspProfile->experience_years }} years</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View Public Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> There are no services available in the system.
    </div>
@endif
@endsection
