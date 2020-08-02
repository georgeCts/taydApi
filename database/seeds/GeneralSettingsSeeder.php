<?php

use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
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
                'key'           => 'TAYD_COMISION',
                'description'   => 'Comisión perteneciente a Tayd por transaccion.',
                'value'         => '20'
            ],
            [
                'key'           => 'STRIPE_COMISION_PORCENTAJE',
                'description'   => 'Comisión perteneciente a Stripe por transaccion.',
                'value'         => '3.9'
            ],
            [
                'key'           => 'STRIPE_COMISION_EXTRA',
                'description'   => 'Comisión perteneciente a Stripe por transaccion.',
                'value'         => '3'
            ],
            [
                'key'           => 'SERVICIO_CASA',
                'description'   => 'Precio por servicio ofrecido a un inmueble tipo casa.',
                'value'         => '200'
            ],
            [
                'key'           => 'SERVICIO_DEPARTAMENTO',
                'description'   => 'Precio por servicio ofrecido a un inmueble tipo departamento.',
                'value'         => '150'
            ],
            [
                'key'           => 'IVA_PORCENTAJE',
                'description'   => 'Porcentaje correspondiente al impuesto.',
                'value'         => '16'
            ],
            [
                'key'           => 'SERVICIO_INSUMOS_EXTRA',
                'description'   => 'Precio por el consumo de los insumos extra solicitado por el cliente.',
                'value'         => '50'
            ],
        );

        foreach ($items as $item) {
            DB::table('general_settings')->insert([
                'key'           => $item['key'],
                'description'   => $item['description'],
                'value'         => $item['value'],
            ]);
        }
    }
}
