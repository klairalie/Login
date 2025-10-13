<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Facades\Validator;

class ApplicantController extends Controller
{
    public function showForm()
    {
        return view('Applicants.Applicationform');
    }

     public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'        => 'required|string|max:100',
        'last_name'         => 'required|string|max:100',
        'contact_number'    => 'required|string|max:20|unique:applicants,contact_number',
        'email'             => 'required|email|max:150|unique:applicants,email',
        'address'           => 'required|string',
        'date_of_birth'     => 'required|date',
        'emergency_contact' => 'required|string|max:150',
        'position'          => 'required|in:Helper,Assistant Technician,Technician,Human Resource Manager,Administrative Manager,Finance Manager',
        'career_objective'  => 'required|string',
        'work_experience'   => 'required|string',
        'education'         => 'required|string',
        'skills'            => 'required|string',
        'achievements'      => 'required|string',
        'references'        => 'required|string',
        'good_moral_file'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'coe_file'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'resume_file'       => 'required|file|mimes:jpg,jpeg,png,doc,docx|max:5120', // ✅ allow docs & larger size
]);

// Handle uploads
if ($request->hasFile('good_moral_file')) {
    $validated['good_moral_file'] = $request->file('good_moral_file')
        ->store('applicants_files', 'public');
}

if ($request->hasFile('coe_file')) {
    $validated['coe_file'] = $request->file('coe_file')
        ->store('applicants_files', 'public');
}

if ($request->hasFile('resume_file')) {
    $validated['resume_file'] = $request->file('resume_file')
        ->store('applicants_files', 'public');
}


    Applicant::create($validated);

    return redirect()->route('show.applicationform')->with('success', 'Application submitted successfully!');
}

public function validateField(Request $request)
{
    $field = $request->get('field');
    $value = $request->get('value');

    $rules = [
        'first_name'        => 'required|string|max:100',
        'last_name'         => 'required|string|max:100',
        'contact_number'    => 'required|string|max:20|unique:applicants,contact_number',
        'email'             => 'required|email|max:150|unique:applicants,email',
        'address'           => 'required|string',
        'date_of_birth'     => 'required|date',
        'emergency_contact' => 'required|string|max:150',
        'position'          => 'required|in:Helper,Assistant Technician,Technician,Human Resource Manager,Administrative Manager,Finance Manager',
        'career_objective'  => 'required|string',
        'work_experience'   => 'required|string',
        'education'         => 'required|string',
        'skills'            => 'required|string',
        'achievements'      => 'required|string',
        'references'        => 'required|string',
    ];

    $validator = Validator::make(
        [$field => $value],
        [$field => $rules[$field] ?? 'nullable']
    );

    if ($validator->fails()) {
        return response()->json(['valid' => false, 'message' => $validator->errors()->first($field)]);
    }
    return response()->json(['valid' => true]);
}


}
