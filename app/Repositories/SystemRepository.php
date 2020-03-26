<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:25 PM
 */

namespace App\Repositories;


use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SystemRepository implements ISystemRepository
{
    public function get($key)
    {
        $setting = Setting::query()->where('key', $key)->first();
        if ($setting)
        {
            return $setting->value;
        }
        return null;
    }

    public function set($key, $value)
    {
        $setting = Setting::query()->firstOrNew(['key' => $key]);
        $setting->value = $value;
        return $setting->save();
    }

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }
}
