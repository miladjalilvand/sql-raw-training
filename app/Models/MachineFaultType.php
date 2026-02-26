<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineFaultType extends Model
{
    //
    protected $table = "machine_fault_types";
    protected $fillable = ['caption'];
}
