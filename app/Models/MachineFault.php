<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineFault extends Model
{
    //
    protected $table = "machine_faults";
    protected $fillable = ['drop_performance','off_effect' , 'caption' , 'machine_fault_type_id'] ;
}
