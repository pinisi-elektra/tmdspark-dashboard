<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__ . '/provinces.json');
        $datas = json_decode($json);
        Province::truncate();
        foreach($datas->RECORDS as $data) {
            Province::insert([
                'name' => $data->name,
                'created_at' => now()
            ]);
        }
    }
}
