@extends('layouts.app')

@section('title', 'Create LSP Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Complete Your Legal Service Provider Profile</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lsp.profile.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="service_type" class="form-label">Service Type</label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type" required>
                            <option value="" selected disabled>Select your service type</option>
                            <option value="advocate" {{ old('service_type') == 'advocate' ? 'selected' : '' }}>Advocate</option>
                            <option value="arbitrator" {{ old('service_type') == 'arbitrator' ? 'selected' : '' }}>Arbitrator</option>
                            <option value="mediator" {{ old('service_type') == 'mediator' ? 'selected' : '' }}>Mediator</option>
                            <option value="notary" {{ old('service_type') == 'notary' ? 'selected' : '' }}>Notary</option>
                            <option value="document_writer" {{ old('service_type') == 'document_writer' ? 'selected' : '' }}>Document Writer</option>
                        </select>
                        @error('service_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                        <div class="form-text">E.g., Family Law, Corporate Law, Property Documentation, etc.</div>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="experience_years" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years" value="{{ old('experience_years') }}" min="0" required>
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_number" class="form-label">License/Registration Number</label>
                        <input type="text" class="form-control @error('license_number') is-invalid @enderror" id="license_number" name="license_number" value="{{ old('license_number') }}" required>
                        @error('license_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control @error('qualification') is-invalid @enderror" id="qualification" name="qualification" value="{{ old('qualification') }}" required>
                        <div class="form-text">E.g., LLB, LLM, Certified Mediator, etc.</div>
                        @error('qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_proof_type" class="form-label">ID Proof Type</label>
                        <select class="form-select @error('id_proof_type') is-invalid @enderror" id="id_proof_type" name="id_proof_type" required>
                            <option value="" selected disabled>Select ID proof type</option>
                            <option value="aadhar" {{ old('id_proof_type') == 'aadhar' ? 'selected' : '' }}>Aadhar Card</option>
                            <option value="pan" {{ old('id_proof_type') == 'pan' ? 'selected' : '' }}>PAN Card</option>
                            <option value="passport" {{ old('id_proof_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                        </select>
                        @error('id_proof_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_proof_number" class="form-label">ID Proof Number</label>
                        <input type="text" class="form-control @error('id_proof_number') is-invalid @enderror" id="id_proof_number" name="id_proof_number" value="{{ old('id_proof_number') }}" required>
                        @error('id_proof_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_proof_document" class="form-label">Upload ID Proof Document</label>
                        <input type="file" class="form-control @error('id_proof_document') is-invalid @enderror" id="id_proof_document" name="id_proof_document" required>
                        <div class="form-text">Upload a scanned copy of your ID proof (PDF, JPG, PNG, max 2MB)</div>
                        @error('id_proof_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="bio" class="form-label">Professional Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4" required>{{ old('bio') }}</textarea>
                        <div class="form-text">Briefly describe your professional background and expertise (max 1000 characters)</div>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Available Days</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_monday" value="Monday" {{ is_array(old('available_days')) && in_array('Monday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_monday">Monday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_tuesday" value="Tuesday" {{ is_array(old('available_days')) && in_array('Tuesday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_tuesday">Tuesday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_wednesday" value="Wednesday" {{ is_array(old('available_days')) && in_array('Wednesday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_wednesday">Wednesday</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_thursday" value="Thursday" {{ is_array(old('available_days')) && in_array('Thursday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_thursday">Thursday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_friday" value="Friday" {{ is_array(old('available_days')) && in_array('Friday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_friday">Friday</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_saturday" value="Saturday" {{ is_array(old('available_days')) && in_array('Saturday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_saturday">Saturday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_days[]" id="day_sunday" value="Sunday" {{ is_array(old('available_days')) && in_array('Sunday', old('available_days')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_sunday">Sunday</label>
                                </div>
                            </div>
                        </div>
                        @error('available_days')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Available Hours</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_hours[]" id="hour_morning" value="Morning (9AM-12PM)" {{ is_array(old('available_hours')) && in_array('Morning (9AM-12PM)', old('available_hours')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hour_morning">Morning (9AM-12PM)</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_hours[]" id="hour_afternoon" value="Afternoon (12PM-5PM)" {{ is_array(old('available_hours')) && in_array('Afternoon (12PM-5PM)', old('available_hours')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hour_afternoon">Afternoon (12PM-5PM)</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_hours[]" id="hour_evening" value="Evening (5PM-9PM)" {{ is_array(old('available_hours')) && in_array('Evening (5PM-9PM)', old('available_hours')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hour_evening">Evening (5PM-9PM)</label>
                                </div>
                            </div>
                        </div>
                        @error('available_hours')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="service_radius" class="form-label">Service Radius (in km)</label>
                        <input type="number" class="form-control @error('service_radius') is-invalid @enderror" id="service_radius" name="service_radius" value="{{ old('service_radius', 10) }}" min="1" max="100" required>
                        <div class="form-text">Maximum distance you're willing to travel to provide services</div>
                        @error('service_radius')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> We'll use your current location for service matching. You can update this later.
                        </div>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', 0) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', 0) }}">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Submit Profile for Verification</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Get user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }, function(error) {
            console.error("Error getting location: ", error);
        });
    }
</script>
@endsection