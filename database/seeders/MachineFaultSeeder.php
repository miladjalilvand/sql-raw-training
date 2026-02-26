<?php

namespace Database\Seeders;

use App\Models\MachineFault;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachineFaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for( $i = 1; $i < 11; $i++ ) 
        {
            MachineFault::create([
            "id"=> $i,
            "drop_performance"=> rand(0,1),
            "off_effect"=> rand(0,100),
            "caption"=> Str::random(10),
            "machine_fault_type_id" => rand(1, 6)
            ]);
        }
    }
}
