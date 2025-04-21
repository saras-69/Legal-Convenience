@extends('layouts.app')

@section('title', 'Pending Verifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pending LSP Verifications</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(count($profiles) > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Service Type</th>
                            <th>Experience</th>
                            <th>License Number</th>
                            <th>Submitted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profiles as $profile)
                            <tr>
                                <td>{{ $profile->user->name }}</td>
                                <td>{{ ucfirst($profile->service_type) }}</td>
                                <td>{{ $profile->experience_years }} years</td>
                                <td>{{ $profile->license_number }}</td>
                                <td>{{ $profile->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#profileModal{{ $profile->id }}">
                                        <i class="fas fa-eye"></i> Review
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Profile Review Modals -->
    @foreach($profiles as $profile)
        <div class="modal fade" id="profileModal{{ $profile->id }}" tabindex="-1" aria-labelledby="profileModalLabel{{ $profile->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileModalLabel{{ $profile->id }}">Review LSP Profile: {{ $profile->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $profile->user->name }}</p>
                                <p><strong>Email:</strong> {{ $profile->user->email }}</p>
                                <p><strong>Phone:</strong> {{ $profile->user->phone }}</p>
                                <p><strong>Service Type:</strong> {{ ucfirst($profile->service_type) }}</p>
                                <p><strong>Specialization:</strong> {{ $profile->specialization }}</p>
                                <p><strong>Experience:</strong> {{ $profile->experience_years }} years</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>License Number:</strong> {{ $profile->license_number }}</p>
                                <p><strong>ID Proof Type:</strong> {{ ucfirst($profile->id_proof_type) }}</p>
                                <p><strong>ID Proof Number:</strong> {{ $profile->id_proof_number }}</p>
                                <p><strong>Qualification:</strong> {{ $profile->qualification }}</p>
                                <p><strong>Available Days:</strong> {{ implode(', ', $profile->available_days) }}</p>
                                <p><strong>Available Hours:</strong> {{ implode(', ', $profile->available_hours) }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Professional Bio:</h6>
                            <p>{{ $profile->bio }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>ID Proof Document:</h6>
                            <a href="{{ Storage::url($profile->id_proof_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file"></i> View Document
                            </a>
                        </div>
                        
                        <hr>
                        
                        <form action="{{ route('admin.verify', $profile) }}" method="POST" id="verificationForm{{ $profile->id }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Verification Decision:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action" id="approve{{ $profile->id }}" value="approve" checked>
                                    <label class="form-check-label" for="approve{{ $profile->id }}">
                                        Approve Profile
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action" id="reject{{ $profile->id }}" value="reject">
                                    <label class="form-check-label" for="reject{{ $profile->id }}">
                                        Reject Profile
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3 rejection-reason{{ $profile->id }}" style="display: none;">
                                <label for="rejection_reason" class="form-label">Rejection Reason:</label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"></textarea>
                                <div class="form-text">Please provide a reason for rejection that will be shared with the LSP.</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="verificationForm{{ $profile->id }}" class="btn btn-primary">Submit Decision</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const approveRadio{{ $profile->id }} = document.getElementById('approve{{ $profile->id }}');
                const rejectRadio{{ $profile->id }} = document.getElementById('reject{{ $profile->id }}');
                const rejectionReason{{ $profile->id }} = document.querySelector('.rejection-reason{{ $profile->id }}');
                
                approveRadio{{ $profile->id }}.addEventListener('change', function() {
                    rejectionReason{{ $profile->id }}.style.display = 'none';
                });
                
                rejectRadio{{ $profile->id }}.addEventListener('change', function() {
                    rejectionReason{{ $profile->id }}.style.display = 'block';
                });
            });
        </script>
    @endforeach
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> There are no pending LSP verifications at the moment.
    </div>
@endif
@endsection