<?php

//correr el comando composer dump-autoload para poder hacer los llamados
//a estas funciones desde los demas archivos

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}