<?php

namespace App\Console\Commands;

use App\Money;
use App\Services\CoinbaseService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CoinbaseMoneyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coinbase:price:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get buy, spot and sell price.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CoinbaseService $coinbaseService)
    {
        $sellPrice = $coinbaseService->getSellPrice($coinbaseService::BTC_EUR);
        $spotPrice = $coinbaseService->getSpotPrice($coinbaseService::BTC_EUR);
        $buyPrice = $coinbaseService->getBuyPrice($coinbaseService::BTC_EUR);

        Money::create([
            'date'     => Carbon::now(),
            'value'    => (float)$sellPrice->getAmount(),
            'type'     => 'sell',
            'currency' => $sellPrice->getCurrency(),
        ]);

        Money::create([
            'date'     => Carbon::now(),
            'value'    => (float)$spotPrice->getAmount(),
            'type'     => 'spot',
            'currency' => $spotPrice->getCurrency(),
        ]);

        Money::create([
            'date'     => Carbon::now(),
            'value'    => (float)$buyPrice->getAmount(),
            'type'     => 'buy',
            'currency' => $buyPrice->getCurrency(),
        ]);

        return;
    }
}
