<?php

// ================================================================
// TINKER SCRIPT — File Manager dummy data add karva mate
// Usage: php artisan tinker
// pachhi: require 'tinker_seed.php';
// ================================================================

use App\Models\File;
use Illuminate\Support\Facades\Storage;

// ----------------------------------------------------------------
// Step 1: Fake image files create karo storage/app/public/files/ ma
// ----------------------------------------------------------------

$disk = Storage::disk('public');

// Folder exist na kare to banavo
if (!$disk->exists('files')) {
    $disk->makeDirectory('files');
}

// Simple colored placeholder images (SVG as PNG-named files — browser render kare)
$images = [
    'photo_sunset.jpg' => '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
        <defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#FF6B35"/><stop offset="100%" stop-color="#F7C948"/></linearGradient></defs>
        <rect width="400" height="300" fill="url(#g)"/>
        <circle cx="200" cy="120" r="60" fill="#FFE066" opacity="0.9"/>
        <rect x="0" y="220" width="400" height="80" fill="#1a1a2e" opacity="0.7"/>
        <text x="200" y="270" text-anchor="middle" fill="white" font-size="18" font-family="Arial">Sunset Photo</text>
    </svg>',

    'nature_forest.jpg' => '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
        <rect width="400" height="300" fill="#87CEEB"/>
        <rect x="0" y="150" width="400" height="150" fill="#228B22"/>
        <polygon points="80,150 130,60 180,150" fill="#155724"/>
        <polygon points="160,150 220,50 280,150" fill="#1a7a2e"/>
        <polygon points="250,150 300,70 350,150" fill="#155724"/>
        <text x="200" y="290" text-anchor="middle" fill="white" font-size="18" font-family="Arial">Forest Photo</text>
    </svg>',

    'product_camera.jpg' => '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
        <rect width="400" height="300" fill="#2c3e50"/>
        <rect x="100" y="90" width="200" height="140" rx="20" fill="#34495e" stroke="#7f8c8d" stroke-width="3"/>
        <circle cx="200" cy="160" r="45" fill="#1a252f" stroke="#7f8c8d" stroke-width="4"/>
        <circle cx="200" cy="160" r="28" fill="#2980b9"/>
        <circle cx="200" cy="160" r="12" fill="#1a252f"/>
        <rect x="260" y="100" width="25" height="15" rx="4" fill="#e74c3c"/>
        <text x="200" y="270" text-anchor="middle" fill="white" font-size="18" font-family="Arial">Camera Product</text>
    </svg>',

    'banner_sale.png' => '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
        <defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="#667eea"/><stop offset="100%" stop-color="#764ba2"/></linearGradient></defs>
        <rect width="400" height="300" fill="url(#bg)"/>
        <text x="200" y="110" text-anchor="middle" fill="white" font-size="48" font-weight="bold" font-family="Arial">SALE</text>
        <text x="200" y="165" text-anchor="middle" fill="#FFE066" font-size="32" font-family="Arial">50% OFF</text>
        <rect x="60" y="190" width="280" height="2" fill="white" opacity="0.4"/>
        <text x="200" y="230" text-anchor="middle" fill="white" font-size="16" font-family="Arial">Limited Time Offer</text>
    </svg>',

    'profile_avatar.png' => '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
        <rect width="400" height="300" fill="#f0f4f8"/>
        <circle cx="200" cy="120" r="70" fill="#4A90D9"/>
        <circle cx="200" cy="105" r="35" fill="#fff"/>
        <ellipse cx="200" cy="175" rx="55" ry="30" fill="#fff"/>
        <text x="200" y="260" text-anchor="middle" fill="#333" font-size="18" font-family="Arial">Profile Avatar</text>
    </svg>',
];

// Non-image files (PDF, ZIP — fake content)
$otherFiles = [
    'report_2024.pdf'   => '%PDF-1.4 fake pdf content for demo',
    'project_files.zip' => 'PK fake zip content for demo',
    'data_backup.zip'   => 'PK fake zip backup content',
];

