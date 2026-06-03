<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use UrlSigner;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $query = File::whereNull('parent_id');

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $files = $query->orderBy('created_at', 'asc')->paginate(10);
        
        $totalSize = File::sum('size');
        $diskUsage = number_format($totalSize / 1024 / 1024, 2) . ' MB';

        return view('files.index', compact('files', 'diskUsage'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,jpg,png,zip,webp',
            'parent_id' => 'nullable|exists:files,id'
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('files', 'public');
                
                File::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'parent_id' => $request->parent_id
                ]);
            }
        }

        return redirect('/')->with('success', 'Files uploaded successfully');
    }

    public function generateSignedUrl(File $file)
    {
        $signedUrl = UrlSigner::sign(
            url("/download/file/{$file->id}"),
            now()->addMinutes(10)
        );

        return view('files.link', compact('signedUrl', 'file'));
    }

    public function download(File $file, Request $request)
    {
        if (!UrlSigner::validate($request->fullUrl())) {
            abort(403, 'Invalid or expired URL');
        }

        return response()->download(storage_path("app/public/{$file->path}"));
    }

    public function destroy($id)
    {
        File::findOrFail($id)->delete();
        return redirect('/')->with('success', 'File Moved to Trash');
    }

    public function trash()
    {
        $files = File::onlyTrashed()->get();
        return view('files.trash', compact('files'));
    }

    public function restore($id)
    {
        File::withTrashed()->find($id)->restore();
        return redirect('/')->with('success', 'File Restored');
    }

    public function forceDelete($id)
    {
        $file = File::withTrashed()->find($id);
        if ($file->path) {
            Storage::disk('public')->delete($file->path);
        }
        $file->forceDelete();
        return back()->with('success', 'File Deleted Permanently');
    }
}