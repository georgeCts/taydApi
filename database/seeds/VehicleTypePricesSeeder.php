
<?php

use Illuminate\Database\Seeder;

class VehicleTypePricesSeeder extends Seeder
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
                'vehicle_type_id'   => 1,
                'key'               => 'LAVADO_ASPIRADO ',
                'name'              => 'Lavado y aspirado',
                'price'             => 90
            ],
            [
                'vehicle_type_id'   => 1,
                'key'               => 'ENCERADO',
                'name'              => 'Encerado',
                'price'             => 150
            ],
            [
                'vehicle_type_id'   => 1,
                'key'               => 'PULIDO',
                'name'              => 'Pulido',
                'price'             => 100
            ],
            [
                'vehicle_type_id'   => 1,
                'key'               => 'LAVADO_VESTIDURAS',
                'name'              => 'Lavado de vestiduras',
                'price'             => 300
            ],
            [
                'vehicle_type_id'   => 2,
                'key'               => 'LAVADO_ASPIRADO ',
                'name'              => 'Lavado y aspirado',
                'price'             => 100
            ],
            [
                'vehicle_type_id'   => 2,
                'key'               => 'ENCERADO',
                'name'              => 'Encerado',
                'price'             => 250
            ],
            [
                'vehicle_type_id'   => 2,
                'key'               => 'PULIDO',
                'name'              => 'Pulido',
                'price'             => 200
            ],
            [
                'vehicle_type_id'   => 2,
                'key'               => 'LAVADO_VESTIDURAS',
                'name'              => 'Lavado de vestiduras',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 3,
                'key'               => 'LAVADO_ASPIRADO ',
                'name'              => 'Lavado y aspirado',
                'price'             => 125
            ],
            [
                'vehicle_type_id'   => 3,
                'key'               => 'ENCERADO',
                'name'              => 'Encerado',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 3,
                'key'               => 'PULIDO',
                'name'              => 'Pulido',
                'price'             => 300
            ],
            [
                'vehicle_type_id'   => 3,
                'key'               => 'LAVADO_VESTIDURAS',
                'name'              => 'Lavado de vestiduras',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 4,
                'key'               => 'LAVADO_ASPIRADO ',
                'name'              => 'Lavado y aspirado',
                'price'             => 130
            ],
            [
                'vehicle_type_id'   => 4,
                'key'               => 'ENCERADO',
                'name'              => 'Encerado',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 4,
                'key'               => 'PULIDO',
                'name'              => 'Pulido',
                'price'             => 300
            ],
            [
                'vehicle_type_id'   => 4,
                'key'               => 'LAVADO_VESTIDURAS',
                'name'              => 'Lavado de vestiduras',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 5,
                'key'               => 'LAVADO_ASPIRADO ',
                'name'              => 'Lavado y aspirado',
                'price'             => 160
            ],
            [
                'vehicle_type_id'   => 5,
                'key'               => 'ENCERADO',
                'name'              => 'Encerado',
                'price'             => 350
            ],
            [
                'vehicle_type_id'   => 5,
                'key'               => 'PULIDO',
                'name'              => 'Pulido',
                'price'             => 300
            ],
            [
                'vehicle_type_id'   => 5,
                'key'               => 'LAVADO_VESTIDURAS',
                'name'              => 'Lavado de vestiduras',
                'price'             => 350
            ],
        );

        foreach ($items as $item) {
            DB::table('vehicles_types_prices')->insert([
                'vehicle_type_id'       => $item['vehicle_type_id'],
                'key'                   => $item['key'],
                'name'                  => $item['name'],
                'price'                 => $item['price'],
            ]);
        }
    }
}
