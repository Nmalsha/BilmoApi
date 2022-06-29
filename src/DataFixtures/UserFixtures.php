<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher, CustomerRepository $customerRepository)
    {
        $this->hasher = $hasher;
        $this->customerRepository = $customerRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $customers = $this->customerRepository->findAll();

        //$customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();
        foreach ($customers as $customer) {
            for ($i = 0; $i < mt_rand(3, 10); $i++) {
                $user = new User();
                $hashedPassword = $this->hasher->hashPassword(
                    $user,
                    "password"
                );
                $user->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setContactNumber(0000000)
                    ->setPassword($hashedPassword)
                    ->setEmail($faker->email)
                    ->setRole(["ROLE_USER"])
                    ->setCustomer($customer);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}
