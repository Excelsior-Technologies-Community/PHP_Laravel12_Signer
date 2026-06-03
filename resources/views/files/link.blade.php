<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Download Link - {{ $file->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white p-8 rounded-2xl shadow-xl text-center">
        <h2 class="text-3xl font-bold text-green-600 mb-4">Link Generated 🎉</h2>
        
        <p class="text-gray-700 mb-2 font-semibold truncate px-4">{{ $file->name }}</p>
        <p class="text-sm text-red-500 mb-6 bg-red-50 py-1 px-3 rounded-full inline-block font-medium">
            ⚠️ Valid for 10 minutes only
        </p>

        <a href="{{ $signedUrl }}" class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-bold shadow-lg">
            Download File
        </a>

        <div class="mt-8">
            <label class="block text-sm font-medium text-gray-500 mb-2">Copy Shared URL:</label>
            <div class="flex gap-2">
                <input type="text" id="linkInput" value="{{ $signedUrl }}" readonly class="w-full border p-2 rounded text-sm bg-gray-50 text-gray-600">
                <button onclick="copyLink()" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Copy</button>
            </div>
        </div>

        <div class="mt-8 border-t pt-6">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition">← Back to Files</a>
        </div>
    </div>

    <script>
        function copyLink() {
            const input = document.getElementById('linkInput');
            input.select();
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        }
    </script>
</body>
</html>