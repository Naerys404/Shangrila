<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Stats{

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    //nombre d'users
    public function getUsersCount(){

        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u ')->getSingleScalarResult();
    }

    //nombre d'annonces
    public function getAdsCount(){

        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a ')->getSingleScalarResult();
    }

    //nombre de réservations
    public function getBookingsCount(){

        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    //nombre de commentaires
    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    // on récupère TOUTES les stats qui concernent les nombres d'user/ad/booking et comments => compactage dans le controller
    public function getStats(){
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users','ads', 'bookings', 'comments');
    }

    //récupération des annonces, avec en param 'ASC' ou 'DESC' pour avoir les pires et les meilleures suivant leur note
    public function getAdsStats($direction){

        return $this->manager->createQuery
        ('SELECT AVG(c.rating) as note, a.title, a.id, u.firstname, u.lastname, u.avatar
        FROM App\Entity\Comment c
        JOIN c.ad a
        JOIN a.author u
        GROUP BY a
        ORDER BY note '.$direction)
        ->setMaxResults(5)
        ->getResult();
    }


}