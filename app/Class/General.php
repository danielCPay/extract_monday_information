<?php

namespace App\Class;

class General
{
    private const RUTA_ARCHIVO = 'upload/';

    public static function isEmpty($object)
    {
        if (!isset($object))
            return true;
        if (is_null($object))
            return true;
        if (is_string($object) && strlen($object) <= 0)
            return true;
        if (is_array($object) && empty($object))
            return true;
        if (is_numeric($object) && is_nan($object))
            return true;
        if (is_object($object) && $object == new \stdClass())
            return true;

        return false;
    }

    public static function convertir_array_a_xml($arrayData)
    {
        $xml_dato = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
        self::array_a_xml($arrayData, $xml_dato, 0);
        return $xml_dato->asXML();
    }

    private static function array_a_xml($dato, &$xml_dato, $nivel)
    {
        foreach ($dato as $llave => $valor) {
            if (is_numeric($llave)) {
                //$llave = 'item'.$llave; //dealing with <0/>..<n/> issues
                $llave = 'item';
            } else if ($nivel == 0) {
                $llave = $llave;
            }
            if (is_array($valor)) {
                $subnode = $xml_dato->addChild($llave);
                self::array_a_xml($valor, $subnode, ($nivel + 1));
            } else {
                //$xml_dato->addChild("$llave",htmlspecialchars("$valor", ENT_QUOTES, "UTF-8"));
                $xml_dato->addChild("$llave", htmlspecialchars("$valor"));
            }
        }
    }

    public static function subir_archivo($files, $ruta = "")
    {
        $_FILES = $files;
        $nombreArchivo = $_FILES['name'];
        $extension = explode(".", $nombreArchivo);
        $extension = $extension[count($extension) - 1];
        $nombreArchivoFinal =
            date("y") . //años
            date("m") . //mes
            date("d") . //dia
            date("H") . //hora 24h
            date("i") . //minutos
            date("s") . //segundos
            date("_") .
            substr(microtime(), 2, 4) .
            '.' . $extension;
        //$rutaArchivoSubido = __DIR__."\..\..\public\upload\\".$ruta. $nombreArchivoFinal;
        //move_uploaded_file($_FILES['tmp_name'], $rutaArchivoSubido);
        //return "upload\\".$ruta.$nombreArchivoFinal;

        $rutaArchivoSubido = self::RUTA_ARCHIVO . $ruta . $nombreArchivoFinal;
        move_uploaded_file($_FILES['tmp_name'], $rutaArchivoSubido);

        return $rutaArchivoSubido;
    }

    public static function replace_characters_pmc_api($valor_string)
    {

        $search_simbolo = "$";
        $replace_simbolo = "";
        $string_simbolo = $valor_string;
        $resultado_simbolo = str_ireplace($search_simbolo, $replace_simbolo, $string_simbolo);

        $search_espacio = " ";
        $replace_espacio = "";
        $string_espacio = $resultado_simbolo;
        $resultado_espacio = str_ireplace($search_espacio, $replace_espacio, $string_espacio);

        $search_coma = ",";
        $replace_coma = "";
        $string_coma = $resultado_espacio;
        $resultado_coma = str_ireplace($search_coma, $replace_coma, $string_coma);
        $resultado_coma = $resultado_coma == "" ? 0 : $resultado_coma;
        return $resultado_coma;
    }

    public static function replace_characters_date_usa_pmc_api($valor_string)
    {
        $search = "-";
        $replace = ".";
        $string = $valor_string;
        $resultado = str_ireplace($search, $replace, $string);
        $resultado = $resultado == "" ? NULL : $resultado;
        return $resultado;
    }

    public static function replace_characters_percentage_pmc_api($valor_string)
    {
        $search = "%";
        $replace = "";
        $string = $valor_string;
        $resultado = str_ireplace($search, $replace, $string);
        $resultado = $resultado == "" ? 0 : $resultado;
        return $resultado;
    }
}
