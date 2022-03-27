<?php

namespace App\DataFixtures;

use App\Entity\User;
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
        $user = new User();
        $hashedPassword = $this->hasher->hashPassword($user, "testtest");
        $user->setFirstname("Leona")
            ->setLastname("Dupont")
            ->setEmail('leona@test.com')
            ->setPassword($hashedPassword)
            ->setAddress("3 rue des Mésanges")
            ->setPostalCode("33100")
            ->setCity('Toulouse');

    $manager->persist($user);
    $manager->flush();        
       
    }
}
