<?php
/**
 * Created by PhpStorm.
 * User: maximemaheo
 * Date: 22/12/2017
 * Time: 09:23
 */

namespace App\Services;


use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Value\Money;

class CoinbaseService
{

    const BTC_EUR = 'BTC-EUR';

    private static $instance = null;
    private $client = null;

    private function __construct()
    {
        $configuration = Configuration::apiKey(env('COINBASE_API_KEY'), env('COINBASE_SECRET_API_KEY'));
        $this->client = Client::create($configuration);
    }

    public static function getInstance(): CoinbaseService
    {
        if (self::$instance === null) {
            self::$instance = new CoinbaseService();
        }

        return self::$instance;
    }

    public function getPrimaryAccount(): Account
    {
        return $this->client->getPrimaryAccount();
    }

    public function getSellPrice(string $currency): Money
    {
        return $this->client->getSellPrice($currency);
    }

    public function getBuyPrice(string $currency): Money
    {
        return $this->client->getBuyPrice($currency);
    }

    public function getSpotPrice(string $currency): Money
    {
        return $this->client->getSpotPrice($currency);
    }

    public function getBuysPrimaryAccount()
    {
        $primaryAccount = $this->getPrimaryAccount();

        return $this->client->getAccountBuys($primaryAccount);
    }

    public function getSellsPrimaryAccount()
    {
        $primaryAccount = $this->getPrimaryAccount();

        return $this->client->getSells($primaryAccount);
    }
}