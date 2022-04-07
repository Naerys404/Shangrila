<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Meal;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\TableBooking;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {   

        $faker = Factory::create("fr-FR");

        //profil Admin
        $adminUser = new User();
        $adminUser->setFirstname('Naerys')
        ->setLastname('admin')
        ->setEmail("Naerys@test.com")
        ->setPassword($this->hasher->hashPassword($adminUser, 'testtest'))
        ->setAddress("17 place des coquelicots")
        ->setPostalCode("31330")
        ->setCity("Larra")
        ->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        //profils User
        $users=[];

        for($i=1; $i<=10; $i++){

            $user = new User();
           
            $hashedPassword = $this->hasher->hashPassword($user, "testtest");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastname())
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->numberBetween(11000,95850))
                ->setCity($faker->city());

                $manager->persist($user);
                $users[]= $user;
        
        }

        //réservations de tables
        for ($j=1; $j<=25; $j++){
            $tableBookings = [];
            
            $tableBooking = new TableBooking();
            $user = $users[mt_rand(0, count($users)-1)];

            $date = $faker->dateTimeBetween('now', '+2 months');
            $timeSheet = ["12h00", "19h00", "20h00", "21h00"];

            $tableBooking->setBooker($user)
            ->setDate($date)
            ->setTimesheet($timeSheet[rand(0,3)])
            ->setGuests(random_int(1,4));
            
            $manager->persist($tableBooking);
            $tableBookings[]=$tableBooking;
        }

        //creation de menus
        for ($k=1; $k<=4; $k++){

            $menu = new Menu();
            $menus = [];
    
            $menu->setTitle($faker->sentence(3))
                ->setPrice($faker->numberBetween(6,15))
                ->setDescription($faker->sentence())
                ->setImage($faker->imageUrl())
                ;
    
                $manager->persist($menu);
                $menus[]=$menu;
        }


        //creations de plats 
        for ($l=1; $l<=10; $l++){
            $meals = [];
            $meal = new Meal();
            $menu = $menus[rand(0, count($menus)-1)];

            $meal->setTitle($faker->sentence(5))
                ->setDescription($faker->sentence())
                ->setCategory($menu);

            $manager->persist($meal);
            $meals[]=$meal;

        }

    $manager->flush();        
       
    }
}
