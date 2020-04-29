<?php

use Illuminate\Database\Seeder;

class PropertyTypeTableSeeder extends Seeder
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
                'name'  => 'Casa',
            ],
            [
                'name'  => 'Departamento'
            ],
            [
                'name'  => 'Oficina'
            ]
        );

        foreach ($items as $item) {
            DB::table('properties_types')->insert([
                'name'     => $item['name'],
            ]);
        }
    }
}
