<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Employeeprofiles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ApplicantController extends Controller
{
    /**
     * Show application form
     */
    public function showForm()
    {
        return view('Applicants.Applicationform');
    }

    /**
     * Store applicant data
     */
    public function store(Request $request)
{
    // ===============================
    //  VALIDATION RULES
    // ===============================
    $rules = $this->validationRules();
    $validated = $request->validate($rules);

    // ===============================
    //  STEP 1: Virus scan before storing
    // ===============================
    $filesToScan = ['good_moral_file', 'coe_file', 'resume_file'];

    foreach ($filesToScan as $fileKey) {
        if ($request->hasFile($fileKey)) {
            $path = $request->file($fileKey)->getRealPath();

            if (!$this->scanWithClamAV($path)) {
                return back()->with('error', ucfirst(str_replace('_', ' ', $fileKey)) . ' appears to be infected and was not uploaded.');
            }
        }
    }

    // ===============================
    //  STEP 2: Show Terms & Conditions
    // ===============================
    // ===============================
//  STEP 2: Show Terms & Conditions
// ===============================
$termsMessage = "
By submitting this application, you acknowledge that 3RS Air-Conditioning Solution, being a small business with fewer than 10 employees, is allowed to not acquire to provide mandatory government benefits such as SSS, PhilHealth, or Pag-IBIG contributions. 
This policy is in accordance with applicable labor regulations.
";

// Flash the message to the session to display in the view
session()->flash('terms_message', $termsMessage);


    // ===============================
    //  STEP 3: Store files
    // ===============================
    foreach ($filesToScan as $fileKey) {
        if ($request->hasFile($fileKey)) {
            $path = $request->file($fileKey)->store('applicants_files', 'public');
            $validated[$fileKey] = asset('storage/' . $path);
        }
    }

    // ===============================
    //  STEP 4: Save applicant record
    // ===============================
    unset($validated['terms_accepted'], $validated['policy_accepted']);
    Applicant::create($validated);

    return redirect()
        ->route('show.applicationform')
        ->with('success', 'Application submitted successfully and files are virus-free!');
}


    /**
     * Real-time AJAX validation for individual fields
     */
    public function validateField(Request $request)
    {
        $field = $request->get('field');
        $value = $request->get('value');

        $fullRules = $this->validationRules();

        $rule = $fullRules[$field] ?? 'nullable';

        $validator = Validator::make(
            [$field => $value],
            [$field => $rule]
        );

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first($field),
            ]);
        }

        return response()->json(['valid' => true]);
    }

    /**
     * Shared reusable validation rules
     */
    private function validationRules()
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'contact_number'    => 'required|string|max:20|unique:applicants,contact_number',
            
            // EMAIL MUST BE UNIQUE IN BOTH TABLES
            'email' => [
                'required',
                'email',
                'max:150',
                'unique:applicants,email',
                'unique:employeeprofiles,email'
            ],

            'address'           => 'required|string',
            'date_of_birth'     => 'required|date',
            'emergency_contact' => 'required|string|max:150',
            'position'          => 'required|in:Helper,Assistant Technician,Technician,Human Resource Manager,Administrative Manager,Finance Manager',
            
            // optional fields
            'career_objective' => 'nullable|string',
            'work_experience'  => 'nullable|string',
            'education'        => 'nullable|string',
            'skills'           => 'nullable|string',
            'achievements'     => 'nullable|string',
            'references'       => 'nullable|string',

            // FILES
            'good_moral_file' => 'nullable|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
            'coe_file'        => 'nullable|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
            'resume_file'     => 'required|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
            
            'terms_accepted'  => 'accepted',
            'policy_accepted' => 'accepted',
        ];
    }

    /**
     * ClamAV scanning function
     */
    private function scanWithClamAV($filePath)
    {
        $clamPath = 'D:\\ClamAV\\clamdscan.exe';

        $command = "$clamPath --fdpass " . escapeshellarg($filePath);

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            return true; // Clean
        }

        // Log infection
        Log::warning('ClamAV detected a threat', [
            'file' => $filePath,
            'output' => $output,
            'returnCode' => $returnCode,
        ]);

        return false;
    }
}
