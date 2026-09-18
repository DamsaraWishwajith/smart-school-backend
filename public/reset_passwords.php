<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();
$updated = 0;
foreach($users as $user) {
    $user->password = Hash::make('123456');
    $user->save();
    $updated++;
}

echo json_encode(["status" => "success", "message" => "All $updated user passwords have been reset to: 123456"]);
