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


        //profil User
        $user = new User();
        $hashedPassword = $this->hasher->hashPassword($user, "testtest");
        $user->setFirstname("Jinx")
            ->setLastname("Powder")
            ->setEmail("jinx@test.com")
            ->setPassword($hashedPassword)
            ->setAddress("3 rue des poros")
            ->setPostalCode("99999")
            ->setCity("Piltover");

    $manager->persist($user);
    $manager->flush();        
       
    }
}
