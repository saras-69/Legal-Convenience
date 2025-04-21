<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LspProfile;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@legalconvenience.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
            'reward_points' => 0,
        ]);

        // Create Citizen Users
        $citizen1 = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '9876543211',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'is_verified' => true,
            'reward_points' => 150,
        ]);

        $citizen2 = User::create([
            'name' => 'Priya Patel',
            'email' => 'priya@example.com',
            'phone' => '9876543212',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'is_verified' => true,
            'reward_points' => 75,
        ]);

        // Create LSP Users and Profiles
        $lsp1 = User::create([
            'name' => 'Advocate Amit Kumar',
            'email' => 'amit@example.com',
            'phone' => '9876543213',
            'password' => Hash::make('password'),
            'role' => 'lsp',
            'is_verified' => true,
            'reward_points' => 250,
        ]);

        LspProfile::create([
            'user_id' => $lsp1->id,
            'service_type' => 'advocate',
            'specialization' => 'Family Law',
            'experience_years' => 8,
            'license_number' => 'ADV12345',
            'id_proof_type' => 'aadhar',
            'id_proof_number' => '123456789012',
            'id_proof_document' => 'id_proofs/sample.pdf',
            'qualification' => 'LLB, Delhi University',
            'bio' => 'Experienced family law advocate with expertise in divorce, child custody, and property disputes. Committed to providing compassionate and effective legal representation.',
            'verification_status' => 'verified',
            'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'available_hours' => ['Morning (9AM-12PM)', 'Afternoon (12PM-5PM)'],
            'latitude' => 28.6139,
            'longitude' => 77.2090,
            'service_radius' => 15,
        ]);

        $lsp2 = User::create([
            'name' => 'Notary Sunita Verma',
            'email' => 'sunita@example.com',
            'phone' => '9876543214',
            'password' => Hash::make('password'),
            'role' => 'lsp',
            'is_verified' => true,
            'reward_points' => 180,
        ]);

        LspProfile::create([
            'user_id' => $lsp2->id,
            'service_type' => 'notary',
            'specialization' => 'Document Notarization',
            'experience_years' => 5,
            'license_number' => 'NOT54321',
            'id_proof_type' => 'pan',
            'id_proof_number' => 'ABCDE1234F',
            'id_proof_document' => 'id_proofs/sample2.pdf',
            'qualification' => 'LLB, Mumbai University',
            'bio' => 'Certified notary public with experience in notarizing various legal documents including affidavits, agreements, and power of attorney. Providing prompt and reliable notary services.',
            'verification_status' => 'verified',
            'available_days' => ['Monday', 'Wednesday', 'Friday', 'Saturday'],
            'available_hours' => ['Morning (9AM-12PM)', 'Evening (5PM-9PM)'],
            'latitude' => 28.6129,
            'longitude' => 77.2295,
            'service_radius' => 10,
        ]);

        $lsp3 = User::create([
            'name' => 'Mediator Rajesh Singh',
            'email' => 'rajesh@example.com',
            'phone' => '9876543215',
            'password' => Hash::make('password'),
            'role' => 'lsp',
            'is_verified' => true,
            'reward_points' => 120,
        ]);

        LspProfile::create([
            'user_id' => $lsp3->id,
            'service_type' => 'mediator',
            'specialization' => 'Commercial Disputes',
            'experience_years' => 12,
            'license_number' => 'MED98765',
            'id_proof_type' => 'passport',
            'id_proof_number' => 'P1234567',
            'id_proof_document' => 'id_proofs/sample3.pdf',
            'qualification' => 'LLM, National Law School',
            'bio' => 'Certified mediator with extensive experience in resolving commercial and business disputes. Specializing in helping parties find mutually beneficial solutions outside of court.',
            'verification_status' => 'verified',
            'available_days' => ['Tuesday', 'Thursday', 'Saturday'],
            'available_hours' => ['Afternoon (12PM-5PM)', 'Evening (5PM-9PM)'],
            'latitude' => 28.5355,
            'longitude' => 77.2410,
            'service_radius' => 20,
        ]);

        // Create Services
        Service::create([
            'lsp_id' => $lsp1->id,
            'title' => 'Divorce Consultation',
            'description' => 'Initial consultation for divorce proceedings. Understand your rights, the legal process, and potential outcomes. Get expert advice on how to proceed with your case.',
            'category' => 'Legal Consultation',
            'price' => 1500,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp1->id,
            'title' => 'Child Custody Advice',
            'description' => 'Legal consultation regarding child custody matters. Understand the factors courts consider when determining custody and how to present your case effectively.',
            'category' => 'Legal Consultation',
            'price' => 2000,
            'duration_minutes' => 90,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp1->id,
            'title' => 'Property Dispute Representation',
            'description' => 'Legal representation for property disputes. Includes document review, case strategy development, and court representation if necessary.',
            'category' => 'Court Representation',
            'price' => 5000,
            'duration_minutes' => 120,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp2->id,
            'title' => 'Affidavit Notarization',
            'description' => 'Notarization of affidavits and sworn statements. Ensure your documents are legally verified and authenticated.',
            'category' => 'Notarization',
            'price' => 500,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp2->id,
            'title' => 'Power of Attorney Notarization',
            'description' => 'Notarization of power of attorney documents. Includes verification of identity and ensuring the document meets legal requirements.',
            'category' => 'Notarization',
            'price' => 800,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp3->id,
            'title' => 'Business Dispute Mediation',
            'description' => 'Mediation services for business disputes. Facilitate communication between parties to reach a mutually acceptable resolution without going to court.',
            'category' => 'Mediation',
            'price' => 3500,
            'duration_minutes' => 120,
            'is_active' => true,
        ]);

        Service::create([
            'lsp_id' => $lsp3->id,
            'title' => 'Contract Dispute Resolution',
            'description' => 'Mediation for contract disputes. Help parties understand their contractual obligations and negotiate a fair resolution.',
            'category' => 'Mediation',
            'price' => 4000,
            'duration_minutes' => 150,
            'is_active' => true,
        ]);
    }
}