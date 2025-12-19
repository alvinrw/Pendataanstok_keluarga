<?php
$admin = App\Models\Admin::where('username', 'alvin')->first();
if ($admin) {
    $admin->role = 'admin';
    $admin->save();
    echo "Updated role to admin.";
} else {
    echo "User not found.";
}
