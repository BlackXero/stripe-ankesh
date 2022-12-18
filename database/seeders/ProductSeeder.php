<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        try{
            $products = array();
            $faker = Factory::create();
            foreach (range(1,50) as $e) {
                $randomNumber = random_int(3,15);
                $randomPrice = random_int(100,500);
                $time = $faker->dateTime();
                $products[] = ['name' => $faker->word(),'price' => $randomPrice,'description' => $faker->randomHtml(random_int(3,7)),'image' => $faker->imageUrl(640, 480, 'product', true),'created_at' => $time,'updated_at' => $time];
            }
            DB::table('products')->insert($products);
        }catch (\Exception $exception){
            throw new \RuntimeException($exception->getMessage());
        }
    }
}
