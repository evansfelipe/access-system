<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VehicleContainer extends Pivot
{

    protected $table = 'vehicle_containers';

    protected $fillable = ['vehicle_id', 'container_id'];
    
}
