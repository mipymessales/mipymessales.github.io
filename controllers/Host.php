<?php

class Host
{
private static $HOST_NAME="http://localhost/mipymessales/";

    /**
     * @return string
     */
    public static function getHOSTNAME(): string
    {
        return self::$HOST_NAME;
    }


}