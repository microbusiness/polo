<?php

namespace App\Repository;

use App\Entity\PublicTradeHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicTradeHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicTradeHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicTradeHistory[]    findAll()
 * @method PublicTradeHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicTradeHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicTradeHistory::class);
    }

    /**
    * @return PublicTradeHistory[] Returns an array of PublicTradeHistory objects
    */
    public function findLastHour($baseCurrencyId,$marketCurrencyId,$lastHour)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.tradeDate > :begin')
            ->andWhere('p.baseCurrencyId = :base_currency_id')
            ->andWhere('p.marketCurrencyId = :market_currency_id')
            ->setParameter('begin', $lastHour)
            ->setParameter('base_currency_id', $baseCurrencyId)
            ->setParameter('market_currency_id', $marketCurrencyId)
            ->orderBy('p.tradeDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?PublicTradeHistory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
