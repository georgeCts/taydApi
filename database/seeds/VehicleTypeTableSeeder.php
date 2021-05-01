<?php

use Illuminate\Database\Seeder;

class VehicleTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            [
                'name'  => 'Auto compacto',
            ],
            [
                'name'  => 'SUV'
            ],
            [
                'name'  => 'Mamivan'
            ],
            [
                'name'  => 'Pickup 1 cabina'
            ],
            [
                'name'  => 'Pickup 2 cabinas'
            ]
        );

        foreach ($items as $item) {
            DB::table('vehicles_types')->insert([
                'name'     => $item['name'],
            ]);
        }
    }
}
