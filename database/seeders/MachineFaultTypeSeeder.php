<?php

namespace Database\Seeders;

use App\Models\MachineFaultType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachineFaultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for( $i = 1; $i < 7; $i++ ) 
        {
            MachineFaultType::create([
            "id"=> $i,
            "caption"=> Str::random('12'),
          
            ]);
        }        
    }
}
