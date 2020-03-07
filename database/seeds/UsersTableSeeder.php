<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                'email'     => 'cliente@email.com',
                'password'  => bcrypt('12345678'),
                'confirmed' => 0,
                'isTayder'  => 0
            ],
            [
                'email'     => 'tayder@email.com',
                'password'  => bcrypt('12345678'),
                'confirmed' => 0,
                'isTayder'  => 1
            ]
        );

        foreach ($items as $item) {
            DB::table('users')->insert([
                'email'     => $item['email'],
                'password'  => $item['password'],
                'confirmed' => $item['confirmed'],
                'isTayder'  => $item['isTayder']
            ]);
        }
    }
}
