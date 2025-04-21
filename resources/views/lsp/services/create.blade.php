@extends('layouts.app')

@section('title', 'Add New Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Service</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lsp.services.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Service Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="" selected disabled>Select a category</option>
                            <option value="Legal Consultation" {{ old('category') == 'Legal Consultation' ? 'selected' : '' }}>Legal Consultation</option>
                            <option value="Document Drafting" {{ old('category') == 'Document Drafting' ? 'selected' : '' }}>Document Drafting</option>
                            <option value="Court Representation" {{ old('category') == 'Court Representation' ? 'selected' : '' }}>Court Representation</option>
                            <option value="Mediation" {{ old('category') == 'Mediation' ? 'selected' : '' }}>Mediation</option>
                            <option value="Arbitration" {{ old('category') == 'Arbitration' ? 'selected' : '' }}>Arbitration</option>
                            <option value="Notarization" {{ old('category') == 'Notarization' ? 'selected' : '' }}>Notarization</option>
                            <option value="Legal Research" {{ old('category') == 'Legal Research' ? 'selected' : '' }}>Legal Research</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (₹)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="15" step="15" required>
                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Add Service</button>
                        <a href="{{ route('lsp.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection