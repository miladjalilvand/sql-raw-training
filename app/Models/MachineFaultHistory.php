<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineFaultHistory extends Model
{
    //
    protected $table = "machine_fault_histories";

    protected $fillable = ['end_machine_log_id' , 'start_machine_log_id','machine_fault_id','machine_id'];
    
}
