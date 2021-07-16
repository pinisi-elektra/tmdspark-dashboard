<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__ . '/districts.json');
        $datas = json_decode($json);
        District::truncate();
        foreach($datas->RECORDS as $data) {
            District::insert([
                'province_id' => $data->id_cms_provinsis,
                'city_id' => $data->id_cms_kabupatens,
                'name' => $data->name,
                'created_at' => now()
            ]);
        }
    }
}
