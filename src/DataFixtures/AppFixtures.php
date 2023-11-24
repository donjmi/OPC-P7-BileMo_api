<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Product;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserRepository $userRepo, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->userRepo = $userRepo;
        $this->passwordEncoder = $passwordEncoder;
    }   
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // add products in database
       for ($j = 0; $j < 20; $j++) {
           //$users = $this->getReference('user_' . $faker->numberBetween(1, 9));

           $product = new Product();
           $product->setBrand($faker->randomElement(['Apple', 'Samsung', 'Huawei', 'Xiaomi', 'LG', 'Google']));
           $product->setDescription($faker->sentence(10));
           $product->setPrice($faker->randomFloat(0, 199, 1200));

           $manager->persist($product);
       }
        $manager->flush();


        // add clients in database
        $clients = ['ORANGE', 'SFR', 'BOUYGUES', 'FREE', 'NJR MOBILE'];

        foreach ($clients as $key => $item) {
            $client= new Client();
            $client->setName($item);

            $manager->persist($client);
            $this->addReference('client_' . $key, $client);
            $manager->flush();
        }

        
        // add user admin + user client in database
        for ($u = 1; $u <= 4; $u++) {
            $clients = $this->getReference('client_' . $faker->numberBetween(1, 4));
            $user = new User();
            $user->setEmail($faker->email);

            if ($u === 1) {
                $user->setEmail("admin@test.fr");
                $user->setRoles(['ROLE_ADMIN']);
                $user->setClient($clients);
                $user->setPassword(
                    $this->passwordEncoder->hashPassword(
                        $user,
                        'admin'
                    )
                );
            }  
            elseif ($u === 2) {
                $user->setEmail("bilemo@bilemo.fr")
                    ->setRoles(['ROLE_ADMIN'])
                    ->setClient($clients)
                    ->setPassword($this->passwordEncoder->hashPassword($user, 'admin'));
            }
            else {
                $user->setRoles(['ROLE_USER'])
                    ->setClient($clients)
                    ->setPassword($this->passwordEncoder->hashPassword($user, '123456'));
            }

            $manager->persist($user);
            $manager->flush();
        }
    }
}
