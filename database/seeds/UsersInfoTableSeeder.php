<?php

use Illuminate\Database\Seeder;

class UsersInfoTableSeeder extends Seeder
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
                'user_id'   => 1,
                'name'      => 'Romeo',
                'last_name' => 'Lopez Rodriguez',
                'photo'     => 'default.jpg',
                'birthday'  => '1994-05-29',
                'phone'     => '9931695789',
                'address'   => 'N/A',
                'zipcode'   => 86153,
                'estate'    => 'Tabasco',
                'city'      => 'Villahermosa'
            ],
            [
                'user_id'   => 2,
                'name'      => 'Fernanda',
                'last_name' => 'Perez Ramirez',
                'photo'     => 'default.jpg',
                'birthday'  => '1989-11-05',
                'phone'     => '9932457896',
                'address'   => 'Col. Tamulte, Calle 35 #004',
                'zipcode'   => 86150,
                'estate'    => 'Tabasco',
                'city'      => 'Villahermosa'
            ]
        );

        foreach ($items as $item) {
            DB::table('users_info')->insert([
                'name'      => $item['name'],
                'user_id'   => $item['user_id'],
                'last_name' => $item['last_name'],
                'photo'     => $item['photo'],
                'birthday'  => $item['birthday'],
                'phone'     => $item['phone'],
                'address'   => $item['address'],
                'zipcode'   => $item['zipcode'],
                'estate'    => $item['estate'],
                'city'      => $item['city'],
            ]);
        }
    }
}
