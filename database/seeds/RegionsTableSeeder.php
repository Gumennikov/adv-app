<?php

use Illuminate\Database\Seeder;
use App\Entity\Region;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Region::class, 10)->create()->each(function (Region $region){
            $region->children()->saveMany(factory(Region::class, random_int(3, 10))->create()->each(function (Region $region) {
                $region->children()->saveMany(factory(Region::class, random_int(3, 10))->make());
            }));
        });
    }
}
