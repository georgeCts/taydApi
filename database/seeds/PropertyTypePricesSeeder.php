<?php

use Illuminate\Database\Seeder;

class PropertyTypePricesSeeder extends Seeder
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
                'property_type_id'  => 1,
                'key'             => 'RECAMARA',
                'price'             => 25
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'BANO',
                'price'             => 35
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'MEDIO_BANO',
                'price'             => 25
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'SALA',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'COMEDOR',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'COCINA',
                'price'             => 30
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'GARAGE',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'PATIO_TRASERO',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'TERRAZA',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'             => 'ESCALERAS',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'RECAMARA',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'BANO',
                'price'             => 25
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'MEDIO_BANO',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'SALA',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'COMEDOR',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'COCINA',
                'price'             => 25
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'GARAGE',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'AREA_SERVICIOS',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'             => 'TERRAZA',
                'price'             => 20
            ],
        );

        foreach ($items as $item) {
            DB::table('properties_types_prices')->insert([
                'property_type_id'      => $item['property_type_id'],
                'key'                   => $item['key'],
                'price'                 => $item['price'],
            ]);
        }
    }
}
