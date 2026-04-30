<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use UrlSigner;

class FileController extends Controller
{
    // LIST + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = File::query();

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $files = $query->orderBy('created_at', 'asc')->paginate(3);
        return view('files.index', compact('files'));
    }

    // SHOW UPLOAD FORM
    public function create()
    {
        return view('files.create');
    }

    // STORE FILE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required|mimes:pdf'
        ]);

        $path = $request->file('file')->store('files', 'public');

        File::create([
            'name' => $request->name,
            'path' => $path
        ]);

        return redirect('/')->with('success', 'File uploaded successfully');
    }

    // GENERATE SIGNED URL
    public function generateSignedUrl(File $file)
    {
        $signedUrl = UrlSigner::sign(
            url("/download/file/{$file->id}"),
            now()->addMinutes(10)
        );

        return view('files.link', compact('signedUrl', 'file'));
    }

    // DOWNLOAD FILE
    public function download(File $file, Request $request)
    {
        if (!UrlSigner::validate($request->fullUrl())) {
            abort(403, 'Invalid or expired URL');
        }

        return response()->download(
            storage_path("app/public/{$file->path}")
        );
    }

    // SOFT DELETE
    public function destroy($id)
    {
        File::findOrFail($id)->delete();

        return redirect('/')->with('success', 'File Moved to Trash successfully');
    }

    // TRASH
    public function trash()
    {
        $files = File::onlyTrashed()->get();
        return view('files.trash', compact('files'));
    }

    // RESTORE
    public function restore($id)
    {
        File::withTrashed()->find($id)->restore();
        return redirect('/')->with('success', 'File Restore successfully');

    }

    // FORCE DELETE
    public function forceDelete($id)
    {
        File::withTrashed()->find($id)->forceDelete();
        return back();
    }
}