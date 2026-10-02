<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users with different roles
        $superAdmin = User::create([
            'name' => 'Super Admin Geprek',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('Password123'),
            'role' => 'super_admin',
        ]);

        $seller1 = User::create([
            'name' => 'Penjual Geprek Bestie',
            'email' => 'seller@gmail.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
        ]);

        $seller2 = User::create([
            'name' => 'Seller Dua',
            'email' => 'seller2@gmail.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
        ]);

        $regularUser = User::create([
            'name' => 'Pengguna Geprek',
            'email' => 'user@gmail.com',
            'password' => Hash::make('Password123'),
            'role' => 'user',
        ]);

        // 2. Create Stores
        $store1 = Store::create([
            'user_id' => $seller1->id,
            'nama_toko' => 'Geprek Bestie Cabang Bengkalis',
            'alamat' => 'Jl. Bathin Alam, Sungai Alam, Kec. Bengkalis, Kabupaten Bengkalis, Riau 28711',
            'whatsapp' => '6283117071309',
            'latitude' => 1.46749,
            'longitude' => 102.12874,
            'gambar' => 'stores/26mMDmxXs0yiLXpAQ1LvxeLMkKfoxA9QhMcE7HYc.jpg',
        ]);

        $store2 = Store::create([
            'user_id' => $seller2->id,
            'nama_toko' => 'Ayam Geprek Juara Senggoro',
            'alamat' => 'Jl. Senggoro, Kec. Bengkalis, Kabupaten Bengkalis, Riau 28711',
            'whatsapp' => '6281234567890',
            'latitude' => 1.47250,
            'longitude' => 102.13850,
            'gambar' => 'stores/CzxBbv3zehx83aTtffUslJtRDFmK5YJFaobmzwWd.jpg',
        ]);

        // 3. Create Menus for Store 1
        Menu::create([
            'store_id' => $store1->id,
            'nama_menu' => 'Ayam Geprek Sambal Korek + Nasi',
            'harga' => 15000,
            'gambar' => 'menus/1OAbL30YwHPh7lGWkevOSeefuWeTNkTzFtYWa8jW.jpg',
        ]);

        Menu::create([
            'store_id' => $store1->id,
            'nama_menu' => 'Ayam Geprek Mozzarella',
            'harga' => 20000,
            'gambar' => 'menus/XgfXDT5ntyTsPL6TxiDYfxiX4Yogy7ZMkFyBPPUz.jpg',
        ]);

        Menu::create([
            'store_id' => $store1->id,
            'nama_menu' => 'Ayam Geprek Sambal Matah',
            'harga' => 17000,
            'gambar' => null,
        ]);

        Menu::create([
            'store_id' => $store1->id,
            'nama_menu' => 'Es Teh Manis Jumbo',
            'harga' => 5000,
            'gambar' => null,
        ]);

        // Create Menus for Store 2
        Menu::create([
            'store_id' => $store2->id,
            'nama_menu' => 'Ayam Geprek Cabe Hijau',
            'harga' => 16000,
            'gambar' => null,
        ]);

        Menu::create([
            'store_id' => $store2->id,
            'nama_menu' => 'Paket Hemat Geprek Mie Goreng',
            'harga' => 18000,
            'gambar' => null,
        ]);

        // 4. Create Reviews
        Review::create([
            'user_id' => $regularUser->id,
            'store_id' => $store1->id,
            'rating' => 5,
            'komentar' => 'Ayam gepreknya luar biasa enak! Sambal koreknya pedas nagih dan ayamnya crispy sekali. Sangat direkomendasikan!',
        ]);

        Review::create([
            'user_id' => $regularUser->id,
            'store_id' => $store2->id,
            'rating' => 4,
            'komentar' => 'Ayam geprek cabe ijo mantap, rasanya khas. Harga juga bersahabat dengan kantong mahasiswa.',
        ]);
    }
}
