<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function isConflictWithAnother(Booking $booking) {
        return 0 !== $this->createQueryBuilder('booking')
            ->select('count(booking)')
            ->innerJoin('booking.room', 'room')
            ->where('room = :room')
            ->andWhere('(booking.finishAt > :beginAt AND booking.finishAt <= :finishAt) OR 
                        (booking.beginAt >= :beginAt AND booking.beginAt < :finishAt) OR 
                        (booking.beginAt >= :beginAt AND booking.finishAt <= :finishAt) OR 
                        (booking.beginAt <= :beginAt AND booking.finishAt >= :finishAt)')
            ->setParameter('room', $booking->getRoom())
            ->setParameter('beginAt', $booking->getBeginAt())
            ->setParameter('finishAt', $booking->getFinishAt())
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Booking $booking
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function clientAlreadyHaveBooking(Booking $booking) {
        return 0 !== $this->createQueryBuilder('booking')
            ->select('count(booking)')
            ->innerJoin('booking.client', 'client')
            ->where('client = :client')
            ->andWhere('(booking.finishAt > :beginAt AND booking.finishAt <= :finishAt) OR 
                    (booking.beginAt >= :beginAt AND booking.beginAt < :finishAt) OR 
                    (booking.beginAt >= :beginAt AND booking.finishAt <= :finishAt) OR 
                    (booking.beginAt <= :beginAt AND booking.finishAt >= :finishAt)')
            ->setParameter('beginAt', $booking->getBeginAt())
            ->setParameter('finishAt', $booking->getFinishAt())
            ->setParameter('client', $booking->getClient())
            ->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
