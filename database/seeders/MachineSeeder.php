<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for( $i = 1; $i < 15; $i++ ) 
        {
            Machine::create([
            "id"=> $i,
            "model"=> Str::random(10),
            "machine_type_id"=>rand(1,6)
            ]);
        }
    }
}
