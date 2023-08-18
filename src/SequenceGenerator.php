<?php

namespace NetworkRailBusinessSystems\OracleApi;

class SequenceGenerator
{
    public static function generate(): int
    {
        $microtime = intval(microtime(true)) * 1000;

        $randomNumber = random_int(10000, 99999);

        return intval($microtime.$randomNumber);
    }
}
