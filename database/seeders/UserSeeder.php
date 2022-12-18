<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $user = array(
                'first_name' => 'Ankesh',
                'last_name' => 'Pareek',
                'email' => 'admin@admin.com',
                'password' => bcrypt('321321321')
            );
            DB::table('users')->insert($user);
        }catch (\Exception $exception){
            throw new \RuntimeException($exception->getMessage());
        }
    }
}
