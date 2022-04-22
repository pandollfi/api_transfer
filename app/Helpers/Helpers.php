<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Contracts\Container\Container;

class Helpers
{
    public static function formatarDataPadrao(string $data){
        $data = new \DateTime($data);
        return $data->format('Y-m-d');
    }

    public static function validarData(string $data, $formato = 'd-m-Y'){
        $d = \DateTime::createFromFormat($formato, $data);
        return $d && $d->format($formato);
    }
}
