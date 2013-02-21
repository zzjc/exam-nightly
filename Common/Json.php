<?php
class Json
{
    static public function encode($json)
    {
        return preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $json);
    }
}