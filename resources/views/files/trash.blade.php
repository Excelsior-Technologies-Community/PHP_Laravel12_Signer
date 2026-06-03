<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trash Files</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-xl">
        <h2 class="text-3xl font-bold mb-8 text-center text-red-600">Trash Files 🗑️</h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 mb-6 rounded-lg text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($files->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <p class="text-6xl mb-4">📭</p>
                <p>Trash is empty</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($files as $file)
                    <div class="flex justify-between items-center border-b pb-4 hover:bg-gray-50 transition p-2 rounded">
                        <div>
                            <p class="font-bold text-gray-800">{{ $file->name }}</p>
                            <p class="text-xs text-gray-400">Deleted at: {{ $file->deleted_at?->format('d M, Y') }}</p>
                        </div>

                        <div class="flex gap-2">
                            <form action="/restore/{{ $file->id }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm">
                                    Restore
                                </button>
                            </form>

                            <form action="/force-delete/{{ $file->id }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                                    Delete Forever
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-10 pt-6 border-t">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition font-medium">← Back to Files</a>
        </div>
    </div>

</body>
</html>