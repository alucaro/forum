<?php

//correr el comando composer dump-autoload para poder hacer los llamados
//a estas funciones desde los demas archivos

function create($class, $attributes = [])
{
    return factory($class)->create($attributes);
}

function make($class, $attributes = [])
{
    return factory($class)->make($attributes);
}