<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    //
    protected $table ='machines';
    protected $fillable = ['model','machine_type_id'];

    public function machine_type()
    {
        return $this->belongsTo(MachineType::class);
    }
    
    
}
