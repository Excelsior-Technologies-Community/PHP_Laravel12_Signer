# PHP_Laravel12_Signer

## Introduction

PHP_Laravel12_Signer is a demonstration project built with Laravel 12 that showcases how to implement secure, temporary signed URLs using the Spatie laravel-url-signer package.

Signed URLs provide a secure way to grant temporary access to resources such as file downloads. Each generated link contains a cryptographic signature and optional expiration time, ensuring that:

- The URL cannot be tampered with

- The link expires automatically

- Unauthorized access is prevented

This project demonstrates a real-world use case: securely downloading files stored in Laravel’s storage directory.

---

## Project Overview

This application allows users to:

- View a list of files stored in the database

- Generate a temporary signed URL for any file

- Download the file only if the URL is valid and not expired

- Automatically reject modified or expired links with a 403 error

---

## Requirements

- PHP 8.2+
- Composer
- Laravel 12
- MySQL / SQLite

---

## How It Works

- Files are stored in storage/app/public

- File metadata is stored in the database

- When a user clicks Generate Signed URL, a temporary signed link is created

- The download route validates the signature before allowing access

- If the signature is invalid or expired → Laravel returns 403 Forbidden

---

## Step 1: Create Laravel 12 Project

Open terminal and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Signer "12.*"
cd PHP_Laravel12_Signer
php artisan serve
```

This creates a fresh Laravel 12 project.

---

## Step 2: Install Required Package

Install Spatie Laravel URL Signer:

```bash
composer require spatie/laravel-url-signer
```

---

## Step 3: Publish Configuration

Run this command:

```bash
php artisan vendor:publish --tag="url-signer-config"
```

Add signature key in .env:

```bash
URL_SIGNER_SIGNATURE_KEY=base64:YOUR_APP_KEY_HERE
```

---

## Step 4: Create Database Migration & Model for Files

### Create migration:

```bash
php artisan make:migration create_files_table --create=files
```

Edit migration (database/migrations/xxxx_create_files_table.php):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');   // File name
            $table->string('path');   // File path in storage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
```

Run migration:

```bash
php artisan migrate
```

### Create Model:

```bash
php artisan make:model File
```

app/Models/File.php:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'path'];
}
```

---

## Step 5: Add your PDF to storage

Go to your Laravel project:

```bash
storage\app\public
```
Copy your PDF file here and rename it to something consistent with your database File record. For example:

```
sample.pdf
```

Make sure this matches the path column in your files table.

---

## Step 6: Create storage symbolic link

Run this command in your Laravel project root:

```bash
php artisan storage:link
```

This will create a link:

```
public/storage -> storage/app/public
```
This ensures Laravel can access files in storage/app/public.

---

## Step 7: Seed the Database 

Add a sample file record in database/seeders/DatabaseSeeder.php:

```php
use App\Models\File;

File::create([
    'name' => 'Sample File',
    'path' => 'sample.pdf', // relative to storage/app/public
]);
```

Run seeder:

```bash
php artisan db:seed
```

---

## Step 8: Create Controller

```bash
php artisan make:controller FileController
```

app/Http/Controllers/FileController.php:

```php
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
```

---

## Step 9: Update Routes

routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;


Route::get('/', [FileController::class, 'index']);
Route::get('/generate/{file}', [FileController::class, 'generateSignedUrl']);
Route::get('/download/file/{file}', [FileController::class, 'download']);
```

---

## Step 10: Blade Files

### index.blade.php

resources/views/files/index.blade.php:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel URL Signer - Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Header -->
    <header class="bg-blue-600 text-white py-8 shadow-md">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold tracking-wide">Secure File Downloads</h1>
            <p class="mt-2 text-lg">Generate signed URLs for your files</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto mt-12 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($files as $file)
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $file->name }}</h2>
                    <p class="text-gray-500 mb-4">File ID: {{ $file->id }}</p>
                </div>
                <a href="{{ url('/generate/'.$file->id) }}" 
                   class="mt-auto inline-block text-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition-colors duration-300">
                    Generate Signed URL
                </a>
            </div>
            @endforeach
        </div>
    </main>

</body>
</html>
```

### link.blade.php

resources/views/files/link.blade.php:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signed URL Generated</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Header -->
    <header class="bg-blue-600 text-white py-8 shadow-md">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold tracking-wide">Secure File Download</h1>
            <p class="mt-2 text-lg">Your signed URL is ready</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto mt-12 px-4 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Signed URL Generated</h2>
            
            <!-- File Name -->
            <p class="text-xl text-blue-600 font-semibold mb-6">{{ $file->name }}</p>
            
            <!-- Info -->
            <p class="text-gray-500 mb-4">This link is valid for 10 minutes only.</p>

            <!-- Download Button -->
            <a href="{{ $signedUrl }}" 
               class="inline-block px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition-colors duration-300">
                Download File
            </a>
        </div>
    </main>

</body>
</html>
```

---

## Step 11: Test the Application

Start server:

```bash
php artisan serve
```

Go to http://127.0.0.1:8000 → see list of files → generate signed URL → download.

Modifying URL will give 403 Invalid or expired URL. 

---

## Output

<img width="1919" height="1033" alt="Screenshot 2026-02-27 110141" src="https://github.com/user-attachments/assets/ba95fcdf-7f93-42e4-881c-7680cb11fc33" />

<img width="1919" height="1025" alt="Screenshot 2026-02-27 110150" src="https://github.com/user-attachments/assets/b0b4da69-6a16-4aa3-8fc6-1ab874b511de" />

---

## Project Structure

```
PHP_Laravel12_Signer/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── FileController.php
│   │
│   └── Models/
│       └── File.php
│
├── config/
│   └── url-signer.php
│
├── resources/
│   └── views/
│       └── files/
│           ├── index.blade.php
│           └── link.blade.php
│
├── routes/
│   └── web.php
│
├── storage/
│   └── app/
│       └── public/
│           └── sample.pdf
│
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── .env
├── composer.json
└── README.md
```

---

Your PHP_Laravel12_Signer Project is now ready!

<<<<<<< HEAD

=======
>>>>>>> development
