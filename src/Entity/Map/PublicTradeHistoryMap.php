<?php


namespace App\Entity\Map;


class PublicTradeHistoryMap
{
    const map = [
        ['name' => 'globalTradeId','type' => 'integer','remote_key' => 'globalTradeID','db_field' => 'global_trade_id'],
        ['name' => 'tradeId','type' => 'integer','remote_key' => 'tradeID','db_field' => 'trade_id'],
        ['name' => 'tradeDate','type' => 'datetime','remote_key' => 'date','db_field' => 'trade_date'],
        ['name' => 'tradeType','type' => 'string','remote_key' => 'type','db_field' => 'trade_type'],
        ['name' => 'tradeRate', 'type' => 'float','remote_key' => 'rate','db_field' => 'trade_rate'],
        ['name' => 'amount', 'type' => 'float','remote_key' => 'amount','db_field' => 'amount'],
        ['name' => 'total', 'type' => 'float','remote_key' => 'total','db_field' => 'total'],
        ['name' => 'orderNumber','type' => 'integer','remote_key' => 'orderNumber','db_field' => 'order_number'],
    ];

    public function getListByKey($key) {
        $keys = [];
        foreach (self::map as $val) {
            $getMethod = 'get'.strtoupper(substr($val['name'],0,1)).substr($val['name'],1);
            $setMethod = 'set'.strtoupper(substr($val['name'],0,1)).substr($val['name'],1);
            $keys[$val[$key]] = array_merge($val,['method' => ['get' => $getMethod,'set' => $setMethod]]);
        }
        return $keys;
    }
}