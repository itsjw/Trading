<?php

namespace App\Http\Controllers;

use App\Money;
use App\Notifications\ObjectiveReached;
use App\Services\CoinbaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception\RuntimeException;

class CoinbaseController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $userObjective = $user->objective;

        return view('coinbase.dashboard', compact('userObjective'));
    }

    public function primaryAccount(CoinbaseService $coinbaseService)
    {
        $primaryAccount = $coinbaseService->getPrimaryAccount();

        return response()->json([
            'balanceAmount'   => $primaryAccount->getBalance()->getAmount(),
            'balanceCurrency'   => $primaryAccount->getBalance()->getCurrency(),
            'nativeBalanceAmount'   => $primaryAccount->getNativeBalance()->getAmount(),
            'nativeBalanceCurrency' => $primaryAccount->getNativeBalance()->getCurrency(),
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

    public function money(Request $request)
    {
        if($request->get('interval') === 'day') {
            $sellMoney = Money::day()->sell()->get();
            $spotMoney = Money::day()->spot()->get();
            $buyMoney = Money::day()->buy()->get();
        } else {
            $sellMoney = Money::hour()->sell()->get();
            $spotMoney = Money::hour()->spot()->get();
            $buyMoney = Money::hour()->buy()->get();
        }

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
}
