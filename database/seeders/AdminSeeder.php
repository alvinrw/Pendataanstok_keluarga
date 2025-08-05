<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash; // Lebih baik pakai Hash facade

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menggunakan updateOrCreate untuk memastikan konsistensi data.
        // Argumen pertama adalah atribut untuk mencari data.
        // Argumen kedua adalah data yang akan diisi atau diperbarui.

        // Membuat user 'ayah' dengan ID 1 dan role 'user'
        Admin::updateOrCreate(
            ['id' => 1], // Cari user dengan ID 1
            [
                'username' => 'ayah',
                'password' => Hash::make('ayah123'), // Gunakan Hash::make() yang lebih modern
                'role'     => 'user'
            ]
        );

        // Membuat user 'alvin' dengan ID 2 dan role 'admin'
        Admin::updateOrCreate(
            ['id' => 2], // Cari user dengan ID 2
            [
                'username' => 'alvin',
                'password' => Hash::make('alvin123'),
                'role'     => 'admin'
            ]
        );

        // Membuat user 'mama' dengan ID 5 (sesuai screenshot Anda) dan role 'user'
        Admin::updateOrCreate(
            ['id' => 5], // Cari user dengan ID 5
            [
                'username' => 'mama',
                'password' => Hash::make('mama123'),
                'role'     => 'user'
            ]
        );

        // Anda bisa menambahkan user lain di sini jika perlu
        // Contoh: user 'admin' (yang di screenshot Anda ID-nya 6)
        Admin::updateOrCreate(
            ['id' => 6], // Cari user dengan ID 6
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'), // Ganti password sesuai kebutuhan
                'role'     => 'user'
            ]
        );
    }
}