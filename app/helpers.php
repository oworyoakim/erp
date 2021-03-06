<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 1/12/2019
 * Time: 12:34 PM
 */

use App\Repositories\SystemRepository;

if (!function_exists('settings'))
{
    function settings()
    {
        return new SystemRepository;
    }
}

if (!function_exists('monthNumber'))
{
    function monthNumber($name)
    {
        if(is_null($name) || empty($name)){
            return null;
        }
        $name = strtolower($name);
        switch ($name)
        {
            case 'january':
            case 'jan':
                return 1;
            case 'february':
            case 'feb':
                return 2;
            case 'march':
            case 'mar':
                return 3;
            case 'april':
            case 'apr':
                return 4;
            case 'may':
                return 5;
            case 'june':
            case 'jun':
                return 6;
            case 'july':
            case 'jul':
                return 7;
            case 'august':
            case 'aug':
                return 8;
            case 'september':
            case 'sep':
                return 9;
            case 'october':
            case 'oct':
                return 10;
            case 'november':
            case 'nov':
                return 11;
            case 'december':
            case 'dec':
                return 12;
            default:
                return null;
        }
    }
}

if (!function_exists('setEnvironmentValue'))
{
    /**
     * @param $envKey
     * @param $oldValue
     * @param $newValue
     * @return void
     */
    function setEnvironmentValue($envKey, $oldValue, $newValue)
    {
        $envFile = base_path() . '/.env';
        $str = file_get_contents($envFile);

        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$newValue}\n", $str);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return;
    }
}

