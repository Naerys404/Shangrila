<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\TableBooking;
use DateTime;
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
                ->setPostalCode($faker->randomNumber(5, true))
                ->setCity($faker->city());

                $manager->persist($user);
                $users[]= $user;
        
        }

        //réservations de tables
        for ($j=0; $j<=25; $j++){
            $tableBookings = [];
            
            $tableBooking = new TableBooking();
            $user = $users[mt_rand(0, count($users)-1)];

            $date = $faker->dateTimeBetween('now', '+3 months');
            $timeSheet = ["12h00", "19h00", "20h00", "21h00"];

            $tableBooking->setBooker($user)
            ->setDate($date)
            ->setTimesheet($timeSheet[rand(0,3)])
            ->setGuests(random_int(1,4));
            
            $manager->persist($tableBooking);
            $tableBookings[]=$tableBooking;
        }

        //creations de plats + menus

    $manager->flush();        
       
    }
}
