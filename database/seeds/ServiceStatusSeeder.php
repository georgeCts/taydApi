<?php

use Illuminate\Database\Seeder;

class ServiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            ['name'  => 'Pendiente'],
            ['name'  => 'Agendado'],
            ['name'  => 'En curso'],
            ['name'  => 'Finalizado'],
            ['name'  => 'Cancelado'],
        );

        foreach ($items as $item) {
            DB::table('services_status')->insert([
                'name'          => $item['name'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
