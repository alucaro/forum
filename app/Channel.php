<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    // por defecto Laravel devuelve el id, para cambiar esto sobreescribimos el metodo
    public function getRouteKeyName(){
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
