@extends('layouts.app')

@section('title', 'Book Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Book Service</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5>{{ $service->title }}</h5>
                        <p class="text-muted">{{ $service->category }}</p>
                        <p>{{ Str::limit($service->description, 150) }}</p>
                        <p><strong>Provider:</strong> {{ $service->lsp->name }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 class="text-primary">₹{{ number_format($service->price, 2) }}</h5>
                        <p class="text-muted">{{ $service->duration_minutes }} minutes</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('bookings.store', $service) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Select Date</label>
                        <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d') }}" required>
                        @error('booking_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="booking_time" class="form-label">Select Time</label>
                        <select class="form-select @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" required>
                            <option value="" selected disabled>Choose a time slot</option>
                            @foreach($service->lsp->lspProfile->available_hours as $timeSlot)
                                @if(strpos($timeSlot, 'Morning') !== false)
                                    <option value="09:00" {{ old('booking_time') == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                                    <option value="10:00" {{ old('booking_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('booking_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                @elseif(strpos($timeSlot, 'Afternoon') !== false)
                                    <option value="12:00" {{ old('booking_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                    <option value="13:00" {{ old('booking_time') == '13:00' ? 'selected' : '' }}>01:00 PM</option>
                                    <option value="14:00" {{ old('booking_time') == '14:00' ? 'selected' : '' }}>02:00 PM</option>
                                    <option value="15:00" {{ old('booking_time') == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                                    <option value="16:00" {{ old('booking_time') == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                                @elseif(strpos($timeSlot, 'Evening') !== false)
                                    <option value="17:00" {{ old('booking_time') == '17:00' ? 'selected' : '' }}>05:00 PM</option>
                                    <option value="18:00" {{ old('booking_time') == '18:00' ? 'selected' : '' }}>06:00 PM</option>
                                    <option value="19:00" {{ old('booking_time') == '19:00' ? 'selected' : '' }}>07:00 PM</option>
                                    <option value="20:00" {{ old('booking_time') == '20:00' ? 'selected' : '' }}>08:00 PM</option>
                                @endif
                            @endforeach
                        </select>
                        @error('booking_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        <div class="form-text">Provide any additional information that might help the service provider.</div>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                <span>Platform Fee:</span>
                                <span>₹{{ number_format($service->price * 0.05, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Amount:</span>
                                <span>₹{{ number_format($service->price * 1.05, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms_agree" required>
                        <label class="form-check-label" for="terms_agree">
                            I agree to the <a href="#" target="_blank">terms and conditions</a> and <a href="#" target="_blank">privacy policy</a>.
                        </label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingDateInput = document.getElementById('booking_date');
        const bookingTimeSelect = document.getElementById('booking_time');
        
        // Get available days from the LSP profile
        const availableDays = @json($service->lsp->lspProfile->available_days);
        
        // Disable dates that are not available
        bookingDateInput.addEventListener('input', function() {
            const selectedDate = new Date(this.value);
            const dayOfWeek = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
            
            if (!availableDays.includes(dayOfWeek)) {
                alert('The service provider is not available on ' + dayOfWeek + 's. Please select another date.');
                this.value = '';
            }
        });
    });
</script>
@endsection