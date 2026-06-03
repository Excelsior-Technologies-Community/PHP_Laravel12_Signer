<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-xl mx-auto mt-16 bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Upload Files</h2>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 mb-4 rounded">{{ $errors->first() }}</div>
        @endif

        <form action="/store" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-semibold">Base Name (Optional)</label>
                <input type="text" name="name" class="w-full border p-3 rounded" placeholder="e.g. My Documents">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Select Parent Folder (Optional)</label>
                <select name="parent_id" class="w-full border p-3 rounded">
                    <option value="">-- Root Directory --</option>
                    @foreach(\App\Models\File::whereNull('parent_id')->get() as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Upload Files (Multiple)</label>
                <input type="file" name="files[]" multiple class="w-full border p-2 rounded bg-gray-50" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                Upload Files
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/" class="text-gray-600 hover:underline">← Back to Files</a>
        </div>
    </div>

</body>
</html>