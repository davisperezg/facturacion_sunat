<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('sendJsonData')) {
    function sendJsonData($datos)
    {
        echo json_encode($datos);
    }
}

if (!function_exists('myUrlEncode')) {

    function myUrlEncode($string) {
        if ($string == "") 
            return 'rrGuitarra';
                
            $string = urlencode($string);
            $string = strtr($string, array('+' => 'xya3D',
                '=' => 'xya26',
                '/' => 'xya2C',
                '.' => 'xya2CCC'
            ));
            return $string;
    }

}

if (!function_exists('myUrlDecode')) {

    function myUrlDecode($string) {
        if ($string == 'rrGuitarra')
            return '';

        $string = strtr($string, array('xya3D' => '+',
            'xya26' => '=',
            'xya2C' => '/',
            'xya2CCC' => '.',
        ));
        $string = urldecode($string);
        return $string;
    }

}

if (!function_exists('paginacionBoostrapCodeigniter')) {

    function paginacionBoostrapCodeigniter($config) {
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        return $config;
    }

}

if (!function_exists('verificar_anio_activo')) {

    function verificar_anio_activo() {
        $CI = & get_instance();
        $CI->load->model('anio_model');
        $data = $CI->anio_model->select(2, array('id', 'descripcion', 'anio'), array('activo_id' => '1'));

        if (count($data) == 0) {
            redirect(base_url() . "index.php/error/activacion_anio");
        } else {
            return $data;
        }
    }

}

if (!function_exists('format_fecha_0000_00_00')) {

    //de 00-00-0000   a   0000-00-00
    function format_fecha_0000_00_00($fecha = '') {

        $resultado = '';
        if ($fecha != '') {
            $resultado = substr($fecha, 6, 4) . "-" . substr($fecha, 3, 2) . "-" . substr($fecha, 0, 2);
        }
        return $resultado;
    }

}

if (!function_exists('format_fecha_00_00_0000')) {

    //de 0000-00-00  a   00-00-0000
    function format_fecha_00_00_0000($fecha) {

        $resultado = '';
        if ($fecha != '') {
            $resultado = substr($fecha, 8, 2) . "-" . substr($fecha, 5, 2) . "-" . substr($fecha, 0, 4);
        }
        return $resultado;
    }

}


if (!function_exists('format_fecha_0000_00_00_format_raya')) {

    //de 00-00-0000   a   0000-00-00
    function format_fecha_0000_00_00_format_raya($fecha = '') {

        $resultado = '';
        if ($fecha != '') {
            $resultado = substr($fecha, 6, 4) . "/" . substr($fecha, 3, 2) . "/" . substr($fecha, 0, 2);
        }
        return $resultado;
    }

}

if (!function_exists('format_fecha_00_00_0000_format_raya')) {

    //de 0000-00-00  a   00-00-0000
    function format_fecha_00_00_0000_format_raya($fecha) {

        $resultado = '';
        if ($fecha != '') {
            $resultado = substr($fecha, 8, 2) . "/" . substr($fecha, 5, 2) . "/" . substr($fecha, 0, 4);
        }
        return $resultado;
    }

}

if (!function_exists('letras_a_numeros')) {

    function letras_a_numeros($palabra) {
        $letras_a_numeros = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13, 'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26);

        $palabra = trim($palabra);
        $letras = strlen($palabra);
        $numero = '';
        for ($i = 0; $i < $letras; $i++) {
            $letra = substr($palabra, $i, 1);
            $numero .= $letras_a_numeros[$letra];
        }
        return $numero;
    }

}

if (!function_exists('codigo_barras')) {

    function codigo_barras($codigo) {
        require_once(APPPATH . 'libraries/php-barcode/BarcodeGenerator.php');
        require_once(APPPATH . 'libraries/php-barcode/BarcodeGeneratorPNG.php');
        require_once(APPPATH . 'libraries/php-barcode/BarcodeGeneratorSVG.php');

        $generatorPNG = new BarcodeGeneratorPNG();
        $generatorSVG = new BarcodeGeneratorSVG();

        //$data['codigo_barras'] = $generatorSVG->getBarcode($codigo, $generatorPNG::TYPE_EAN_13,2,50);
        $numero = $generatorSVG->getBarcode($codigo, $generatorPNG::TYPE_EAN_13, 2, 50);
        return $numero;
    }

}

