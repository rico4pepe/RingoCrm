<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'AIRTIME',
            'DATA',
            'CHEAP DATA',
            'BULK SMS',
            'BILL PAYMENTS',
            'EDUCATIONS',
            'PAY TV',
            'BETTING',
            'SOLAR',
            'ESIMS',
            'GIFT CARDS',
            'VOUCHERS',
            'INTERNATIONAL SMS',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'category_name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
