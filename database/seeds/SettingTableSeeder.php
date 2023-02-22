<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            'description' => "Our Vision is to make interior brands accessible to everyone. With this in the forefront of our mind, we can ensure our customers that we have the best, the exclusives and most popular wallpaper designs available. The result is a collection of all the top wallpaper brands, from coveted designer names to emerging new creations.

With dozens of new collections added each week, there will always be something to suit everyones style. The luxury about shopping with us, is that everything is all under one roof for you to select what wallpaper, flooring, curtains, bedding and we send it all directly to you at the click of a button.",
            'short_des' => "Our passion stems from creating the perfect home for our customers. Our company started from humble beginnings in 2018 where from a small selection of designs and styles to branching out from wallpaper to then include paint, ceiling roses and not forgetting flooring..",
            'photo' => "image.jpg",
            'logo' => 'logo.jpg',
            'address' => "Maa'le Iron - Musmus",
            'email' => "info@sharkdesign.co.il",
            'phone' => "+972502445194",
        );
        DB::table('settings')->insert($data);
    }
}