if (!function_exists('obtener_iniciales')) {

    function obtener_iniciales($texto) {
        //4 iniciales
        $texto = trim($texto);
        $array = explode(" ", $texto);
        $iniciales = '';
        $i = 1;

        foreach ($array as $value) {
            $iniciales .= substr($value, 0, 1);
            if ($i == 4) {
                break;
            }
            $i++;
        }

        if (strlen($iniciales) < 4) {
            $falta = 4 - strlen($iniciales);
            for ($i = 0; $i < $falta; $i++) {
                $iniciales .= "x";
            }
        }
        return $iniciales;
    }

}

if (!function_exists('listar_meses')) {

    function listar_meses($mes = '') {
        //4 iniciales
        $meses = array(
            '1' => 'enero',
            '2' => 'febrero',
            '3' => 'marzo',
            '4' => 'abril',
            '5' => 'mayo',
            '6' => 'junio',
            '7' => 'julio',
            '8' => 'agosto',
            '9' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre'
        );

        if ($mes != '') {
            return $meses[$mes];
        }

        return $meses;
    }

}

if (!function_exists('dias_entre_fechas')) {

    function dias_entre_fechas($desde, $hasta) {
        $dias = (strtotime($desde) - strtotime($hasta)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias + 1;
    }

}

if (!function_exists('array_fechas')) {

    function array_fechas($start, $end) {
        if (is_string($start) === true)
            $start = strtotime($start);
        if (is_string($end) === true)
            $end = strtotime($end);

        $range = array();
        while ($start <= $end) {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        }
        return $range;
    }

}

if (!function_exists('array_envia')) {

    function array_envia($array) {
        $array = serialize($array);
        $array = urlencode($array);
        return $array;
    }

}

if (!function_exists('array_recibe')) {

    function array_recibe($tmp) {
        $tmp = urldecode($tmp);
        $tmp = unserialize($tmp);
        return $tmp;
    }

}

//  FUNCION AGREGADA PARA SUMAR HORAS //

function RestarHoras($horaini, $horafin) {
    $horai = substr($horaini, 0, 2);
    $mini = substr($horaini, 3, 2);
    $segi = substr($horaini, 6, 2);

    $horaf = substr($horafin, 0, 2);
    $minf = substr($horafin, 3, 2);
    $segf = substr($horafin, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
    $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

    $dif = $fin - $ini;

    $difh = floor($dif / 3600);
    $difm = floor(($dif - ($difh * 3600)) / 60);
    $difs = $dif - ($difm * 60) - ($difh * 3600);
    return date("H:i:s", mktime($difh, $difm, $difs));
}

function sumarhoras($horaini, $horafin) {
    $horai = substr($horaini, 0, 2);
    $mini = substr($horaini, 3, 2);
    $segi = substr($horaini, 6, 2);

    $horaf = substr($horafin, 0, 2);
    $minf = substr($horafin, 3, 2);
    $segf = substr($horafin, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
    $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

    $dif = $fin + $ini;

    $difh = floor($dif / 3600);
    $difm = floor(($dif - ($difh * 3600)) / 60);
    $difs = $dif - ($difm * 60) - ($difh * 3600);
    return date("H:i:s", mktime($difh, $difm, $difs));
}

function convierte_a_entero($horan) {
    $horai = substr($horan, 0, 2);
    $mini = substr($horan, 3, 2);
    $segi = substr($horan, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);

    return $ini;
}

function convierte_a_hora($entero) {
    $difh = floor($entero / 3600);
    $difm = floor(($entero - ($difh * 3600)) / 60);
    $difs = $entero - ($difm * 60) - ($difh * 3600);
    return $difh . "H " . $difm . "m " . $difs . "s";
}

function minuto_a_horas($minutos) {
    $horas = '';
    if (($minutos > 0) && ($minutos != '')) {
        $horas = floor($minutos / 60);
        $horas = $horas . "H " . ($minutos % 60) . "m";
    }
    return $horas;
}

function crearFileBinary($nombre_archivo, $contenido) {
    $data = base64_decode($contenido);
    file_put_contents($nombre_archivo, $data);
}

//   FUNCION AGREGADA PARA SUMAR HORAS //