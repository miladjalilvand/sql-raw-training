<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachinePerformanceStandard extends Model
{
    //
    protected $table = "machine_performance_standards";
    protected $fillable = ['machine_id' , 'expected_output_per_hour'];
    
}
