@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Bookings</h2>
    <a href="{{ route('lsp.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <ul class="nav nav-tabs card-header-tabs" id="bookingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pending</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab" aria-controls="confirmed" aria-selected="false">Confirmed</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="bookingTabsContent">
            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                @if(count($bookings->where('status', 'pending')) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings->where('status', 'pending') as $booking)
                                    <tr>
                                        <td>{{ $booking->service->title }}</td>
                                        <td>{{ $booking->citizen->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->booking_date)) }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
                                        <td>₹{{ number_format($booking->amount, 2) }}</td>
                                        <td>
                                            @if($booking->payment_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any pending bookings.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                @if(count($bookings->where('status', 'confirmed')) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings->where('status', 'confirmed') as $booking)
                                    <tr>
                                        <td>{{ $booking->service->title }}</td>
                                        <td>{{ $booking->citizen->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->booking_date)) }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
                                        <td>₹{{ number_format($booking->amount, 2) }}</td>
                                        <td>
                                            @if($booking->payment_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any confirmed bookings.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                @if(count($bookings->where('status', 'completed')) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Review</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings->where('status', 'completed') as $booking)
                                    <tr>
                                        <td>{{ $booking->service->title }}</td>
                                        <td>{{ $booking->citizen->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->booking_date)) }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
                                        <td>₹{{ number_format($booking->amount, 2) }}</td>
                                        <td>
                                            @if($booking->review)
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $booking->review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            @else
                                                <span class="text-muted">Not reviewed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any completed bookings yet.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                @if(count($bookings->where('status', 'cancelled')) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Cancellation Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings->where('status', 'cancelled') as $booking)
                                    <tr>
                                        <td>{{ $booking->service->title }}</td>
                                        <td>{{ $booking->citizen->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->booking_date)) }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
                                        <td>₹{{ number_format($booking->amount, 2) }}</td>
                                        <td>{{ Str::limit($booking->cancellation_reason, 30) }}</td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any cancelled bookings.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection