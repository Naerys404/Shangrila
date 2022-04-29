<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Blog;
use App\Entity\Meal;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Comment;
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

        //profil Admin (pas de résa ni commentaires pour tester soi-même si besoin)
        $adminUser = new User();
        $adminUser->setFirstname('Naerys')
        ->setLastname('admin')
        ->setEmail("Naerys@test.com")
        ->setPassword($this->hasher->hashPassword($adminUser, 'testtest'))
        ->setAddress("17 place des coquelicots")
        ->setPostalCode(31330)
        ->setCity("Larra")
        ->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        //profils User + ajout d'un commentaire par user
        $users=[];
        $comments=[];

        for($i=1; $i<=10; $i++){

            $user = new User();
            $comment = new Comment();
           
            //creation des users
            $hashedPassword = $this->hasher->hashPassword($user, "testtest");
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastname())
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->numberBetween(11000,95850))
                ->setCity($faker->city())
                ->setComment($comment);

            
                $users[]= $user;
                $manager->persist($user);
                

                //creation des commentaires (1 par user) après avoir persisté les users pour obtenir les id
                $comment->setAuthor($user)
                ->setRating(random_int(0,5))
                ->setContent($faker->sentence(15))
                ->setPublicView(random_int(0,1));

                $comments[]=$comment;
                $manager->persist($comment);
                $manager->flush($user);
        
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

        //creation des menus du restaurant (petit dejeuner, dejeuner, diner et fastfood )

            $breakfast = new Menu();
            $lunch = new Menu();
            $diner = new Menu();
            $fastFood = new Menu();

            $menus = [];
    
            $breakfast->setTitle("Petit Déjeuner")
                ->setPrice(6)
                ->setDescription($faker->sentence(15))
                ->setImage('/img/breakfast.jpg')
                ;

                $manager->persist($breakfast);
                $menus[]=$breakfast;

            $lunch->setTitle("Déjeuner")
                ->setPrice(12)
                ->setDescription($faker->sentence(15))
                ->setImage('/img/lunch.jpg')
                ;
                $manager->persist($lunch);
                $menus[]=$lunch;

            $diner->setTitle("Diner")
                ->setPrice(15)
                ->setDescription($faker->sentence(15))
                ->setImage('/img/diner.jpg')
                ;
                $manager->persist($diner);
                $menus[]=$diner;
                
            $fastFood->setTitle("Fast Food")
                ->setPrice(12)
                ->setDescription($faker->sentence(15))
                ->setImage('/img/fastfood.jpg')
                ;
                $manager->persist($fastFood);
                $menus[]=$fastFood;

            $manager->flush($menus);


             //creations de plats pour étoffer chaque menu
           
                $meals = [];

                //PETIT DEJEUNER
                for ($a=1;$a<=3;$a++)
                {
                    $breakfastMeal = new Meal();
                    $breakfastMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($breakfast)
                        //pour mettre une image nommée de 1 à 3 (qui correspondent à des petit dejeuners)
                        ->setImage("/img/$a.jpg");
    
                    $manager->persist($breakfastMeal);
                    $meals[]=$breakfastMeal;
                }
              

                //DEJEUNER
                for ($b=4;$b<=6;$b++)
                {
                    $lunchMeal = new Meal();
                    $lunchMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($lunch)
                        //meme principe on commence la boucle à 4 pour avoir les images de plats salés
                        ->setImage("/img/$b.jpg");
    
                    $manager->persist($lunchMeal);
                    $meals[]=$lunchMeal;
                }

                //DINER

                for ($c=7;$c<=9;$c++)
                {
                    $dinerMeal = new Meal();
                    $dinerMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($diner)
                        ->setImage("/img/$c.jpg");
    
                    $manager->persist($dinerMeal);
                    $meals[]=$dinerMeal;
                }

                //FASTFOOD

                for ($d=10;$d<=12;$d++)
                {
                    $fastFoodMeal = new Meal();
                    $fastFoodMeal->setTitle($faker->sentence(3))
                        ->setDescription($faker->sentence())
                        ->setCategory($fastFood)
                        ->setImage("/img/$d.jpg");
    
                    $manager->persist($fastFoodMeal);
                    $meals[]=$fastFoodMeal;
                }

            //BLOG - création d'articles 
            for ($i=1;$i<=3;$i++)
            {
                $blogs = [];
                $blog = new Blog();
                $blog->setTitle($faker->sentence(3))
                    ->setContent($faker->sentence(100))
                    ->setAuthor($adminUser->getFirstname())
                    ->setImage("/img/news-$i.jpg");

                $manager->persist($blog);
                $blogs[]=$blog;
            }

            $manager->flush();
       
       
    }
}
