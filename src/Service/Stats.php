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
    public function getTableBookingsCount(){

        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\TableBooking a ')->getSingleScalarResult();
    }

    //nombre de réservations
    public function getMealsCount(){

        return $this->manager->createQuery('SELECT COUNT(m) FROM App\Entity\Meal m')->getSingleScalarResult();
    }

    public function getMenusCount(){
        return $this->manager->createQuery('SELECT COUNT(n) FROM App\Entity\Menu n')->getSingleScalarResult();
    }

    //nombre de commentaires
    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    //nombre de commandes
    public function getOrdersCount(){
        return $this->manager->createQuery('SELECT COUNT(o) FROM App\Entity\Order o')->getSingleScalarResult();
    }

    // on récupère TOUTES les stats qui concernent les nombres d'user/ad/booking et comments => compactage dans le controller
    public function getStats(){
        $users = $this->getUsersCount();
        $tableBookings = $this->getTableBookingsCount();
        $meals = $this->getMealsCount();
        $menus = $this->getMenusCount();
        $comments = $this->getCommentsCount();
        $orders = $this->getOrdersCount();

        return compact('users','tableBookings', 'meals', 'menus', 'comments', 'orders');
    }

    //récupération des commentaires, avec en param 'ASC' ou 'DESC' pour avoir les pires et les meilleures 
    public function getCommentstats($direction){

        return $this->manager->createQuery
        ('SELECT c.rating as rating, c.content, c.id, u.firstname, u.lastname
        FROM App\Entity\Comment c
        JOIN c.author u
        GROUP BY c
        ORDER BY rating '.$direction)
        ->setMaxResults(5)
        ->getResult();
    }


}