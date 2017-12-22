<?php

namespace App\Http\Controllers;

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Illuminate\Http\Request;

class CoinbaseController extends Controller
{
    public function dashboard()
    {
        $configuration = Configuration::apiKey(env('COINBASE_API_KEY'), env('COINBASE_SECRET_API_KEY'));
        $client = Client::create($configuration);
        dd($client->getPrimaryAccount());
    }
}
