<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $code;
    /**
     * @ORM\Column(type="float")
     */
    private $txFee;
    /**
     * @ORM\Column(type="float")
     */
    private $minConf;
    /**
     * @ORM\Column(type="boolean")
     */
    private $frozen;
    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;
    /**
     * @ORM\Column(type="boolean")
     */
    private $delisted;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getTxFee() {
        return $this->txFee;
    }

    /**
     * @param mixed $txFee
     */
    public function setTxFee($txFee): void {
        $this->txFee = $txFee;
    }

    /**
     * @return mixed
     */
    public function getMinConf() {
        return $this->minConf;
    }

    /**
     * @param mixed $minConf
     */
    public function setMinConf($minConf): void {
        $this->minConf = $minConf;
    }

    /**
     * @return mixed
     */
    public function getFrozen() {
        return $this->frozen;
    }

    /**
     * @param mixed $frozen
     */
    public function setFrozen($frozen): void {
        $this->frozen = $frozen;
    }

    /**
     * @return mixed
     */
    public function getDisabled() {
        return $this->disabled;
    }

    /**
     * @param mixed $disabled
     */
    public function setDisabled($disabled): void {
        $this->disabled = $disabled;
    }

    /**
     * @return mixed
     */
    public function getDelisted() {
        return $this->delisted;
    }

    /**
     * @param mixed $delisted
     */
    public function setDelisted($delisted): void {
        $this->delisted = $delisted;
    }
}