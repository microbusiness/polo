<?php


namespace App\Entity;


use App\Entity\Map\PublicTradeHistoryMap;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;

class PublicTradeHistoryList
{
    private $list;
    private $map;

    public function __construct(array $list,PublicTradeHistoryMap $map, array $currencyIdList) {
        $this->map = $map;
        $this->list = new ArrayCollection();

        $objectPropertyBaseMap = $this->map->getListByKey('name');
        $tz = new DateTimeZone('UTC');
        foreach ($list as $topItem) {
            foreach ($topItem as $key => $item) {
                $obj = new PublicTradeHistory($currencyIdList);
                foreach ($objectPropertyBaseMap as $prop => $propMap)
                {
                    if (array_key_exists($propMap['remote_key'],$item)) {
                        if (property_exists($obj,$prop)) {
                            if ($propMap['type'] == 'integer') {
                                $calcItem = (int)$item[$propMap['remote_key']];
                            } elseif ($propMap['type'] == 'float') {
                                $calcItem = (float)$item[$propMap['remote_key']];
                            } elseif ($propMap['type'] == 'datetime') {
                                $calcItem = new \DateTime($item[$propMap['remote_key']],$tz);
                            } else {
                                $calcItem = $item[$propMap['remote_key']];
                            }
                            $methodSet = $propMap['method']['set'];
                            $obj->$methodSet($calcItem);
                        }

                    }
                }

                $this->list->add($obj);
            }

        }
    }

    public function getList() {
        return $this->list;
    }

    public function getMap() {
        return $this->map;
    }
}