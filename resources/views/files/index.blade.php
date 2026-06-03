<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Files Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Image lightbox overlay */
        #lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85);
                    z-index:1000; align-items:center; justify-content:center; }
        #lightbox.open { display:flex; }
        #lightbox img { max-width:90vw; max-height:85vh; border-radius:12px;
                        box-shadow:0 25px 60px rgba(0,0,0,0.5); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

{{-- ========== LIGHTBOX ========== --}}
<div id="lightbox" onclick="closeLightbox()">
    <div class="relative">
        <img id="lightbox-img" src="" alt="Preview">
        <button onclick="closeLightbox()"
            class="absolute -top-4 -right-4 bg-white text-gray-800 rounded-full w-9 h-9 text-xl font-bold shadow hover:bg-red-500 hover:text-white transition">
            ×
        </button>
        <p id="lightbox-name" class="text-center text-white text-sm mt-3 opacity-80"></p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📁 Files Manager</h1>
            <p class="text-sm text-gray-500 mt-1">
                Storage used: <span class="font-semibold text-blue-600">{{ $diskUsage ?? '0 MB' }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="/create" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-medium transition shadow">
                + Upload
            </a>
            <a href="/trash" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-medium transition shadow">
                🗑 Trash
            </a>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" class="mb-8">
        <div class="flex rounded-lg overflow-hidden shadow-sm border border-gray-200">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="🔍 Search files..."
                class="w-full px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 font-medium transition">
                Search
            </button>
        </div>
    </form>

    {{-- Files grid --}}
    @if($files->isEmpty())
        <div class="text-center py-24 text-gray-400">
            <p class="text-6xl mb-4">📂</p>
            <p class="text-lg">No files found</p>
        </div>
    @else

    {{-- Grid view --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($files as $file)
        @php
            $ext       = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
            $isImage   = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            $isPdf     = $ext === 'pdf';
            $isZip     = in_array($ext, ['zip', 'rar', '7z']);
            $isFolder  = $file->parent_id !== null;
            $imgUrl    = asset('storage/' . $file->path);
            $sizeKb    = number_format($file->size / 1024, 1);
        @endphp

        <div class="bg-white rounded-xl shadow hover:shadow-md transition group overflow-hidden border border-gray-100">

            {{-- Thumbnail area --}}
            <div class="relative bg-gray-50 h-36 flex items-center justify-center overflow-hidden">

                @if($isImage)
                    {{-- Real image preview --}}
                    <img src="{{ $imgUrl }}" alt="{{ $file->name }}"
                        class="w-full h-full object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                        onclick="openLightbox('{{ $imgUrl }}', '{{ $file->name }}')"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    {{-- Fallback if image fails --}}
                    <div class="hidden absolute inset-0 items-center justify-center text-5xl">🖼️</div>

                @elseif($isPdf)
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-5xl">📄</span>
                        <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded">PDF</span>
                    </div>

                @elseif($isZip)
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-5xl">🗜️</span>
                        <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">ZIP</span>
                    </div>

                @elseif($isFolder)
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-5xl">📁</span>
                        <span class="text-xs text-gray-500">Folder</span>
                    </div>

                @else
                    <span class="text-5xl">📎</span>
                @endif

                {{-- Extension badge top-right --}}
                <span class="absolute top-2 right-2 text-xs font-bold uppercase px-1.5 py-0.5 rounded
                    {{ $isImage ? 'bg-blue-500 text-white' : ($isPdf ? 'bg-red-500 text-white' : ($isZip ? 'bg-yellow-500 text-white' : 'bg-gray-400 text-white')) }}">
                    {{ $ext }}
                </span>
            </div>

            {{-- File info --}}
            <div class="p-3">
                <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $file->name }}">
                    {{ $file->name }}
                </p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $sizeKb }} KB</p>

                {{-- Action buttons --}}
                <div class="flex gap-1 mt-3">
                    @if($isImage)
                    <button onclick="openLightbox('{{ $imgUrl }}', '{{ $file->name }}')"
                        class="flex-1 text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 py-1.5 rounded-lg transition font-medium">
                        👁 View
                    </button>
                    @endif

                    <a href="/generate/{{ $file->id }}"
                        class="flex-1 text-xs bg-green-50 hover:bg-green-100 text-green-700 py-1.5 rounded-lg transition font-medium text-center">
                        🔗 Link
                    </a>

                    <form action="/delete/{{ $file->id }}" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Move to trash?')"
                            class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-1.5 rounded-lg transition font-medium">
                            🗑
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- List view for non-image files info --}}
    <div class="mt-8 bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">All Files — Detail View</h3>
        </div>
        @foreach($files as $file)
        @php
            $ext     = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            $imgUrl  = asset('storage/' . $file->path);
            $sizeKb  = number_format($file->size / 1024, 1);
        @endphp
        <div class="flex items-center justify-between px-5 py-3 border-b last:border-0 hover:bg-gray-50 transition">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Mini thumbnail --}}
                @if($isImage)
                    <img src="{{ $imgUrl }}" alt="{{ $file->name }}"
                        class="w-10 h-10 rounded-lg object-cover border cursor-zoom-in flex-shrink-0"
                        onclick="openLightbox('{{ $imgUrl }}', '{{ $file->name }}')"
                        onerror="this.outerHTML='<span class=\'text-2xl\'>🖼️</span>'">
                @else
                    <span class="text-2xl flex-shrink-0">
                        {{ $ext === 'pdf' ? '📄' : ($ext === 'zip' ? '🗜️' : '📎') }}
                    </span>
                @endif

                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $file->name }}</p>
                    <p class="text-xs text-gray-400">
                        ID: {{ $file->id }} &nbsp;·&nbsp; {{ $sizeKb }} KB &nbsp;·&nbsp;
                        {{ $file->created_at->format('d M Y') }}
                        @if($file->parent_id)
                            &nbsp;·&nbsp; <span class="text-blue-500">child file</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex gap-2 flex-shrink-0 ml-4">
                <a href="/generate/{{ $file->id }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                    🔗 Link
                </a>
                <form action="/delete/{{ $file->id }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Move to trash?')"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                        🗑 Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @endif

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $files->appends(request()->query())->links() }}
    </div>

</div>

{{-- Lightbox JS --}}
<script>
    function openLightbox(src, name) {
        document.getElementById('lightbox-img').src  = src;
        document.getElementById('lightbox-name').textContent = name;
        document.getElementById('lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('open');
        document.getElementById('lightbox-img').src = '';
        document.body.style.overflow = '';
    }

    // ESC key thi pan band thay
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

</body>
</html>