<?php

namespace App\Http\Controllers;

use App\Models\LspProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LspProfileController extends Controller
{
    public function create()
    {
        return view('lsp.profile.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_type' => 'required|in:advocate,arbitrator,mediator,notary,document_writer',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:255',
            'id_proof_type' => 'required|in:aadhar,pan,passport',
            'id_proof_number' => 'required|string|max:255',
            'id_proof_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'qualification' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'available_days' => 'required|array',
            'available_hours' => 'required|array',
            'service_radius' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Handle file upload
        $idProofPath = $request->file('id_proof_document')->store('id_proofs', 'public');

        $lspProfile = LspProfile::create([
            'user_id' => $user->id,
            'service_type' => $request->service_type,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'license_number' => $request->license_number,
            'id_proof_type' => $request->id_proof_type,
            'id_proof_number' => $request->id_proof_number,
            'id_proof_document' => $idProofPath,
            'qualification' => $request->qualification,
            'bio' => $request->bio,
            'verification_status' => 'pending',
            'available_days' => $request->available_days,
            'available_hours' => $request->available_hours,
            'latitude' => $request->latitude ?? 0,
            'longitude' => $request->longitude ?? 0,
            'service_radius' => $request->service_radius,
        ]);

        return redirect()->route('lsp.dashboard')->with('success', 'Profile created successfully! It is now pending verification.');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->lspProfile;

        if (!$profile) {
            return redirect()->route('lsp.profile.create');
        }

        return view('lsp.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',
            'available_days' => 'required|array',
            'available_hours' => 'required|array',
            'service_radius' => 'required|integer|min:1|max:100',
            'id_proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $profile = $user->lspProfile;

        $data = [
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'license_number' => $request->license_number,
            'qualification' => $request->qualification,
            'bio' => $request->bio,
            'available_days' => $request->available_days,
            'available_hours' => $request->available_hours,
            'latitude' => $request->latitude ?? $profile->latitude,
            'longitude' => $request->longitude ?? $profile->longitude,
            'service_radius' => $request->service_radius,
        ];

        // Handle file upload if a new document is provided
        if ($request->hasFile('id_proof_document')) {
            // Delete old file
            if ($profile->id_proof_document) {
                Storage::disk('public')->delete($profile->id_proof_document);
            }
            
            $data['id_proof_document'] = $request->file('id_proof_document')->store('id_proofs', 'public');
        }

        $profile->update($data);

        return redirect()->route('lsp.dashboard')->with('success', 'Profile updated successfully!');
    }
}