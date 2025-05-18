<?php

class Host
{
private static $HOST_NAME="http://salesmanager.infinityfreeapp.com/";

    /**
     * @return string
     */
    public static function getHOSTNAME(): string
    {
        return self::$HOST_NAME;
    }


}