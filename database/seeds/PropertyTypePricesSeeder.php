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
                'key'               => 'RECAMARA',
                'name'              => 'Recámara',
                'price'             => 25
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'BANO',
                'name'              => 'Baño',
                'price'             => 35
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'MEDIO_BANO',
                'name'              => 'Medio baño',
                'price'             => 25
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'SALA',
                'name'              => 'Sala',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'COMEDOR',
                'name'              => 'Comedor',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'COCINA',
                'name'              => 'Cocina',
                'price'             => 30
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'GARAGE',
                'name'              => 'Garage',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'PATIO_TRASERO',
                'name'              => 'Patio trasero',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'TERRAZA',
                'name'              => 'Terraza',
                'price'             => 20
            ],
            [
                'property_type_id'  => 1,
                'key'               => 'ESCALERAS',
                'name'              => 'Escaleras',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'RECAMARA',
                'name'              => 'Recámara',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'BANO',
                'name'              => 'Baño',
                'price'             => 25
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'MEDIO_BANO',
                'name'              => 'Medio baño',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'SALA',
                'name'              => 'Sala',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'COMEDOR',
                'name'              => 'Comedor',
                'price'             => 15
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'COCINA',
                'name'              => 'Cocina',
                'price'             => 25
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'GARAGE',
                'name'              => 'Garage',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'AREA_SERVICIOS',
                'name'              => 'Área de servicios',
                'price'             => 20
            ],
            [
                'property_type_id'  => 2,
                'key'               => 'TERRAZA',
                'name'              => 'Terraza',
                'price'             => 20
            ],
        );

        foreach ($items as $item) {
            DB::table('properties_types_prices')->insert([
                'property_type_id'      => $item['property_type_id'],
                'key'                   => $item['key'],
                'name'                  => $item['name'],
                'price'                 => $item['price'],
            ]);
        }
    }
}
