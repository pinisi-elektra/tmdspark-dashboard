<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__ . '/cities.json');
        $datas = json_decode($json);
        City::truncate();
        foreach($datas->RECORDS as $data) {
            City::insert([
                'province_id' => $data->id_cms_provinsis,
                'name' => $data->name,
                'created_at' => now()
            ]);
        }
    }
}
