<?php


namespace App\Service;


use poloniex\api\Poloniex;

class PoloniexService
{
    private $service;

    private $key;

    private $secret;

    public function __construct($poloKey,$poloSecret) {
        $this->service = new Poloniex();
        $this->key = $poloKey;
        $this->secret = $poloSecret;
    }

    public function getTicker() {
        return $this->service->returnTicker();
    }

    public function getRemoteMarkets() {
        return $this->service->returnCurrencies();
    }

    public function returnTradeHistory($currencyPair, \DateTime $start, \DateTime $end) : array {
        return $this->returnTradeHistoryInternal($currencyPair,$start, $end);
    }

    public function returnTradeHistoryInternal($currencyPair, \DateTime $start, \DateTime $end) : array {
        usleep(500000);
        $list = $this->service->returnPublicTradeHistory($currencyPair,$start->getTimestamp(),$end->getTimestamp());
        if (count((array)$list) == 1000) {
            $diff = $start->diff($end);
            $diffNum = abs($diff->h*3600 + $diff->i*60 + $diff->s) + 1;

            $first1 = $start;
            $last1 = clone $first1;
            $last1->add(new \DateInterval('PT'.(string)($diffNum/2).'S'));
            $first2 = clone $last1;
            $last1->sub(new \DateInterval('PT1S'));
            $list1 = $this->returnTradeHistoryInternal($currencyPair,$first1,$last1);

            $last2 = clone $first2;
            $last2->add(new \DateInterval('PT'.(string)($diffNum/2).'S'));
            $last2->sub(new \DateInterval('PT1S'));
            $list2 = $this->returnTradeHistoryInternal($currencyPair,$first2,$last2);
            $list = array_merge($list1[0], $list2[0]);
        }
        return (array($list));
    }
}