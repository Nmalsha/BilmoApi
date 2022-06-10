<?php
namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setSiret($faker->numberBetween(0, 99999))
                ->setName($faker->word)
                ->setContactNumber($faker->numberBetween(0, 9))

                ->setEmail($faker->email)
                ->setPassword($faker->password);
            $manager->persist($customer);

        }

        $manager->flush();
    }
}
