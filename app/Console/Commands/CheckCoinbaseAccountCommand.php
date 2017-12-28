<?php

namespace App\Console\Commands;

use App\Notifications\LimitReached;
use App\Notifications\ObjectiveReached;
use App\Services\CoinbaseService;
use App\User;
use Illuminate\Console\Command;

class CheckCoinbaseAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coinbase:account:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check coinbase account';

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
        $user = User::all()->first();
        if ($user === null) {
            return;
        }

        $nativeBalance = $coinbaseService->getPrimaryAccount()->getNativeBalance();

        if ($nativeBalance->getAmount() == 0) {
            return;
        }

        $lastBuy = collect($coinbaseService->getBuysPrimaryAccount()->all())->first()->getTotal();
        $delta = $nativeBalance->getAmount() - $lastBuy->getAmount();

        $userObjective = $user->objective;
        $userAlert = $user->alert;
        if ($delta >= $userObjective) {
            $this->info("Objective reached");
            $user->notify(new ObjectiveReached($delta, $nativeBalance->getCurrency()));
        } else if ($delta <= $userAlert) {
            $this->info("Limit reached");
            $user->notify(new LimitReached($delta, $nativeBalance->getCurrency()));
        }
    }
}
