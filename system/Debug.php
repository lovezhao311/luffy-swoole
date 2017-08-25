<?php
namespace luffyzhao;

class Debug
{
    public static function info($msg)
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n";
    }
}
