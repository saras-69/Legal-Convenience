@extends('layouts.app')

@section('title', 'My Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Services</h2>
    <a href="{{ route('lsp.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Service
    </a>
</div>

@if(count($services) > 0)
    <div class="row">
        @foreach($services as $service)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $service->title }}</h5>
                        <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Category:</strong> {{ $service->category }}</p>
                        <p class="card-text"><strong>Price:</strong> ₹{{ number_format($service->price, 2) }}</p>
                        <p class="card-text"><strong>Duration:</strong> {{ $service->duration_minutes }} minutes</p>
                        <p class="card-text">{{ $service->description }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('lsp.services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('lsp.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> You haven't added any services yet. 
        <a href="{{ route('lsp.services.create') }}" class="alert-link">Add your first service</a> to start receiving bookings.
    </div>
@endif
@endsection