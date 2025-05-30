<?php

class Host
{
private static $HOST_NAME="https://salesmanager.infinityfreeapp.com/";

    /**
     * @return string
     */
    public static function getHOSTNAME(): string
    {
        return self::$HOST_NAME;
    }


}