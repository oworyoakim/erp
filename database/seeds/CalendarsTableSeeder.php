<?php

use App\Models\Calendar;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CalendarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Calendar::query()->truncate();

        $period = CarbonPeriod::create(Carbon::today(), Carbon::today()->endOfCentury());

        $period->forEach(function (Carbon $date){
            Calendar::create([
                'date' => $date,
                'is_weekend' => $date->isWeekend(),
            ]);
        });
    }
}
