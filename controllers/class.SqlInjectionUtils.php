<?php
class SqlInjectionUtils
{
    public static function checkSqlInjectionAttempt($data){
        $response = false;
        $postString = strtolower(print_r($data, true));
        if ((strpos(strtolower($postString), 'select ') !== false && strpos(strtolower($postString), 'PRIMERA SELECT') !== false && !strpos(strtolower($postString), 'human select') && !strpos(strtolower($postString), 'canvas select plus'))
            || strpos(strtolower($postString), ' deleted') !== false
            || (strpos(strtolower($postString), 'select ') !== false && strpos(strtolower($postString), ' from ') !== false)
            || strpos(strtolower($postString), 'delete ') !== false || strpos(strtolower($postString), 'delete/*') !== false
            || ( strpos(strtolower($postString), 'drop') !== false && (strpos(strtolower($postString), 'drop ') == 0 || strpos(strtolower($postString), ';drop ') !== false || strpos(strtolower($postString), ' drop ') !== false || strpos(strtolower($postString), '(drop ') !== false))
            || strpos(strtolower($postString), ' where ') !== false
            || strpos(strtolower($postString), 'xor(') !== false
            || (strpos(strtolower($postString), ' union select') !== false && strpos(strtolower($postString), ' union all select') !== false && strpos(strtolower($postString), ' union distinct select') !== false)
            || strpos(strtolower($postString), ' between') !== false) {

            $response = true;

        }
        return $response;
    }
}
