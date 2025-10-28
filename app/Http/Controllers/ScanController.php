<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $path = $file->store('temp');

        $fullPath = storage_path('app/' . $path);

        // 🔍 Run ClamAV
        $result = shell_exec("clamscan --no-summary " . escapeshellarg($fullPath));

        // 🧠 Determine status
        $isClean = !str_contains($result, "FOUND");

        // Delete temp file
        Storage::delete($path);

        return response()->json(['clean' => $isClean]);
    }
}
