<?php

namespace App\Entity;

use App\Repository\PublicTradeHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicTradeHistoryRepository::class)
 */
class PublicTradeHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $globalTradeId;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseCurrencyId;

    /**
     * @ORM\Column(type="integer")
     */
    private $marketCurrencyId;

    /**
     * @ORM\Column(type="integer")
     */
    private $tradeId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tradeDate;

    /**
     * @ORM\Column(type="string")
     */
    private $tradeType;

    /**
     * @ORM\Column(type="float")
     */
    private $tradeRate;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderNumber;

    public function __construct($currencyIdList) {
        $this->baseCurrencyId = $currencyIdList['base_currency'];
        $this->marketCurrencyId = $currencyIdList['market_currency'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGlobalTradeId(): ?int
    {
        return $this->globalTradeId;
    }

    public function setGlobalTradeId(int $globalTradeId): self
    {
        $this->globalTradeId = $globalTradeId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeId()
    {
        return $this->tradeId;
    }

    /**
     * @param mixed $tradeId
     */
    public function setTradeId($tradeId): void
    {
        $this->tradeId = $tradeId;
    }

    /**
     * @return mixed
     */
    public function getTradeDate()
    {
        return $this->tradeDate;
    }

    /**
     * @param mixed $tradeDate
     */
    public function setTradeDate($tradeDate): void
    {
        $this->tradeDate = $tradeDate;
    }

    /**
     * @return mixed
     */
    public function getTradeType()
    {
        return $this->tradeType;
    }

    /**
     * @param mixed $tradeType
     */
    public function setTradeType($tradeType): void
    {
        $this->tradeType = $tradeType;
    }

    /**
     * @return mixed
     */
    public function getTradeRate()
    {
        return $this->tradeRate;
    }

    /**
     * @param mixed $tradeRate
     */
    public function setTradeRate($tradeRate): void
    {
        $this->tradeRate = $tradeRate;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param mixed $orderNumber
     */
    public function setOrderNumber($orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return mixed
     */
    public function getBaseCurrencyId() {
        return $this->baseCurrencyId;
    }

    /**
     * @param mixed $baseCurrencyId
     */
    public function setBaseCurrencyId($baseCurrencyId): void {
        $this->baseCurrencyId = $baseCurrencyId;
    }

    /**
     * @return mixed
     */
    public function getMarketCurrencyId() {
        return $this->marketCurrencyId;
    }

    /**
     * @param mixed $marketCurrencyId
     */
    public function setMarketCurrencyId($marketCurrencyId): void {
        $this->marketCurrencyId = $marketCurrencyId;
    }

}
