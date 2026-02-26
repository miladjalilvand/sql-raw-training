<?php

namespace Database\Seeders;

use App\Models\MachineType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for( $i = 1; $i < 7; $i++ ) 
        {
            MachineType::create([
            "id"=> $i,
            "caption"=> Str::random(6),
            ]);
        }
    }
}
