<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:24 PM
 */

namespace App\Repositories;

interface ISystemRepository
{
    public function get($key);

    public function set($key, $value);

    public function beginTransaction();

    public function commitTransaction();

    public function rollbackTransaction();

    public function createOutputBasedReport($params = array());

}
