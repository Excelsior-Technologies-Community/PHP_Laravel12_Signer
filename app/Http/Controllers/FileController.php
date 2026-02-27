<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use UrlSigner; // Facade
use App\Models\File;

class FileController extends Controller
{
    // Show list of all files
    public function index()
    {
        $files = File::all(); // Get all files from DB
        return view('files.index', compact('files'));
    }

    // Generate signed URL for a specific file
    public function generateSignedUrl(File $file)
    {
        // Generate signed URL for download route, valid for 10 minutes
        $signedUrl = UrlSigner::sign(url("/download/file/{$file->id}"), now()->addMinutes(10));

        return view('files.link', [
            'signedUrl' => $signedUrl,
            'file' => $file, // Pass file to view
        ]);
    }

    // Download file if URL is valid
    public function download(File $file, Request $request)
    {
        // Validate signed URL
        if (! UrlSigner::validate($request->fullUrl())) {
            abort(403, 'Invalid or expired URL.');
        }

        return response()->download(storage_path("app/public/{$file->path}"));
    }
}