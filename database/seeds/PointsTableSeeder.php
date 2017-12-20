<?php

use App\Services\CoinDeskService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDate = Carbon::today()->subWeek();
        $diffInDays = Carbon::today()->diffInDays($startDate);

        for ($i = 0; $i < $diffInDays; $i++) {
            CoinDeskService::getPriceOfDay($startDate);
            $startDate->addDay();
        }
    }
}
