<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->word)
                ->setDescription($faker->sentences(4, true))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setPrice($faker->randomFloat(1, 50, 2000));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
