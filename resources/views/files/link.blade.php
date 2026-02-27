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