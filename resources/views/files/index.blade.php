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