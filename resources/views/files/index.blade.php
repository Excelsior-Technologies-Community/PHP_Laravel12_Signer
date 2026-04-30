<!DOCTYPE html>
<html>

<head>
    <title>Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto mt-10">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">📁 Files Manager</h2>

            <div class="space-x-2">
                <a href="/create" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    + Upload
                </a>

                <a href="/trash" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Trash
                </a>
            </div>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- SEARCH BAR -->
        <form method="GET" class="mb-6">
            <div class="flex">
                <input type="text" name="search" placeholder="🔍 Search file..."
                    class="w-full border p-3 rounded-l focus:outline-none">

                <button type="submit" class="bg-blue-500 text-white px-6 rounded-r hover:bg-blue-600">
                    Search
                </button>
            </div>
        </form>

        <!-- FILE LIST -->
        <div class="bg-white rounded shadow p-4">

            @if($files->count() == 0)
                <div class="text-center text-gray-500 py-10">
                    📂 No files found
                </div>
            @endif

            @foreach($files as $file)
                <div class="flex justify-between items-center border-b py-3 hover:bg-gray-50 transition">

                    <!-- FILE INFO -->
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $file->name }}
                        </p>
                        <p class="text-sm text-gray-400">
                            ID: {{ $file->id }}
                        </p>
                    </div>

                    <!-- ACTIONS -->
                    <div class="space-x-2">

                        <a href="/generate/{{ $file->id }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                            Generate Link
                        </a>

                        <a href="/delete/{{ $file->id }}" onclick="return confirm('Are you sure to delete this file?')"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                            Delete
                        </a>

                    </div>

                </div>
            @endforeach

        </div>

        <!-- PAGINATION -->
        <div class="mt-6">
            {{ $files->links() }}
        </div>

    </div>

</body>

</html>