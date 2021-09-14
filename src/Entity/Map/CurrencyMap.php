<?php


namespace App\Entity\Map;


class CurrencyMap
{
    const map = [
        ['name' => 'name','type' => 'string','remote_key' => 'name','db_field' => 'name'],
        ['name' => 'code','type' => 'string','remote_key' => '','db_field' => 'code'],
        ['name' => 'txFee','type' => 'float','remote_key' => 'txFee','db_field' => 'tx_fee'],
        ['name' => 'minConf','type' => 'float','remote_key' => 'minConf','db_field' => 'min_conf'],
        ['name' => 'frozen', 'type' => 'boolean','remote_key' => 'frozen','db_field' => 'frozen'],
        ['name' => 'disabled', 'type' => 'boolean','remote_key' => 'disabled','db_field' => 'disabled'],
        ['name' => 'delisted', 'type' => 'boolean','remote_key' => 'delisted','db_field' => 'delisted'],
    ];

    public function getListByKey($key) {
        $keys = [];
        foreach (self::map as $val) {
            $getMethod = 'get'.strtoupper(substr($val['name'],0,1)).substr($val['name'],1);
            $setMethod = 'set'.strtoupper(substr($val['name'],0,1)).substr($val['name'],1);

            if ($key === '') {
                $key = 'code';
            }
            $keys[$val[$key]] = array_merge($val,['method' => ['get' => $getMethod,'set' => $setMethod]]);
        }
        return $keys;
    }
}