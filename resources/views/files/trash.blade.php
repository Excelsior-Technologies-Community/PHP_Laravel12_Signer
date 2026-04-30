<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trash Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto mt-12">

        <h2 class="text-3xl font-bold mb-6 text-center text-red-600">
            Trash Files 🗑️
        </h2>

        <!-- ✅ SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">

            @if($files->count() == 0)
                <p class="text-center text-gray-500">No files in trash</p>
            @endif

            @foreach($files as $file)
                <div class="flex justify-between items-center border-b py-3">

                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $file->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            ID: {{ $file->id }}
                        </p>
                    </div>

                    <div class="space-x-2">

                        <!-- RESTORE -->
                        <a href="/restore/{{ $file->id }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Restore
                        </a>

                        <!-- DELETE FOREVER -->
                        <a href="/force-delete/{{ $file->id }}"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Delete Forever
                        </a>

                    </div>

                </div>
            @endforeach

        </div>

        <div class="text-center mt-6">
            <a href="/" class="text-gray-600 hover:underline">
                ← Back to Files
            </a>
        </div>

    </div>

</body>

</html>