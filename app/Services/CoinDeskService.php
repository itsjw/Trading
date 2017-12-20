<?php
/**
 * Created by PhpStorm.
 * User: maximemaheo
 * Date: 20/12/2017
 * Time: 20:38
 */

namespace App\Services;


use App\Point;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CoinDeskService
{
    public static function getPricesOfDay($day)
    {
        $start = $day->toDateString();
        $end = Carbon::create($day->year, $day->month, $day->day + 1)->toDateString();

        $client = new Client();
        $request = $client->request('GET', 'https://api.coindesk.com/charts/data?data=close&exchanges=bpi&dev=1&index=USD&startdate=' . $start . '&enddate=' . $end);
        $response = str_replace("cb(", "", $request->getBody()->getContents());
        $response = str_replace(");", "", $response);
        $points = json_decode($response, true)["bpi"];

        foreach ($points as $point) {
            $timeStamp = (int)substr((string)$point[0], 0, -3);
            $date = Carbon::createFromTimestamp($timeStamp);
            $start = new Carbon($start);
            $end = new Carbon($end);

            if ($date->between($start, $end)) {
                Point::create([
                    'date'  => $date,
                    'value' => $point[1],
                ]);
            }
        }
    }
}