<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            ['name'  => 'Limpieza Domicilio'],
            ['name'  => 'Lavado vehículo'],
        );

        foreach ($items as $item) {
            DB::table('services_types')->insert([
                'name'          => $item['name']
            ]);
        }
    }
}