echo "Creating fake files in storage...\n";

foreach ($images as $filename => $svgContent) {
    $disk->put("files/{$filename}", $svgContent);
    echo "  ✓ Created: files/{$filename}\n";
}

foreach ($otherFiles as $filename => $content) {
    $disk->put("files/{$filename}", $content);
    echo "  ✓ Created: files/{$filename}\n";
}

// ----------------------------------------------------------------
// Step 2: Database records insert karo
// ----------------------------------------------------------------

echo "\nInserting database records...\n";

// Existing data saaf karo (fresh start)
File::withTrashed()->forceDelete();
echo "  ✓ Old records cleared\n";

// Root level files — images
$f1 = File::create([
    'name'      => 'photo_sunset.jpg',
    'path'      => 'files/photo_sunset.jpg',
    'size'      => 204800,   // 200 KB
    'parent_id' => null,
]);

$f2 = File::create([
    'name'      => 'nature_forest.jpg',
    'path'      => 'files/nature_forest.jpg',
    'size'      => 358400,   // 350 KB
    'parent_id' => null,
]);

$f3 = File::create([
    'name'      => 'product_camera.jpg',
    'path'      => 'files/product_camera.jpg',
    'size'      => 512000,   // 500 KB
    'parent_id' => null,
]);

$f4 = File::create([
    'name'      => 'banner_sale.png',
    'path'      => 'files/banner_sale.png',
    'size'      => 102400,   // 100 KB
    'parent_id' => null,
]);

$f5 = File::create([
    'name'      => 'profile_avatar.png',
    'path'      => 'files/profile_avatar.png',
    'size'      => 153600,   // 150 KB
    'parent_id' => null,
]);

// Non-image root files
$f6 = File::create([
    'name'      => 'report_2024.pdf',
    'path'      => 'files/report_2024.pdf',
    'size'      => 1048576,  // 1 MB
    'parent_id' => null,
]);

$f7 = File::create([
    'name'      => 'project_files.zip',
    'path'      => 'files/project_files.zip',
    'size'      => 2097152,  // 2 MB
    'parent_id' => null,
]);

// Child file (parent = photo_sunset.jpg)
$f8 = File::create([
    'name'      => 'data_backup.zip',
    'path'      => 'files/data_backup.zip',
    'size'      => 3145728,  // 3 MB
    'parent_id' => $f1->id,
]);

// One soft-deleted file (trash ma dikhshe)
$f9 = File::create([
    'name'      => 'old_logo.png',
    'path'      => 'files/profile_avatar.png', // reuse existing path
    'size'      => 51200,
    'parent_id' => null,
]);
$f9->delete(); // soft delete — trash ma jashe

echo "  ✓ {$f1->name} (ID: {$f1->id})\n";
echo "  ✓ {$f2->name} (ID: {$f2->id})\n";
echo "  ✓ {$f3->name} (ID: {$f3->id})\n";
echo "  ✓ {$f4->name} (ID: {$f4->id})\n";
echo "  ✓ {$f5->name} (ID: {$f5->id})\n";
echo "  ✓ {$f6->name} (ID: {$f6->id})\n";
echo "  ✓ {$f7->name} (ID: {$f7->id})\n";
echo "  ✓ {$f8->name} (ID: {$f8->id}) [child of {$f1->name}]\n";
echo "  ✓ {$f9->name} (ID: {$f9->id}) [soft deleted — trash]\n";

echo "\n========================================\n";
echo "  Done! Total: " . File::count() . " active files\n";
echo "  Trash:  " . File::onlyTrashed()->count() . " file\n";
echo "  Storage: " . collect(array_keys($images) + array_keys($otherFiles))->count() . " physical files\n";
echo "========================================\n";
echo "\n  Open: http://127.0.0.1:8000\n\n";