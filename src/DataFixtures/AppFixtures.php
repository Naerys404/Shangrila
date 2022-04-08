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
                $manager->flush($users);
        
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

        //creation des menus principaux (petit dejeuner, dejeuner, diner et fastfood )

            $breakfast = new Menu();
            $lunch = new Menu();
            $diner = new Menu();
            $fastFood = new Menu();

            $menus = [];
    
            $breakfast->setTitle("Petit Déjeuner")
                ->setPrice(6)
                ->setDescription($faker->sentence())
                ->setImage('/img/breakfast.jpg')
                ;

                $manager->persist($breakfast);
                $menus[]=$breakfast;

            $lunch->setTitle("Déjeuner")
                ->setPrice(12)
                ->setDescription($faker->sentence())
                ->setImage('/img/lunch.jpg')
                ;
                $manager->persist($lunch);
                $menus[]=$lunch;

            $diner->setTitle("Diner")
                ->setPrice(15)
                ->setDescription($faker->sentence())
                ->setImage('/img/diner.jpg')
                ;
                $manager->persist($diner);
                $menus[]=$diner;
                
            $fastFood->setTitle("Fast Food")
                ->setPrice(12)
                ->setDescription($faker->sentence())
                ->setImage('/img/fastfood.jpg')
                ;
                $manager->persist($fastFood);
                $menus[]=$fastFood;

            $manager->flush($menus);


             //creations de plats pour etoffer chaque menu
           
                $meals = [];

                //PETIT DEJEUNER
                for ($a=1;$a<=3;$a++)
                {
                    $breakfastMeal = new Meal();
                    $breakfastMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($breakfast);
    
                    $manager->persist($breakfastMeal);
                    $meals[]=$breakfastMeal;
                }
              

                //DEJEUNER
                for ($b=1;$b<=3;$b++)
                {
                    $lunchMeal = new Meal();
                    $lunchMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($lunch);
    
                    $manager->persist($lunchMeal);
                    $meals[]=$lunchMeal;
                }

                //DINER

                for ($c=1;$c<=3;$c++)
                {
                    $dinerMeal = new Meal();
                    $dinerMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($diner);
    
                    $manager->persist($dinerMeal);
                    $meals[]=$dinerMeal;
                }

                //FASTFOOD

                for ($d=1;$d<=3;$d++)
                {
                    $fastFoodMeal = new Meal();
                    $fastFoodMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($fastFood);
    
                    $manager->persist($fastFoodMeal);
                    $meals[]=$fastFoodMeal;
                }


        
            $manager->flush();
       
       
    }
}
