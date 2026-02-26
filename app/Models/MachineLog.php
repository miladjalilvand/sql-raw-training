<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineLog extends Model
{
    //
    protected $table='machine_logs';
    protected $fillable = ['machine_id'];
}
