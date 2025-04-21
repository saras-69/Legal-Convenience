@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Bookings</h2>
    <a href="{{ route('services.search') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Book New Service
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <ul class="nav nav-tabs card-header-tabs" id="bookingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</button>
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
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                @if(count($bookings->where('status', 'pending')->merge($bookings->where('status', 'confirmed'))) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Provider</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings->where('status', 'pending')->merge($bookings->where('status', 'confirmed')) as $booking)
                                    <tr>
                                        <td>{{ $booking->service->title }}</td>
                                        <td>{{ $booking->lsp->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->booking_date)) }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
                                        <td>₹{{ number_format($booking->amount, 2) }}</td>
                                        <td>
                                            @if($booking->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status === 'confirmed')
                                                <span class="badge bg-info">Confirmed</span>
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
                        <i class="fas fa-info-circle"></i> You don't have any upcoming bookings.
                        <a href="{{ route('services.search') }}" class="alert-link">Book a service now</a>.
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
                                    <th>Provider</th>
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
                                        <td>{{ $booking->lsp->name }}</td>
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
                                    <th>Provider</th>
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
                                        <td>{{ $booking->lsp->name }}</td>
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