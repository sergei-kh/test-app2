<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Основной', 'Запасной', 'Новый'];
        foreach ($data as $datum) {
            $storage = new Storage();
            $storage->name = $datum;
            $storage->save();
        }
    }
}
