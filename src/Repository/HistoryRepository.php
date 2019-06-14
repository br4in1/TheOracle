<?php
/**
 * Created by PhpStorm.
 * User: br4in
 * Date: 2019-05-23
 * Time: 15:10
 */

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * @param canteen
     * @return History[]
     */
    public function getMonthlyDNOG($canteen): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select(' SUM(h.number), month(h.date) AS gBmonth, YEAR(h.date) AS gBday')
            ->where('h.date IS NOT NULL')
            ->andWhere('h.canteen = :canteen')
            ->groupBy('gBmonth')
            ->addGroupBy('gBday')
            ->setParameter('canteen',$canteen)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @param canteen
     * @return History[]
     */
    public function getYearlyDNOG($canteen): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select(' SUM(h.number), YEAR(h.date) AS gBday')
            ->where('h.date IS NOT NULL')
            ->andWhere('h.canteen = :canteen')
            ->groupBy('gBday')
            ->setParameter('canteen',$canteen)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @param canteen
     * @return History[]
     */
    public function getGuestsByMonth($canteen): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select(' SUM(h.number), Month(h.date) AS gBday')
            ->where('h.date IS NOT NULL')
            ->andWhere('h.canteen = :canteen')
            ->groupBy('gBday')
            ->setParameter('canteen',$canteen)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @param canteen
     * @return History[]
     */
    public function getGuestsByDayOfWeek($canteen): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select(' SUM(h.number), dayofweek(h.date) AS gBday')
            ->where('h.date IS NOT NULL')
            ->andWhere('h.canteen = :canteen')
            ->groupBy('gBday')
            ->setParameter('canteen',$canteen)
            ->getQuery();

        return $qb->execute();
    }
}