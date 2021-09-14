<?php


namespace App\Service;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class CalcService
{
    private LocalStore $store;

    private string $firstDay;

    private LogService $log;

    public function __construct(LocalStore $store,$firstDay,LogService $log) {
        $this->store = $store;
        $this->firstDay = $firstDay;
        $this->log = $log;
    }

    private function getBaseTimeline(): array {
        $tz = new DateTimeZone('UTC');
        $baseTimeline = [];
        try {
            $nowHour = new DateTime('now', $tz);
            $nowHour->setTime($nowHour->format('G'),0);
            $currentHour = new DateTime($this->firstDay,$tz);
            while ($currentHour <= $nowHour) {
                $baseTimeline[] = clone $currentHour;
                $currentHour->add(new DateInterval('PT1H'));
            }
        } catch (Exception $e) {
            $this->log->log($e->getMessage());
        }

        return $baseTimeline;
    }

    public function getFirstHour() : DateTime {
        $baseTimeline = $this->getBaseTimeline();
        $timeline = $this->store->getHourTimeline();
        $pairTimeline = [];
        foreach ($baseTimeline as $hour) {
            $pairTimeline[$hour->format('YmdH')] = [
                'b' => $hour,
                'r' => false,
            ];
        }

        foreach ($timeline as $hour) {
            if (array_key_exists($hour->format('YmdH'),$pairTimeline)) {
                $pairTimeline[$hour->format('YmdH')]['r'] = $hour;
            }
        }

        $tz = new DateTimeZone('UTC');
        $firstHour = new DateTime($this->firstDay,$tz);
        foreach ($pairTimeline as $pair) {
            if (false === $pair['r']) {
                $firstHour = $pair['b'];
                break;
            } else {
                $firstHour = $pair['r'];
            }
        }
        return $firstHour;

    }

    public function currentTimeIsFirst(DateTime $hour) : bool {
        $result = false;
        $tz = new DateTimeZone('UTC');

        $firstHour = new DateTime($this->firstDay,$tz);
        if ($hour->format('YmdH') == $firstHour->format('YmdH')) {
            $result = true;
        }
        return $result;
    }

    public function lastDiffForTimeInterval(DateTime $checkTime, DateInterval $interval) {
        $firstTime = clone $checkTime;
        $firstTime->sub($interval);
        $firstRate = $this->store->getFirstTradeForHour($firstTime);
        $lastRate = $this->store->getLastTradeForHour($checkTime);
        return $lastRate - $firstRate;
    }
}