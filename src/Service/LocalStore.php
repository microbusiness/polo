<?php


namespace App\Service;


use App\Entity\Map\PublicTradeHistoryMap;
use App\Entity\PublicTradeHistory;
use App\Entity\PublicTradeHistoryList;
use Doctrine\DBAL\Connection;

class LocalStore
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function setPublicHistoryList(array $list, $code) {
        $pair = $this->getCurrencyPairListForCode($code);
        $list = new PublicTradeHistoryList(
            $list,
            new PublicTradeHistoryMap(),
            $pair
        );
        $this->savePublicTradeHistory($list);
    }

    private function savePublicTradeHistory(PublicTradeHistoryList $list): void {
        $objectPropertyBaseMap = $list->getMap()->getListByKey('name');
        $dbFieldsBaseMap = $list->getMap()->getListByKey('db_field');

        $globalIdList = [];
        foreach ($list->getList() as $item) {
            $hourForCheck = $item->getTradeDate();
            $globalIdList = $this->getGlobalIdList($hourForCheck);
            break;
        }


        $sql = ' insert into public_trade_history ';
        $sqlFields = '(' . implode(',', array_keys($dbFieldsBaseMap)) . ',base_currency_id,market_currency_id) values ';
        $sqlValues = [];
        /**
         * @var PublicTradeHistory $val
         */
        foreach ($list->getList() as $val) {
            if (false === in_array($val->getGlobalTradeId(),$globalIdList)) {
                $currList = [];
                foreach ($objectPropertyBaseMap as $prop => $field) {
                    $getMethod = $field['method']['get'];
                    $calcVal = $val->$getMethod();
                    if ($field['type'] == 'string') {
                        $currList[] = "'" . $calcVal . "'";
                    } elseif ($field['type'] == 'datetime') {
                        $currList[] = "'" . $calcVal->format('Y-m-d H:i:s') . "'";
                    } else {
                        $currList[] = $calcVal;
                    }
                }
                $sqlValues[] = '(' . implode(',', $currList) . ','.$val->getBaseCurrencyId().','.$val->getMarketCurrencyId().')';
            }
        }

        if (count($sqlValues) != 0) {
            $sql = $sql . $sqlFields . implode(',', $sqlValues) . ';';

            $this->conn->executeQuery($sql);
        }


        return;
    }

    public function getHourTimeline() {
        $sql = "select date_trunc('hour',pth.trade_date) as date_hour from public_trade_history pth ";
        $sql.= "group by date_trunc('hour',pth.trade_date) ";
        $sql.= "order by date_trunc('hour',pth.trade_date) ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->execute();
        $timeline = [];
        $tz = new \DateTimeZone('UTC');
        while ($row = $stmt->fetchAssociative()) {
            $timeline[] = new \DateTime($row['date_hour'], $tz);
        }

        return $timeline;
    }

    public function getGlobalIdList(\DateTime $hour) {
        $sql = "select global_trade_id as global_trade_id from public_trade_history pth ";
        $sql.= "where date_trunc('hour',pth.trade_date) = date_trunc('hour',TO_TIMESTAMP(:trade_date,'YYYY-MM-DD HH24:MI:SS')) ";
        $sql.= "order by pth.trade_date ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindValue('trade_date', $hour->format('Y-m-d H:i:s'));
        $stmt->execute();
        $list = [];
        while ($row = $stmt->fetchAssociative()) {
            $list[] = $row['global_trade_id'];
        }

        return $list;
    }

    public function getFirstTradeForHour(\DateTime $hour) {
        $sql = "select pth.trade_rate as trade_rate from public_trade_history pth ";
        $sql.= "where pth.trade_date >= :trade_date ";
        $sql.= "order by pth.trade_date ";
        $sql.= "limit 1 ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindValue('trade_date', $hour, 'datetime');
        $stmt->execute();
        $tradeRate = 0;
        while ($row = $stmt->fetchAssociative()) {
            $tradeRate = (float)$row['trade_rate'];
        }

        return $tradeRate;
    }

    public function getLastTradeForHour(\DateTime $hour) {
        $sql = "select pth.trade_rate as trade_rate from public_trade_history pth ";
        $sql.= "where pth.trade_date <= :trade_date ";
        $sql.= "order by pth.trade_date DESC ";
        $sql.= "limit 1 ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindValue('trade_date', $hour->format('Y-m-d H:i:s'));
        $stmt->execute();
        $tradeRate = 0;
        while ($row = $stmt->fetchAssociative()) {
            $tradeRate = (float)$row['trade_rate'];
        }

        return $tradeRate;
    }

    public function saveCurrency(array $list) {
        $sql = "select c.* from currency c ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->execute();
        $localList = [];
        while ($row = $stmt->fetchAssociative()) {
            $localList[$row['code']] = $row;
        }
        foreach ($list as $key => $item) {
            if (false === array_key_exists($key,$localList)) {
                $sql = 'insert into currency (code,name,tx_fee,min_conf,frozen,disabled,delisted) values ';
                $sql.= '(:code,:name,:tx_fee,:min_conf,:frozen,:disabled,:delisted)';
                $stmt =  $this->conn->prepare($sql);
                $stmt->bindValue('code', $key);
                $stmt->bindValue('name', $item['name']);
                $stmt->bindValue('tx_fee', $item['txFee']);
                $stmt->bindValue('min_conf', $item['minConf']);
                $stmt->bindValue('frozen', $item['frozen'],'boolean');
                $stmt->bindValue('disabled', $item['disabled'],'boolean');
                $stmt->bindValue('delisted', $item['delisted'],'boolean');
                $stmt->execute();
            }
        }
    }

    public function getCurrencyPairListForCode($code) {
        $pairArray = explode('_',$code);
        $sql = "select c.* from currency c where code = '".$pairArray[0]."' or code = '".$pairArray[1]."' ";
        $stmt =  $this->conn->prepare($sql);
        $stmt->execute();
        $currencyIdList = [];
        while ($row = $stmt->fetchAssociative()) {
            if ($row['code'] == $pairArray[0]) {
                $currencyIdList['base_currency'] = $row['id'];
            }
            if ($row['code'] == $pairArray[1]) {
                $currencyIdList['market_currency'] = $row['id'];
            }
        }
        return $currencyIdList;
    }

}