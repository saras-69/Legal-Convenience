@extends('layouts.app')

@section('title', 'Welcome')


@section('content')
<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold">Find Legal Services With Ease</h1>
        <p class="lead">Connect with verified legal professionals across India. From advocates to document writers, find the right legal help for your needs.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="{{ route('services.search') }}" class="btn btn-primary btn-lg px-4 me-md-2">Find Services</a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Join Now</a>
            @endguest
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative image-container">
            <img src="{{ asset('images/image.png') }}" alt="Legal Services" >
            
        </div>
    </div>

<div class="row mt-5 pt-5">
    <div class="col-12 text-center mb-4">
        <h2>Our Services</h2>
        <p class="lead">Find the legal assistance you need from verified professionals</p>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-gavel fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Advocates</h5>
                <p class="card-text">Connect with qualified lawyers for legal advice, representation, and consultation.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-balance-scale fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Arbitrators & Mediators</h5>
                <p class="card-text">Resolve disputes efficiently with our experienced arbitrators and mediators.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-file-signature fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Notaries & Document Writers</h5>
                <p class="card-text">Get your documents drafted, reviewed, and notarized by professionals.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 pt-3">
    <div class="col-12 text-center mb-4">
        <h2>How It Works</h2>
    </div>
    
    <div class="col-md-3 mb-4 text-center">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
            <i class="fas fa-search fa-2x text-primary"></i>
        </div>
        <h5>Search</h5>
        <p>Find the right legal service provider based on your needs</p>
    </div>
    
    <div class="col-md-3 mb-4 text-center">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
            <i class="fas fa-calendar-check fa-2x text-primary"></i>
        </div>
        <h5>Book</h5>
        <p>Schedule an appointment at your convenient time</p>
    </div>
    
    <div class="col-md-3 mb-4 text-center">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
            <i class="fas fa-handshake fa-2x text-primary"></i>
        </div>
        <h5>Connect</h5>
        <p>Meet with your legal service provider</p>
    </div>
    
    <div class="col-md-3 mb-4 text-center">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
            <i class="fas fa-star fa-2x text-primary"></i>
        </div>
        <h5>Review</h5>
        <p>Rate your experience and earn reward points</p>
    </div>
</div>
@endsection