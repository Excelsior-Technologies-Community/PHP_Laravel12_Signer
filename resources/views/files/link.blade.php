<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signed URL</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-lg mx-auto mt-20 bg-white p-8 rounded-xl shadow-lg text-center">

        <h2 class="text-3xl font-bold text-green-600 mb-4">
            Link Generated 🎉
        </h2>

        <p class="text-gray-700 mb-2 font-semibold">
            {{ $file->name }}
        </p>

        <p class="text-sm text-gray-500 mb-6">
            This link is valid for 10 minutes only
        </p>

        <!-- Download Button -->
        <a href="{{ $signedUrl }}"
            class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
            Download File
        </a>

        <!-- Copy Link -->
        <div class="mt-6">
            <input type="text" value="{{ $signedUrl }}" readonly class="w-full border p-2 rounded text-sm">
        </div>

        <div class="mt-6">
            <a href="/" class="text-gray-600 hover:underline">
                ← Back to Files
            </a>
        </div>

    </div>

</body>

</html>