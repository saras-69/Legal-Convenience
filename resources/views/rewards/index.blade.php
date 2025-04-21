@extends('layouts.app')

@section('title', 'My Rewards')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Your Reward Points</h5>
            </div>
            <div class="card-body text-center">
                <div class="display-1 mb-3">{{ Auth::user()->reward_points }}</div>
                <p class="lead">Available Points</p>
                
                @if(Auth::user()->reward_points >= 100)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#redeemModal">
                        Redeem Points
                    </button>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You need at least 100 points to redeem rewards.
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">How to Earn Points</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Complete a booking
                        @if(Auth::user()->role === 'lsp')
                            <span class="badge bg-primary rounded-pill">10% of booking amount</span>
                        @else
                            <span class="badge bg-primary rounded-pill">5% of booking amount</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Leave a review
                        <span class="badge bg-primary rounded-pill">10 points</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Refer a friend
                        <span class="badge bg-primary rounded-pill">50 points</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Reward History</h5>
            </div>
            <div class="card-body">
                @if(count($rewards) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewards as $reward)
                                    <tr>
                                        <td>{{ $reward->created_at->format('M d, Y') }}</td>
                                        <td>{{ $reward->description }}</td>
                                        <td>
                                            @if($reward->type === 'earned')
                                                <span class="badge bg-success">Earned</span>
                                            @else
                                                <span class="badge bg-warning">Redeemed</span>
                                            @endif
                                        </td>
                                        <td class="{{ $reward->type === 'earned' ? 'text-success' : 'text-danger' }}">
                                            {{ $reward->type === 'earned' ? '+' : '-' }}{{ abs($reward->points) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You haven't earned any rewards yet. Complete bookings to start earning points!
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Redemption Options</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Wallet Credit</h5>
                                <p class="card-text">Convert your points to wallet credit for future bookings.</p>
                                <p class="text-primary fw-bold">100 points = ₹50 credit</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Service Discount</h5>
                                <p class="card-text">Get a discount on your next booking.</p>
                                <p class="text-primary fw-bold">200 points = 10% discount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Premium Listing</h5>
                                <p class="card-text">For LSPs: Get your services featured on the homepage.</p>
                                <p class="text-primary fw-bold">500 points = 1 week featured</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Priority Support</h5>
                                <p class="card-text">Get priority customer support for any issues.</p>
                                <p class="text-primary fw-bold">300 points = 1 month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Redeem Modal -->
<div class="modal fade" id="redeemModal" tabindex="-1" aria-labelledby="redeemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="redeemModalLabel">Redeem Points</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('rewards.redeem') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="points" class="form-label">Points to Redeem</label>
                        <input type="number" class="form-control" id="points" name="points" min="100" max="{{ Auth::user()->reward_points }}" value="100" required>
                        <div class="form-text">You have {{ Auth::user()->reward_points }} points available. Minimum redemption is 100 points.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="redemption_type" class="form-label">Redemption Type</label>
                        <select class="form-select" id="redemption_type" name="redemption_type" required>
                            <option value="wallet_credit">Wallet Credit (100 points = ₹50)</option>
                            <option value="service_discount">Service Discount (200 points = 10% off)</option>
                            @if(Auth::user()->role === 'lsp')
                                <option value="premium_listing">Premium Listing (500 points = 1 week)</option>
                            @endif
                            <option value="priority_support">Priority Support (300 points = 1 month)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Redeem Points</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pointsInput = document.getElementById('points');
        const redemptionTypeSelect = document.getElementById('redemption_type');
        
        redemptionTypeSelect.addEventListener('change', function() {
            const selectedOption = this.value;
            
            if (selectedOption === 'wallet_credit') {
                pointsInput.min = 100;
                pointsInput.value = Math.max(100, pointsInput.value);
            } else if (selectedOption === 'service_discount') {
                pointsInput.min = 200;
                pointsInput.value = Math.max(200, pointsInput.value);
            } else if (selectedOption === 'premium_listing') {
                pointsInput.min = 500;
                pointsInput.value = Math.max(500, pointsInput.value);
            } else if (selectedOption === 'priority_support') {
                pointsInput.min = 300;
                pointsInput.value = Math.max(300, pointsInput.value);
            }
        });
    });
</script>
@endsection