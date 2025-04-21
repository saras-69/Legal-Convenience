@extends('layouts.app')

@section('title', $service->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title">{{ $service->title }}</h2>
                <h6 class="card-subtitle mb-3 text-muted">{{ $service->category }}</h6>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p>{{ $service->description }}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Price</h5>
                                <p class="card-text fs-4 fw-bold text-primary">₹{{ number_format($service->price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Duration</h5>
                                <p class="card-text fs-4 fw-bold">{{ $service->duration_minutes }} minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @auth
                    @if(Auth::user()->role === 'citizen')
                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.create', $service) }}" class="btn btn-primary btn-lg">Book Now</a>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to book this service.
                    </div>
                @endauth
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Reviews</h5>
            </div>
            <div class="card-body">
                @if(count($service->lsp->receivedReviews) > 0)
                    @foreach($service->lsp->receivedReviews as $review)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $review->citizen->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p>{{ $review->comment }}</p>
                            
                            @if($review->lsp_response)
                                <div class="card bg-light mt-2">
                                    <div class="card-body py-2">
                                        <small class="text-muted">Response from {{ $service->lsp->name }}:</small>
                                        <p class="mb-0">{{ $review->lsp_response }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <hr>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No reviews yet for this service provider.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Service Provider</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="https://via.placeholder.com/150" alt="{{ $service->lsp->name }}" class="rounded-circle">
                </div>
                <h5>{{ $service->lsp->name }}</h5>
                <p class="text-muted">{{ ucfirst($service->lsp->lspProfile->service_type) }}</p>
                
                <div class="d-flex justify-content-center mb-3">
                    <div class="px-3 border-end">
                        <h6>Experience</h6>
                        <p class="mb-0">{{ $service->lsp->lspProfile->experience_years }} years</p>
                    </div>
                    <div class="px-3">
                        <h6>Specialization</h6>
                        <p class="mb-0">{{ $service->lsp->lspProfile->specialization }}</p>
                    </div>
                </div>
                
                <p>{{ Str::limit($service->lsp->lspProfile->bio, 200) }}</p>
                
                <div class="mb-3">
                    <h6>Qualification</h6>
                    <p>{{ $service->lsp->lspProfile->qualification }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Available Days</h6>
                    <p>{{ implode(', ', $service->lsp->lspProfile->available_days) }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Available Hours</h6>
                    <p>{{ implode(', ', $service->lsp->lspProfile->available_hours) }}</p>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Other Services by this Provider</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($service->lsp->lspProfile->services->where('id', '!=', $service->id)->take(5) as $otherService)
                        <a href="{{ route('services.show', $otherService) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $otherService->title }}</h6>
                                <small>₹{{ number_format($otherService->price, 2) }}</small>
                            </div>
                            <small class="text-muted">{{ $otherService->category }}</small>
                        </a>
                    @endforeach
                    
                    @if(count($service->lsp->lspProfile->services->where('id', '!=', $service->id)) === 0)
                        <p class="text-muted">No other services available from this provider.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection