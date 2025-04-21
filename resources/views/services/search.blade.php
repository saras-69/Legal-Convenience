@extends('layouts.app')

@section('title', 'Search Services')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Find Legal Services</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('services.search') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search" placeholder="What legal service do you need?" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <option value="Legal Consultation" {{ request('category') == 'Legal Consultation' ? 'selected' : '' }}>Legal Consultation</option>
                            <option value="Document Drafting" {{ request('category') == 'Document Drafting' ? 'selected' : '' }}>Document Drafting</option>
                            <option value="Court Representation" {{ request('category') == 'Court Representation' ? 'selected' : '' }}>Court Representation</option>
                            <option value="Mediation" {{ request('category') == 'Mediation' ? 'selected' : '' }}>Mediation</option>
                            <option value="Arbitration" {{ request('category') == 'Arbitration' ? 'selected' : '' }}>Arbitration</option>
                            <option value="Notarization" {{ request('category') == 'Notarization' ? 'selected' : '' }}>Notarization</option>
                            <option value="Legal Research" {{ request('category') == 'Legal Research' ? 'selected' : '' }}>Legal Research</option>
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
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('services.search') }}" method="GET" id="filterForm">
                    <!-- Hidden fields to preserve search and category -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="service_type[]" value="advocate" id="type_advocate" {{ is_array(request('service_type')) && in_array('advocate', request('service_type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_advocate">Advocate</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="service_type[]" value="arbitrator" id="type_arbitrator" {{ is_array(request('service_type')) && in_array('arbitrator', request('service_type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_arbitrator">Arbitrator</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="service_type[]" value="mediator" id="type_mediator" {{ is_array(request('service_type')) && in_array('mediator', request('service_type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_mediator">Mediator</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="service_type[]" value="notary" id="type_notary" {{ is_array(request('service_type')) && in_array('notary', request('service_type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_notary">Notary</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="service_type[]" value="document_writer" id="type_document_writer" {{ is_array(request('service_type')) && in_array('document_writer', request('service_type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_document_writer">Document Writer</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price_range" class="form-label">Price Range</label>
                        <div class="d-flex align-items-center">
                            <span>₹0</span>
                            <input type="range" class="form-range mx-2 filter-range" id="price_range" name="max_price" min="0" max="10000" step="500" value="{{ request('max_price', 10000) }}">
                            <span>₹<span id="price_value">{{ request('max_price', 10000) }}</span></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="experience" class="form-label">Minimum Experience (Years)</label>
                        <select class="form-select filter-select" id="experience" name="min_experience">
                            <option value="">Any</option>
                            <option value="1" {{ request('min_experience') == '1' ? 'selected' : '' }}>1+ years</option>
                            <option value="3" {{ request('min_experience') == '3' ? 'selected' : '' }}>3+ years</option>
                            <option value="5" {{ request('min_experience') == '5' ? 'selected' : '' }}>5+ years</option>
                            <option value="10" {{ request('min_experience') == '10' ? 'selected' : '' }}>10+ years</option>
                        </select>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Search Results</h4>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Price: Low to High</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Price: High to Low</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="experience_desc">Experience: High to Low</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="rating_desc">Rating: High to Low</a></li>
                </ul>
                <input type="hidden" name="sort" id="sort_input" value="{{ request('sort', '') }}">
            </div>
        </div>
        
        @if(count($services) > 0)
            <div class="row">
                @foreach($services as $service)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->title }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $service->category }}</h6>
                                <p class="card-text">{{ Str::limit($service->description, 150) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-primary">₹{{ number_format($service->price, 2) }}</span>
                                    <span class="text-muted small">{{ $service->duration_minutes }} minutes</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> {{ $service->lsp->name }}
                                            @if($service->lsp->lspProfile)
                                                <br><i class="fas fa-briefcase"></i> {{ ucfirst($service->lsp->lspProfile->service_type) }}
                                                <br><i class="fas fa-star"></i> {{ $service->lsp->lspProfile->experience_years }} years experience
                                            @endif
                                        </small>
                                    </div>
                                    <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No services found matching your criteria. Try adjusting your search or filters.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price range slider
        const priceRange = document.getElementById('price_range');
        const priceValue = document.getElementById('price_value');
        
        priceRange.addEventListener('input', function() {
            priceValue.textContent = this.value;
        });
        
        // Auto-submit form on filter change
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
        const filterSelects = document.querySelectorAll('.filter-select');
        const filterRanges = document.querySelectorAll('.filter-range');
        
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        filterRanges.forEach(range => {
            range.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        // Sorting
        const sortOptions = document.querySelectorAll('.sort-option');
        const sortInput = document.getElementById('sort_input');
        
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                sortInput.value = this.dataset.sort;
                document.getElementById('filterForm').submit();
            });
        });
    });
</script>
@endsection