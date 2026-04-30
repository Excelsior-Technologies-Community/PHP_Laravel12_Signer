<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload File</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-xl mx-auto mt-16 bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">
            Upload PDF File
        </h2>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/store" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-semibold">File Name</label>
                <input type="text" name="name" 
                    class="w-full border p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter file name" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Upload PDF</label>
                <input type="file" name="file" 
                    class="w-full border p-2 rounded bg-gray-50" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                Upload File
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/" class="text-gray-600 hover:underline">← Back to Files</a>
        </div>
    </div>

</body>
</html>