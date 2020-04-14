<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Vehiculo extends Model
{
    
    protected $fillable =['placa','marca','modelo', 'npuertas','tipovehiculo'];
// hace referencia vehiculo
    public function vehiculo(){
        return $this->hasMany('App\Persona');
    }
}
