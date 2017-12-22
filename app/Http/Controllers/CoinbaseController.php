<?php

namespace App\Http\Controllers;

use App\Money;
use App\Notifications\ObjectiveReached;
use App\Services\CoinbaseService;
use Illuminate\Support\Facades\Auth;

class CoinbaseController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $userObjective = $user->objective;
        $userAlert = $user->alert;

        return view('coinbase.dashboard', compact('userObjective', 'userAlert'));
    }

    public function primaryAccount(CoinbaseService $coinbaseService)
    {
        $primaryAccount = $coinbaseService->getPrimaryAccount();

        return response()->json([
            'nativeBalanceAmount'   => $primaryAccount->getNativeBalance()->getAmount(),
            'nativeBalanceCurrency' => $primaryAccount->getNativeBalance()->getCurrency(),
        ]);
    }

    public function spotPrice(CoinbaseService $coinbaseService)
    {
        $spotPrice = $coinbaseService->getSpotPrice(CoinbaseService::BTC_EUR);

        return response()->json([
            'amount'   => $spotPrice->getAmount(),
            'currency' => $spotPrice->getCurrency(),
        ]);
    }

    public function sellPrice(CoinbaseService $coinbaseService)
    {
        $sellPrice = $coinbaseService->getSellPrice($coinbaseService::BTC_EUR);

        return response()->json([
            'amount'   => $sellPrice->getAmount(),
            'currency' => $sellPrice->getCurrency(),
        ]);
    }

    public function buyPrice(CoinbaseService $coinbaseService)
    {
        $buyPrice = $coinbaseService->getbuyPrice($coinbaseService::BTC_EUR);

        return response()->json([
            'amount'   => $buyPrice->getAmount(),
            'currency' => $buyPrice->getCurrency(),
        ]);
    }

    public function money()
    {
        $sellMoney = Money::hour()->sell()->get();
        $spotMoney = Money::hour()->spot()->get();
        $buyMoney = Money::hour()->buy()->get();

        return response()->json([
            'sell' => $sellMoney,
            'spot' => $spotMoney,
            'buy'  => $buyMoney,
        ]);
    }

    public function lastBuy(CoinbaseService $coinbaseService)
    {
        $lastBuy = collect($coinbaseService->getBuysPrimaryAccount()->all())->first();

        return response()->json([
            'amount'   => $lastBuy->getTotal()->getAmount(),
            'currency' => $lastBuy->getTotal()->getCurrency(),
        ]);
    }

    public function lastSell(CoinbaseService $coinbaseService)
    {
        $sells = collect($coinbaseService->getSellsPrimaryAccount()->all());

        foreach ($sells as $sell) {
            if ($sell->getStatus() === 'completed') {
                return response()->json([
                    'amount'   => $sell->getTotal()->getAmount(),
                    'currency' => $sell->getTotal()->getCurrency(),
                ]);
            }
        }

        return response()->json([
            'amount'   => -1,
            'currency' => -1,
        ]);
    }
}
