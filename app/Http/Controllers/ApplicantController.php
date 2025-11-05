<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ApplicantController extends Controller
{
    public function showForm()
    {
        return view('Applicants.Applicationform');
    }

public function store(Request $request)
{
    // ✅ Validate request inputs
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
        'good_moral_file'   => 'required|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
        'coe_file'          => 'required|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
        'resume_file'       => 'required|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
    ]);

    // 🧩 Files to process
    $filesToScan = ['good_moral_file', 'coe_file', 'resume_file'];

    // 🦠 Step 1: Virus scan before storing
    foreach ($filesToScan as $fileKey) {
        if ($request->hasFile($fileKey)) {
            $path = $request->file($fileKey)->getRealPath();

            // Scan file using ClamAV
            if (!$this->scanWithClamAV($path)) {
                return back()->with('error', ucfirst(str_replace('_', ' ', $fileKey)) . ' appears to be infected and was not uploaded.');
            }
        }
    }

    // 📂 Step 2: Store files and generate full URLs
    foreach ($filesToScan as $fileKey) {
        if ($request->hasFile($fileKey)) {
            // Store file under "storage/app/public/applicants_files"
            $path = $request->file($fileKey)->store('applicants_files', 'public');

            // Convert to a full URL (e.g., http://3rs-erp.test/storage/applicants_files/file.jpg)
            $validated[$fileKey] = asset('storage/' . $path);
        }
    }

    // 🧾 Step 3: Save applicant record
    Applicant::create($validated);

    // 🎉 Step 4: Redirect with success message
    return redirect()
        ->route('show.applicationform')
        ->with('success', 'Application submitted successfully and files are virus-free!');
}


// 🔍 Helper method for scanning
private function scanWithClamAV($filePath)
{
    // Path to your ClamAV scanner
    $clamPath = 'D:\\ClamAV\\clamdscan.exe';

    // Run scan
    $command = "$clamPath --fdpass " . escapeshellarg($filePath);
    exec($command, $output, $returnCode);

    // 0 = clean, 1 = infected, 2 = error
    if ($returnCode === 0) {
        return true;
    }

    Log::warning('ClamAV detected a threat', [
        'file' => $filePath,
        'output' => $output,
        'returnCode' => $returnCode,
    ]);

    return false;
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
