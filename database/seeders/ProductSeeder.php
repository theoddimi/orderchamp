<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {
        for ($i = 0; $i < 3; $i++) {
            DB::table('product')->insert([
                'name' => 'Product' . ' ' . $i + 1,
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eleifend.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet quis lacus ac dignissim. Praesent non tellus felis. Phasellus felis ipsum, malesuada a varius ac, varius ut dui. Proin nec sapien sit amet leo auctor fermentum in ut urna. Donec mattis efficitur purus efficitur ornare. Phasellus vel iaculis arcu. Phasellus tempor arcu in augue sollicitudin lacinia. Nam mollis urna a risus porttitor, vitae gravida risus posuere. Sed egestas massa ut molestie fringilla. Nulla commodo leo est, vitae imperdiet eros malesuada in. Proin egestas lobortis lorem sit amet commodo. Integer et condimentum eros. Vivamus lacus est, egestas id pulvinar sit amet, consequat sed augue. Ut sed elit justo. Sed aliquet dui non lacinia tempus. Aliquam lacinia eu sapien non ornare. Proin hendrerit fermentum fringilla. Mauris nec ipsum pharetra, posuere massa at, luctus tellus. Aenean aliquam lorem leo, eget rhoncus lectus aliquet viverra. Cras commodo nisi condimentum lacinia gravida. Phasellus gravida maximus magna, a fringilla dolor ultrices vitae. Morbi erat elit, euismod quis vulputate in, gravida at nisi.',
                'price' => rand(5, 50),
                'quantity' => rand(5, 50),
                'image' => 'no-image-placeholder.jpg'
            ]);
        }
    }

}
