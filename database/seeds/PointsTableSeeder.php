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
        $day = Carbon::today();

        for ($i = 0; $i < 8; $i++) {
            CoinDeskService::getPricesOfDay($day);
            $day->subDay();
        }
    }
}
