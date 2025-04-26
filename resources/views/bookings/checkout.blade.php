@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Complete Your Payment</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5>{{ $service->title }}</h5>
                        <p class="text-muted">{{ $service->category }}</p>
                        <p><strong>Provider:</strong> {{ $service->lsp->name }}</p>
                        <p><strong>Date:</strong> {{ date('F d, Y', strtotime($booking->booking_date)) }}</p>
                        <p><strong>Time:</strong> {{ date('h:i A', strtotime($booking->booking_time)) }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 class="text-primary">₹{{ number_format($amount, 2) }}</h5>
                        <p class="text-muted">{{ $service->duration_minutes }} minutes</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Payment Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Fee:</span>
                            <span>₹{{ number_format($service->price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Platform Fee (5%):</span>
                            <span>₹{{ number_format($service->price * 0.05, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Amount:</span>
                            <span>₹{{ number_format($amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <button id="rzp-button" class="btn btn-primary btn-lg">
                        <i class="fas fa-credit-card me-2"></i> Pay Now
                    </button>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Your booking will be confirmed once payment is complete.
                </div>
                
                <!-- Hidden form to submit after payment -->
                <form action="{{ route('bookings.complete', $booking) }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            "key": "{{ $razorpayId }}",
            "amount": "{{ $amount * 100 }}",
            "currency": "INR",
            "name": "Legal Convenience",
            "description": "{{ $service->title }}",
            "image": "{{ asset('images/logo.png') }}",
            "order_id": "{{ $razorpayOrder->id }}",
            "handler": function (response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('payment-form').submit();
            },
            "prefill": {
                "name": "{{ $user->name }}",
                "email": "{{ $user->email }}",
                "contact": "{{ $user->phone }}"
            },
            "theme": {
                "color": "#0d6efd"
            }
        };
        
        const rzp = new Razorpay(options);
        
        document.getElementById('rzp-button').addEventListener('click', function(e) {
            rzp.open();
            e.preventDefault();
        });
    });
</script>
@endsection