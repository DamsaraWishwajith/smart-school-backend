<?php
// Run this from: c:\Users\MSI\Desktop\Narme\smart-school-backend
// Command: php create_admin.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

// Create Admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@school.com'],
    [
        'name'     => 'School Admin',
        'password' => Hash::make('admin123'),
        'role'     => 'admin',
    ]
);
echo "✅ Admin user created/updated:\n";
echo "   Email:    admin@school.com\n";
echo "   Password: admin123\n\n";

// Create a Teacher user
$teacher = User::updateOrCreate(
    ['email' => 'teacher@school.com'],
    [
        'name'     => 'John Teacher',
        'password' => Hash::make('teacher123'),
        'role'     => 'teacher',
    ]
);
// Ensure teacher profile exists
Teacher::updateOrCreate(
    ['user_id' => $teacher->id],
    [
        'employee_id'             => 'TCH' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT),
        'qualification'           => 'B.Ed',
        'subject_specialization'  => 'Mathematics',
    ]
);
echo "✅ Teacher user created/updated:\n";
echo "   Email:    teacher@school.com\n";
echo "   Password: teacher123\n\n";

echo "🎉 Done! Now go to http://192.168.8.184:8000/admin and login.\n";
